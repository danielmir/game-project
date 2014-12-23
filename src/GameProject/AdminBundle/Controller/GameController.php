<?php
// src/GameProject/AdminBundle/Controller/GameController.php

namespace GameProject\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    public function indexAction()
    {
        return $this->render('GameProjectAdminBundle:Game:index.html.twig');
    }

    public function addAction()
    {
        return $this->render('GameProjectAdminBundle:Game:add.html.twig');
    }
}