<?php
// src/GameProject/GameBundle/Controller/CategoryController.php

namespace GameProject\GameBundle\Controller;

use GameProject\GameBundle\Entity\Category;
use GameProject\GameBundle\Entity\CategoryContent;
use GameProject\GameBundle\Form\CategoryContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    public function adminIndexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $dql = "SELECT a FROM GameProjectGameBundle:Category a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            5
        );

        return $this->render('GameProjectAdminBundle:Category:index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function getFormAction(Request $request, $subdomain_id, $category_id)
    {
        if ($request->isXmlHttpRequest()) {
            $subdomains = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectAdminBundle:Subdomain')->findAll();

            //Find category content if exists
            $repository = $this->getDoctrine()
                ->getRepository('GameProjectGameBundle:CategoryContent');
            $query = $repository->createQueryBuilder('c')
                ->where('c.category = :category')
                ->andWhere('c.subdomain = :subdomain')
                ->setParameters([
                    'subdomain' => $subdomain_id,
                    'category' => $category_id
                ])
                ->getQuery();
            $categoryContent = $query->getOneOrNullResult();

            if (!$categoryContent) {
                $categoryContent = new CategoryContent();
            }

            $form = $this->createForm(new CategoryContentType(), $categoryContent);
            $form->add('subdomain', 'hidden', [
                'data' => $subdomain_id
            ]);
            $form->add('category', 'hidden', [
                'data' => $category_id
            ]);

            return $this->render('GameProjectAdminBundle:Category/forms:updateForm.html.twig', [
                'form' => $form->createView(),
                'subdomains' => $subdomains,
                'subdomain_id' => $subdomain_id,
                'category_id' => $category_id
            ]);
        }
    }

    public function saveFormAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getEntityManager();
            $categoryContentPost = $this->get('request')->request->get('categoryContent');

            //Find category content if exists
            $repository = $this->getDoctrine()
                ->getRepository('GameProjectGameBundle:CategoryContent');
            $query = $repository->createQueryBuilder('c')
                ->where('c.category = :category')
                ->andWhere('c.subdomain = :subdomain')
                ->setParameters([
                    'subdomain' => $categoryContentPost['subdomain'],
                    'category' => $categoryContentPost['category']
                ])
                ->getQuery();
            $categoryContent = $query->getOneOrNullResult();

            if (!$categoryContent) {
                $categoryContent = new CategoryContent();
            } else {
                $categoryContent = $repository->find($categoryContent->getId());
            }

            $subdomain = $this->getDoctrine()->getRepository('GameProjectAdminBundle:Subdomain')->find($categoryContentPost['subdomain']);
            $category = $this->getDoctrine()->getRepository('GameProjectGameBundle:Category')->find($categoryContentPost['category']);

            //If saved form is for english subdomain, set category displayName to that name
            if ($categoryContentPost['subdomain'] == $this->getSubdomainIdWhereEng()->getId()) {
                $category->setDisplayName($categoryContentPost['name']);
            }

            $categoryContent->setName($categoryContentPost['name']);
            $categoryContent->setSubdomain($subdomain);
            $categoryContent->setCategory($category);
            $categoryContent->setIsActive((isset($categoryContentPost['isActive']) ? true : false));

            $em->persist($categoryContent);
            $em->flush();

            $response = new JsonResponse();
            return $response->setData(array(
                'success' => 1,
                'error' => 0,
                'message' => 'Category has been saved'
            ));

        }
    }

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $categoryContent = new CategoryContent();
        $form = $this->createForm(new CategoryContentType(), $categoryContent);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $category = new Category();
            $category->setDisplayName($form->get('name')->getData());
            $em->persist($category);
            $em->flush();

            //Find subdomain id where abbreviation is en
            $eng_subdomain = $this->getSubdomainIdWhereEng();

            $categoryContent->setSubdomain($eng_subdomain);
            $categoryContent->setCategory($category);
            $em->persist($categoryContent);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'notice',
                'The category was created!'
            );

            //return $this->redirect($this->generateUrl('admin_category_index'));
            return $this->redirect($this->generateUrl('admin_update_category_view', ['id' => $category->getId()]));
        }

        return $this->render('GameProjectAdminBundle:Category:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository('GameProjectGameBundle:Category')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('The category does not exist');
        }

        $subdomains = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectAdminBundle:Subdomain')->findAll();

        //Find subdomain id where abbreviation is en
        $eng_subdomain = $this->getSubdomainIdWhereEng();

        //Find category content if exists
        $repository = $this->getDoctrine()
            ->getRepository('GameProjectGameBundle:CategoryContent');
        $query = $repository->createQueryBuilder('c')
            ->where('c.category = :category')
            ->andWhere('c.subdomain = :subdomain')
            ->setParameters([
                'subdomain' => $eng_subdomain->getId(),
                'category' => $id
            ])
            ->getQuery();
        $categoryContent = $query->getOneOrNullResult();

        if (!$categoryContent) {
            $categoryContent = new CategoryContent();
        }

        $form = $this->createForm(new CategoryContentType(), $categoryContent);
        $form->add('subdomain', 'hidden', [
            'data' => $eng_subdomain->getId()
        ]);
        $form->add('category', 'hidden', [
            'data' => $id
        ]);

        return $this->render('GameProjectAdminBundle:Category:update.html.twig', [
            'form' => $form->createView(),
            'subdomains' => $subdomains,
            'subdomain_id' => $eng_subdomain->getId(),
            'category_id' => $id
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