<?php

namespace Vivait\BootstrapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VivaitBootstrapBundle:Default:index.html.twig', array('name' => $name));
    }
}
