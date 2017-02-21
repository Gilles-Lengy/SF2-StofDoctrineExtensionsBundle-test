<?php

namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TreeBundle\Entity\Category;
use TreeBundle\Form\CategoryType;
use TreeBundle\Entity\Item;
use TreeBundle\Form\ItemType;
use TreeBundle\Form\PartieType;
use TreeBundle\Form\ChapitreType;
use TreeBundle\Form\SectionType;

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

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('CoreBundle:Tree:addCategory.html.twig', array(
                    'form' => $form->createView(),
                        )
        );
    }

    /**
     * @Route("/add/partie/", name="add_partie")
     */
    public function addPartieAction(Request $request) {

        $category = new Category();
        $form = $this->get('form.factory')->create(new PartieType(), $category);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Category bien enregistrée.');

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('CoreBundle:Tree:addPartie.html.twig', array(
                    'form' => $form->createView(),
                        )
        );
    }

    /**
     * @Route("/add/chapitre/", name="add_chapitre")
     */
    public function addChapitreAction(Request $request) {

        $category = new Category();
        $form = $this->get('form.factory')->create(new ChapitreType(), $category);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Category bien enregistrée.');

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('CoreBundle:Tree:addChapitre.html.twig', array(
                    'form' => $form->createView(),
                        )
        );
    }

    /**
     * @Route("/add/section/", name="add_section")
     */
    public function addSectionAction(Request $request) {

        $category = new Category();
        $form = $this->get('form.factory')->create(new SectionType(), $category);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Category bien enregistrée.');

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('CoreBundle:Tree:addSection.html.twig', array(
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

        $listItems = $em->getRepository('TreeBundle:Item')->findBy(
                array('category' => $category), // Pas de critère
                array('title' => 'asc')
        );

        return $this->render('CoreBundle:Tree:viewCategory.html.twig', array(
                    'category' => $category,
                    'path' => $path,
                    'listItems' => $listItems
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

        $form = $this->get('form.factory')->create(new PartieType(), $category);

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

    /**
     * @Route("/view/category/item/{id}", name="view_item")
     */
    public function viewItemAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getEntityManager();

        $item = $em->getRepository('TreeBundle:Item')->findOneById($id);

        $repo = $em->getRepository('TreeBundle:Category');
        $category = $item->getCategory();
        $path = $repo->getPath($category);
        //var_dump($path);


        return $this->render('CoreBundle:Tree:viewItem.html.twig', array(
                    'category' => $category,
                    'path' => $path,
                    'item' => $item
                        )
        );
    }

    /**
     * @Route("getjsonchapitre/", name="get_json_chapitre")
     */
    public function getJsonChapitreAction() {
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) { // pour vérifier la présence d'une requete Ajax
            $id_section = $request->request->get('id');

            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('TreeBundle:Category');


            if ($id_section != null) {
                $chapitre = $repo->getChildrenArrayResult($id_section);
                //var_dump($chapitre);

                $response = new Response();
                $chapitre_json = json_encode(array('chapitre' => $chapitre));
                //var_dump($chapitre_json);
                $response->headers->set('Content-Type', 'application/json');
                $response->setContent($chapitre_json);
                return $response;
            }
        }

        return new Response("KKKKKOOOOOOO");
    }

}
