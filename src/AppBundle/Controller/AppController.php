<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        return $this->render('AppBundle:App:index.html.twig');
    }

    public function menuAction($limit) {

        return $this->render('AppBundle:App:menu.html.twig');
    }

}