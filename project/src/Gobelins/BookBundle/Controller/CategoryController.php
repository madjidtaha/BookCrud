<?php
/**
 * Created by PhpStorm.
 * User: mtaha
 * Date: 22/10/2014
 * Time: 14:08
 */

namespace Gobelins\BookBundle\Controller;

use Gobelins\BookBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends Controller {

    /**
     * @Route("/category", name="category_list")
     *
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()->getRepository('GobelinsBookBundle:Category')->findAll();

        return $this->render('GobelinsBookBundle:Default:list_category.html.twig', [
            'categories' => $categories
        ]);
    }
    /**
     * @Route("/category/create", name="category_create")
     */
    public function createAction(Request $request)
    {

        $form = $this->createForm(new CategoryType());

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if($form->isValid()) {

                $category = $form->getData();

                $categories = $this->getDoctrine()->getRepository('GobelinsBookBundle:Category')->findAll();


                foreach ($categories as $cat) {

                    if (strtolower($cat->getName()) == strtolower($category->getName()) ){

                        $this->get('session')->getFlashBag()->add('error', 'La categorie existe déjà');

                        return $this->redirect($this->generateUrl('category_list'));
                    }

                }

                $em = $this->getDoctrine()->getManager();

                $em->persist($category);

                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'La categorie a bien été ajouté');

                return $this->redirect($this->generateUrl('category_list'));

            }

        }

        return $this->render('GobelinsBookBundle:Default:create_category.html.twig', [

            'form' => $form->createView()

        ]);

    }
} 