<?php
// src/GameProject/GameBundle/Controller/GameController.php

namespace GameProject\GameBundle\Controller;

use GameProject\GameBundle\Entity\Game;
use GameProject\GameBundle\Entity\GameContent;
use GameProject\GameBundle\Form\GameContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{
    public function adminIndexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $dql = "SELECT a FROM GameProjectGameBundle:Game a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            5
        );

        return $this->render('GameProjectAdminBundle:Game:index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function getFormAction(Request $request, $subdomain_id, $game_id)
    {
        if ($request->isXmlHttpRequest()) {
            $subdomains = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectAdminBundle:Subdomain')->findAll();
            $subdomain = $this->getDoctrine()->getRepository('GameProjectAdminBundle:Subdomain')->find($subdomain_id);

            //Find game content if exists
            $repository = $this->getDoctrine()
                ->getRepository('GameProjectGameBundle:GameContent');
            $query = $repository->createQueryBuilder('g')
                ->where('g.game = :game')
                ->andWhere('g.subdomain = :subdomain')
                ->setParameters([
                    'subdomain' => $subdomain_id,
                    'game' => $game_id
                ])
                ->getQuery();
            $gameContent = $query->getOneOrNullResult();

            if (!$gameContent) {
                $gameContent = new GameContent();
            }

            $form = $this->createForm(new GameContentType($subdomain), $gameContent);
            $form->add('subdomain', 'hidden', [
                'data' => $subdomain_id
            ]);
            $form->add('game', 'hidden', [
                'data' => $game_id
            ]);

            return $this->render('GameProjectAdminBundle:Game/forms:updateForm.html.twig', [
                'form' => $form->createView(),
                'subdomains' => $subdomains,
                'subdomain_id' => $subdomain_id,
                'category_id' => $game_id
            ]);
        }
        return $this->createNotFoundException('No page found');
    }

    public function saveFormAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $response = new JsonResponse();

            $em = $this->getDoctrine()->getEntityManager();
            $gameContentPost = $this->get('request')->request->get('gameContent');

            if ($gameContentPost['name'] == '' || $gameContentPost['description'] == '' || $gameContentPost['link'] == '') {
                return $response->setData(array(
                    'success' => 0,
                    'error' => 1,
                    'message' => 'Name, description and link fields are required'
                ));
            }

            //Find game content if exists
            $repository = $this->getDoctrine()
                ->getRepository('GameProjectGameBundle:GameContent');
            $query = $repository->createQueryBuilder('g')
                ->where('g.game = :game')
                ->andWhere('g.subdomain = :subdomain')
                ->setParameters([
                    'subdomain' => $gameContentPost['subdomain'],
                    'game' => $gameContentPost['game']
                ])
                ->getQuery();
            $gameContent = $query->getOneOrNullResult();

            if (!$gameContent) {
                $gameContent = new GameContent();
            } else {
                $gameContent = $repository->find($gameContent->getId());
            }

            $subdomain = $this->getDoctrine()->getRepository('GameProjectAdminBundle:Subdomain')->find($gameContentPost['subdomain']);
            $game = $this->getDoctrine()->getRepository('GameProjectGameBundle:Game')->find($gameContentPost['game']);

            //If saved form is for english subdomain, set game displayName to that name
            if ($gameContentPost['subdomain'] == $this->getSubdomainIdWhereEng()->getId()) {
                $game->setDisplayName($gameContentPost['name']);
            }

            $gameContent->setLinkDisplay($subdomain->getAbbreviation() . '.' . $gameContentPost['link']);
            $gameContent->setName($gameContentPost['name']);
            $gameContent->setLink($gameContentPost['link']);
            $gameContent->setDescription($gameContentPost['description']);
            $gameContent->setGame($game);
            $gameContent->setSubdomain($subdomain);
            //Delete all many to many relations of games and categories, before inserting new categories
            foreach ($gameContent->getCategoryContents() as $categoryContent) {
                $gameContent->removeCategoryContent($categoryContent);
            }
            if (isset($gameContentPost['category_contents'])) {
                foreach ($gameContentPost['category_contents'] as $key=>$categoryContent) {
                    $categoryContent = $this->getDoctrine()->getRepository('GameProjectGameBundle:CategoryContent')->find($categoryContent);
                    if (!$gameContent->getCategoryContents()->contains($categoryContent)) {
                        $gameContent->addCategoryContent($categoryContent);
                    }
                }
            }
            $gameContent->setIsActive((isset($gameContentPost['isActive']) ? true : false));

            $em->persist($gameContent);
            $em->flush();

            return $response->setData(array(
                'success' => 1,
                'error' => 0,
                'message' => 'Category has been saved'
            ));

        }
        return $this->createNotFoundException('No page found');
    }

    public function addAction(Request $request)
    {
        //Find subdomain id where abbreviation is en
        $eng_subdomain = $this->getSubdomainIdWhereEng();

        $em = $this->getDoctrine()->getEntityManager();
        $gameContent = new GameContent();
        $form = $this->createForm(new GameContentType($eng_subdomain), $gameContent);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $game = new Game();
            $game->setDisplayName($form->get('name')->getData());
            $em->persist($game);
            $em->flush();

            $gameContent->setLinkDisplay($eng_subdomain->getAbbreviation() . '.' . $form->get('link')->getData());
            $gameContent->setSubdomain($eng_subdomain);
            $gameContent->setGame($game);
            $em->persist($gameContent);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_update_game_view', ['id' => $game->getId()]));
        }

        return $this->render('GameProjectAdminBundle:Game:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction(Request $request, $id)
    {
        $game = $this->getDoctrine()->getRepository('GameProjectGameBundle:Game')->find($id);

        if (!$game) {
            throw $this->createNotFoundException('The game does not exist');
        }

        $subdomains = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectAdminBundle:Subdomain')->findAll();

        //Find subdomain id where abbreviation is en
        $eng_subdomain = $this->getSubdomainIdWhereEng();

        //Find category content if exists
        $repository = $this->getDoctrine()
            ->getRepository('GameProjectGameBundle:GameContent');
        $query = $repository->createQueryBuilder('g')
            ->where('g.game = :game')
            ->andWhere('g.subdomain = :subdomain')
            ->setParameters([
                'subdomain' => $eng_subdomain->getId(),
                'game' => $id
            ])
            ->getQuery();
        $gameContent = $query->getOneOrNullResult();

        if (!$gameContent) {
            $gameContent = new GameContent();
        }

        $form = $this->createForm(new GameContentType($eng_subdomain), $gameContent);
        $form->add('subdomain', 'hidden', [
            'data' => $eng_subdomain->getId()
        ]);
        $form->add('game', 'hidden', [
            'data' => $id
        ]);

        return $this->render('GameProjectAdminBundle:Game:update.html.twig', [
            'form' => $form->createView(),
            'subdomains' => $subdomains,
            'subdomain_id' => $eng_subdomain->getId(),
            'game_id' => $id
        ]);
    }

    private function getSubdomainIdWhereEng()
    {
        $repository = $this->getDoctrine()
            ->getRepository('GameProjectAdminBundle:Subdomain');
        $query = $repository->createQueryBuilder('s')
            ->where('s.abbreviation = :abbr')
            ->setParameter('abbr', 'en')
            ->getQuery();
        $eng_subdomain = $query->getSingleResult();

        return $eng_subdomain;
    }

}