<?php
// src/GameProject/AdminBundle/Controller/SubdomainController.php

namespace GameProject\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SubdomainController extends Controller
{
    public function indexAction()
    {
        return $this->render('GameProjectAdminBundle:Subdomain:index.html.twig');
    }
}