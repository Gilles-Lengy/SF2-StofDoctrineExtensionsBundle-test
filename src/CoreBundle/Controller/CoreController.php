<?php

namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TreeBundle\Entity\Category;

class CoreController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        /** Pour générer un arbre * *
          $food = new Category();
          $food->setTitle('Food');


          $fruits = new Category();
          $fruits->setTitle('Fruits');
          $fruits->setParent($food);

          $vegetables = new Category();
          $vegetables->setTitle('Vegetables');
          $vegetables->setParent($food);

          $carrots = new Category();
          $carrots->setTitle('Carrots');
          $carrots->setParent($vegetables);

          $em->persist($food);
          $em->persist($fruits);
          $em->persist($vegetables);
          $em->persist($carrots);
          $em->flush();
          /* */
        $repo = $em->getRepository('TreeBundle:Category');

        $food = $repo->findOneByTitle('Food');
        echo $repo->childCount($food);
        echo $repo->childCount($food, true/* direct */);
        //$children = $repo->children($food);
        //var_dump($children);
        //$children = $repo->children($food, false, 'title');
        $arrayTree = $repo->childrenHierarchy();
        var_dump($arrayTree);


//        $htmlTree = $repo->childrenHierarchy(
//                null, /* starting from root nodes */ false, /* true: load all children, false: only direct */ array(
//            'decorate' => true,
//            'representationField' => 'slug',
//            'html' => true
//                )
//        );



        return $this->render('CoreBundle:Main:index.html.twig', array(
                    'arrayTree' => $arrayTree
                        )
        );
    }

    public function menuAction($limit) {

        return $this->render('CoreBundle:Main:menu.html.twig');
    }

}
