<?php
// src/GameProject/GameBundle/Controller/CategoryController.php

namespace GameProject\GameBundle\Controller;

use GameProject\GameBundle\Entity\Category;
use GameProject\GameBundle\Form\CategoryType;
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
            3
        );

        return $this->render('GameProjectAdminBundle:Category:index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $category->setIsActive(true);
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'notice',
                'The category was created!'
            );

            return $this->redirect($this->generateUrl('admin_category_index'));
        }

        return $this->render('GameProjectAdminBundle:Category:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectGameBundle:Category')->find($id);
        $form = $this->createForm(new CategoryType(), $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'notice',
                'The category was updated!'
            );

            return $this->redirect($this->generateUrl('admin_category_index'));
        }

        return $this->render('GameProjectAdminBundle:Category:update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $category = $em->getRepository('GameProjectGameBundle:Category')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('The category does not exist');
        }

        $em->remove($category);
        $em->flush();

        $request->getSession()->getFlashBag()->add(
            'notice',
            'The category was deleted!'
        );

        return $this->redirect($this->generateUrl('admin_category_index'));
    }

    public function toggleActiveAction(Request $request, $id)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getEntityManager();
            $response = new JsonResponse();

            $category = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectGameBundle:Category')->find($id);

            if (!$category) {
                return $response->setData([
                    'success' => 0,
                    'error' => 1,
                    'message' => 'The category does not exist'
                ]);
            }

            if ($category->getIsActive() == 1) {
                $category->setIsActive(false);
                $em->persist($category);
                $em->flush();

                $message = 'The category was turned off';
                $active = 0;
            } else {
                $category->setIsActive(true);
                $em->persist($category);
                $em->flush();

                $message = 'The category was turned on';
                $active = 1;
            }

            return $response->setData([
                'success' => 1,
                'error' => 0,
                'data' => $category->getIsActive(),
                'message' => $message,
                'params' => [
                    'id' => $category->getId(),
                    'active' => $active
                ]
            ]);

        }

        throw $this->createNotFoundException('The route does not exist');
    }
}