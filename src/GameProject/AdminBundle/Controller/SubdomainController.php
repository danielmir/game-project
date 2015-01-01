<?php
// src/GameProject/GameBundle/Controller/SubdomainController.php

namespace GameProject\AdminBundle\Controller;

use GameProject\AdminBundle\Entity\Subdomain;
use GameProject\AdminBundle\Form\SubdomainType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SubdomainController extends Controller
{
    public function adminIndexAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $dql = "SELECT a FROM GameProjectAdminBundle:Subdomain a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            5
        );

        return $this->render('GameProjectAdminBundle:Subdomain:index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function addAction(Request $request)
    {
        $subdomain = new Subdomain();
        $form = $this->createForm(new SubdomainType(), $subdomain);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $subdomain->setIsActive(true);
            $em->persist($subdomain);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'notice',
                'The subdomain was created!'
            );

            return $this->redirect($this->generateUrl('admin_subdomain_index'));
        }

        return $this->render('GameProjectAdminBundle:Subdomain:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction(Request $request, $id)
    {
        $subdomain = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectAdminBundle:Subdomain')->find($id);
        $form = $this->createForm(new SubdomainType(), $subdomain);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subdomain);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'notice',
                'The subdomain was updated!'
            );

            return $this->redirect($this->generateUrl('admin_subdomain_index'));
        }

        return $this->render('GameProjectAdminBundle:Subdomain:update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $subdomain = $em->getRepository('GameProjectAdminBundle:Subdomain')->find($id);

        if (!$subdomain) {
            throw $this->createNotFoundException('The subdomain does not exist');
        }

        $em->remove($subdomain);
        $em->flush();

        $request->getSession()->getFlashBag()->add(
            'notice',
            'The subdomain was deleted!'
        );

        return $this->redirect($this->generateUrl('admin_subdomain_index'));
    }

    public function toggleActiveAction(Request $request, $id)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getEntityManager();
            $response = new JsonResponse();

            $subdomain = $this->getDoctrine()->getEntityManager()->getRepository('GameProjectAdminBundle:Subdomain')->find($id);

            if (!$subdomain) {
                return $response->setData([
                    'success' => 0,
                    'error' => 1,
                    'message' => 'The subdomain does not exist'
                ]);
            }

            if ($subdomain->getIsActive() == 1) {
                $subdomain->setIsActive(false);
                $em->persist($subdomain);
                $em->flush();

                $message = 'The subdomain was turned off';
                $active = 0;
            } else {
                $subdomain->setIsActive(true);
                $em->persist($subdomain);
                $em->flush();

                $message = 'The subdomain was turned on';
                $active = 1;
            }

            return $response->setData([
                'success' => 1,
                'error' => 0,
                'data' => $subdomain->getIsActive(),
                'message' => $message,
                'params' => [
                    'id' => $subdomain->getId(),
                    'active' => $active
                ]
            ]);

        }

        throw $this->createNotFoundException('The route does not exist');
    }
}