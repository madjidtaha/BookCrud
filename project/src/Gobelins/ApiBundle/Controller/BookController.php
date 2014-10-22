<?php
/**
 * Created by PhpStorm.
 * User: mtaha
 * Date: 22/10/2014
 * Time: 14:57
 */

namespace Gobelins\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Util\Codes;
use Gobelins\BookBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookController
 * @package Gobelins\ApiBundle\Controller
 *
 */

class BookController extends Controller{


    /**
     * @Route("/books")
     */

    public function getBooksAction(){

        $books = $this->getDoctrine()->getRepository('GobelinsBookBundle:Book')->findAll();

        $view = $this->view($books, Codes::HTTP_OK);

        return $this->handleView($view);

    }

    /**
     * @Route("/books/{id}", requirements={"id" = "\d+"})
     */

    public function getBookAction($id){

        $book = $this->getDoctrine()->getRepository('GobelinsBookBundle:Book')->find($id);


        if (!empty($book)) {

            $view = $this->view($book, Codes::HTTP_OK);

        } else {

            $error = ["error" => "Ressource not found"];

            $view = $this->view($error, Codes::HTTP_NOT_FOUND);

        }

        return $this->handleView($view);

    }

    public function postBookAction(Request $request)
    {

        $form = $this->get('form.factory')->createNamed('form', new BookType(), null, ["csrf_protection" => false]);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $book = $form->getData();

            $users = $this->getDoctrine()->getRepository('GobelinsUserBundle:User')->findAll();

            if (!empty($users)) {

                $book->setAuthor($users[0]);

            } else {

                $error = ["error" => "No author exist in database"];

                $view = $this->view($error, Codes::HTTP_BAD_REQUEST);

                return $this->handleView($view);

            }

            $em = $this->getDoctrine()->getManager();

            $em->persist($book);

            $em->flush();

            $success = ["success" => "News created"];

            $view = $this->view($success, Codes::HTTP_CREATED);

        } else {

            $error = ["error" => (string)$form->getErrors()];

            $view = $this->view($error, Codes::HTTP_BAD_REQUEST);

        }

    }
    public function patchBookAction(Request $request, $id)
    {

        $book = $this->getDoctrine()->getRepository('GobelinsBookBundle:Book')->find($id);

        if( !empty($book) ){

            $form = $this->get('form.factory')->createNamed('form', new BookType(), $book, [
                "csrf_protection" => false,
                "method" => $request->getMethod()
            ]);

            $form->handleRequest($request);

            if ($form->isValid()) {

                $book = $form->getData();

                $users = $this->getDoctrine()->getRepository('GobelinsUserBundle:User')->findAll();

                if (!empty($users)) {

                    $book->setAuthor($users[0]);

                } else {

                    $error = ["error" => "No author exist in database"];

                    $view = $this->view($error, Codes::HTTP_BAD_REQUEST);

                    return $this->handleView($view);

                }

                $em = $this->getDoctrine()->getManager();

                $em->persist($book);

                $em->flush();

                $success = ["success" => "News modified"];

                $view = $this->view($success, Codes::HTTP_OK);

            } else {

                $error = ["error" => (string) $form->getErrors()];

                $view = $this->view($error, Codes::HTTP_BAD_REQUEST);

            }
            return $this->handleView($view);

        } else {

            $error = ["error" => "Le livre n'existe pas"];

            $view = $this->view($error, Codes::HTTP_BAD_REQUEST);

        }
        return $this->handleView($view);

    }
    public function deleteBookAction(Request $request, $id)
    {

        $book = $this->getDoctrine()->getRepository('GobelinsBookBundle:Book')->find($id);

        if(!empty($book)){

            $em = $this->getDoctrine()->getManager();

            $em->remove($book);

            $em->flush();

            $success = ["success" => "Le livre a été supprimé"];

            $view = $this->view($success, Codes::HTTP_OK);


        } else {

            $error = ["error" => "Le livre n'existe pas"];

            $view = $this->view($error, Codes::HTTP_BAD_REQUEST);

        }
        return $this->handleView($view);

    }


} 