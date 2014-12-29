<?php
// src/GameProject/GameBundle/Controller/GameController.php

namespace GameProject\GameBundle\Controller;

use GameProject\GameBundle\Entity\Game;
use GameProject\GameBundle\Form\GameType;
//use Proxies\__CG__\GameProject\AdminBundle\Entity\Subdomain;
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
            3
        );

        return $this->render('GameProjectAdminBundle:Game:index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function addAction(Request $request)
    {
        $game = new Game();
        $form = $this->createForm(new GameType(), $game);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $linkPost = $form->get('link')->getData();
            $subdomainPost = $form->get('subdomain')->getViewData();
            $link = '';

            if ($subdomainPost && $subdomainPost > 0) {
                //If there is subdomains in database and got from form, get subdomain by id
                $subdomain = $this->getDoctrine()->getRepository('GameProjectAdminBundle:Subdomain')->find($subdomainPost);

                //Appending abbreviation from choosed subdomain to link
                $link = $subdomain->getAbbreviation() . '.' . $linkPost;
            }

            $em = $this->getDoctrine()->getManager();
            $game->setLinkDisplay($link);
            $game->setIsActive(true);
            $em->persist($game);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'notice',
                'The game was created!'
            );

            return $this->redirect($this->generateUrl('admin_game_index'));
        }

        return $this->render('GameProjectAdminBundle:Game:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction(Request $request, $id)
    {
        $game = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectGameBundle:Game')->find($id);
        $form = $this->createForm(new GameType(), $game);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $linkPost = $form->get('link')->getData();
            $subdomainPost = $form->get('subdomain')->getData();
            $link = '';

            if ($subdomainPost && $subdomainPost != '') {
                //If there is subdomains in database and got from form, get subdomain by id
                $subdomain = $this->getDoctrine()->getRepository('GameProjectAdminBundle:Subdomain')->find($subdomainPost);

                //Appending abbreviation from choosed subdomain to link
                $link = $subdomain->getAbbreviation() . '.' . $linkPost;
            }

            $em = $this->getDoctrine()->getManager();
            $game->setLinkDisplay($link);
            $em->persist($game);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'notice',
                'The game was updated!'
            );

            return $this->redirect($this->generateUrl('admin_game_index'));
        }

        return $this->render('GameProjectAdminBundle:Game:update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $game = $em->getRepository('GameProjectGameBundle:Game')->find($id);

        if (!$game) {
            throw $this->createNotFoundException('The game does not exist');
        }

        $em->remove($game);
        $em->flush();

        $request->getSession()->getFlashBag()->add(
            'notice',
            'The game was deleted!'
        );

        return $this->redirect($this->generateUrl('admin_game_index'));
    }

    public function toggleActiveAction(Request $request, $id)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getEntityManager();
            $response = new JsonResponse();

            $game = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectGameBundle:Game')->find($id);

            if (!$game) {
                return $response->setData([
                    'success' => 0,
                    'error' => 1,
                    'message' => 'The game does not exist'
                ]);
            }

            if ($game->getIsActive() == 1) {
                $game->setIsActive(false);
                $em->persist($game);
                $em->flush();

                $message = 'The game was turned off';
                $active = 0;
            } else {
                $game->setIsActive(true);
                $em->persist($game);
                $em->flush();

                $message = 'The game was turned on';
                $active = 1;
            }

            return $response->setData([
                'success' => 1,
                'error' => 0,
                'data' => $game->getIsActive(),
                'message' => $message,
                'params' => [
                    'id' => $game->getId(),
                    'active' => $active
                ]
            ]);

        }

        throw $this->createNotFoundException('The route does not exist');
    }
}