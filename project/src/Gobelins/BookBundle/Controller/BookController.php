<?php

namespace Gobelins\BookBundle\Controller;

use Gobelins\BookBundle\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    /**
     * @Route("/", name="book_list")
     *
     */
    public function listAction()
    {
        $books = $this->getDoctrine()->getRepository('GobelinsBookBundle:Book')->findAll();

        return $this->render('GobelinsBookBundle:Default:list.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("/create", name="book_create")
     */
    public function createAction(Request $request)
    {

        $form = $this->createForm(new BookType());

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if($form->isValid()) {

                $book = $form->getData();

                if ($this->getUser()){

                    $book->setAuthor($this->getUser());

                }else{

                    $this->get('session')->getFlashBag()->add('error', 'Vous devez etre logué pour poster une news');

                    return $this->redirect($this->generateUrl('fos_user_security_login'));
                }

                $em = $this->getDoctrine()->getManager();

                $em->persist($book);

                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Le livre a bien été ajouté');

                return $this->redirect($this->generateUrl('book_list'));

            }

        }

        return $this->render('GobelinsBookBundle:Default:create.html.twig', [

            'form' => $form->createView()

        ]);

    }

    /**
     * @Route("/update/{id}", requirements={"id" = "\d+"}, name="book_update")
     *
     */
    public function updateAction(Request $request, $id)
    {

        $book = $this->getDoctrine()->getRepository('GobelinsBookBundle:Book')->find($id);


        if (!empty($book)) {

            $form = $this->createForm(new BookType(), $book);

            if ($request->isMethod('POST')) {

                $form->handleRequest($request);

                if ($form->isValid()) {

                    $book = $form->getData();

                    if ($this->getUser()) {

                        $book->setAuthor($this->getUser());

                    } else {

                        $this->get('session')->getFlashBag()->add('error', 'Vous devez etre logué pour modifier un livre');

                        return $this->redirect($this->generateUrl('fos_user_security_login'));
                    }

                    $em = $this->getDoctrine()->getManager();

                    $em->persist($book);

                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', 'Le livre a bien été modifié');

                    return $this->redirect($this->generateUrl('book_list'));

                }

            }

            return $this->render('GobelinsBookBundle:Default:edit.html.twig', [

                'form' => $form->createView()

            ]);

        } else {

            $this->get('session')->getFlashBag()->add('error', 'Le livre n\'existe pas');

            return $this->redirect($this->generateUrl('book_list'));

        }

    }

    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"}, name="book_delete")
     *
     */
    public function deleteAction(Request $request, $id)
    {

        $book = $this->getDoctrine()->getRepository('GobelinsBookBundle:Book')->find($id);


        if (!empty($book)) {


            $em = $this->getDoctrine()->getManager();

            $em->remove($book);

            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Le livre a bien été supprimé');

            return $this->redirect($this->generateUrl('book_list'));


        } else {

            $this->get('session')->getFlashBag()->add('error', 'Le livre n\'existe pas');

            return $this->redirect($this->generateUrl('book_list'));

        }

    }

}
