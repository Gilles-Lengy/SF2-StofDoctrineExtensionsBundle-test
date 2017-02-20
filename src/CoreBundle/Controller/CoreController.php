<?php

namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TreeBundle\Entity\Category;
use TreeBundle\Form\CategoryType;
use TreeBundle\Entity\Item;
use TreeBundle\Form\ItemType;

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

//        $food = $repo->findOneByTitle('Food');
//        echo $repo->childCount($food);
//        echo $repo->childCount($food, true/* direct */);
        //$children = $repo->children($food);
        //var_dump($children);
        //$children = $repo->children($food, false, 'title');
        $arrayTree = $repo->childrenHierarchy();
        //var_dump($arrayTree);
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

    /**
     * @Route("/add/category/", name="add_category")
     */
    public function addCategoryAction(Request $request) {

        $category = new Category();
        $form = $this->get('form.factory')->create(new CategoryType(), $category);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Category bien enregistrée.');

            return $this->redirect($this->generateUrl('add_category'));
        }

        return $this->render('CoreBundle:Tree:addCategory.html.twig', array(
                    'form' => $form->createView(),
                        )
        );
    }

    /**
     * @Route("/view/category/{slug}", name="view_category")
     */
    public function viewCategoryAction(Request $request, $slug) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('TreeBundle:Category');
        $category = $repo->findOneBySlug($slug);
        $path = $repo->getPath($category);
        //var_dump($path);

        return $this->render('CoreBundle:Tree:viewCategory.html.twig', array(
                    'category' => $category,
                    'path' => $path,
                        )
        );
    }

    /**
     * @Route("/edit/category/{slug}", name="edit_category")
     */
    public function editCategoryAction(Request $request, $slug) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('TreeBundle:Category');
        $category = $repo->findOneBySlug($slug);
        $path = $repo->getPath($category);
        //var_dump($path);

        $form = $this->get('form.factory')->create(new CategoryType(), $category);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Category bien enregistrée.');

            return $this->redirect($this->generateUrl('view_category', array("slug" => $category->getSlug())));
        }

        return $this->render('CoreBundle:Tree:editCategory.html.twig', array(
                    'category' => $category,
                    'path' => $path,
                    'form' => $form->createView(),
                        )
        );
    }

    public function menuAction($limit) {

        return $this->render('CoreBundle:Main:menu.html.twig');
    }

    /**
     * @Route("/add/Item/{slug_category}", name="add_item")
     */
    public function addItemAction(Request $request, $slug_category) {

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('TreeBundle:Category');
        $category = $repo->findOneBySlug($slug_category);
        $path = $repo->getPath($category);

        $item = new Item();
        $form = $this->get('form.factory')->create(new ItemType(), $item);

        if ($form->handleRequest($request)->isValid()) {
            $item->setCreated(new \Datetime());
            $item->setUpdated(new \Datetime());
            $item->setCategory($category);
            $em->persist($item);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Item bien enregistrée.');

            return $this->redirect($this->generateUrl('view_category', array("slug" => $slug_category)));
        }

        return $this->render('CoreBundle:Tree:addItem.html.twig', array(
                    'category' => $category,
                    'path' => $path,
                    'form' => $form->createView(),
                        )
        );
    }

}
