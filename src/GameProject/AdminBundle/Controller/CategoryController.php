<?php
// src/GameProject/AdminBundle/Controller/CategoryController.php

namespace GameProject\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function indexAction()
    {
        return $this->render('GameProjectAdminBundle:Category:index.html.twig');
    }
}