<?php

namespace Gobelins\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Util\Codes;
use Gobelins\NewsBundle\Form\NewsType;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends Controller
{
    /**
     * @Route("/news")
     *
     */
    public function getNewsAction()
    {

        $news = $this->getDoctrine()->getRepository('GobelinsNewsBundle:News')->findAll();

/*      $serializer = $container->get('jms_serializer');

        $serializer->serialize($news, $format);*/

        $view = $this->view($news, 200);

        return $this->handleView($view);

    }
    /**
     * @Route("/news/{id}", requirements={"id" = "\d+"})
     *
     */
    public function getANewsAction($id)
    {

        $news = $this->getDoctrine()->getRepository('GobelinsNewsBundle:News')->find($id);

        if (!empty($news)) {

            $view = $this->view($news, Codes::HTTP_OK);

        } else {

            $error = ["error" => "Ressource not found"];

            $view = $this->view($error, Codes::HTTP_NOT_FOUND);

        }

        return $this->handleView($view);

    }


    public function postNewsAction(Request $request)
    {

        $form = $this->get('form.factory')->createNamed('form', new NewsType(), null, [ "csrf_protection" => false ]);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $news = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($news);

            $em->flush();

            $success = ["success" => "News created"];

            $view = $this->view($success, Codes::HTTP_CREATED);

        } else {

            $error = ["error" => (string) $form->getErrors()];

            $view = $this->view($error, Codes::HTTP_BAD_REQUEST);

        }
        return $this->handleView($view);

    }

    public function patchNewsAction(Request $request, $id)
    {

        $news = $this->getDoctrine()->getRepository('GobelinsNewsBundle:News')->find($id);

        $form = $this->get('form.factory')->createNamed('form', new NewsType(), $news, [
            "csrf_protection" => false,
            "method" => $request->getMethod()
        ]);

        if (!empty($news)) {

            $form->handleRequest($request);

            if($form->isValid()) {

                $news = $form->getData();

                $em = $this->getDoctrine()->getManager();

                $em->persist($news);

                $em->flush();

                $success = ["success" => "News modified"];

                $view = $this->view($success, Codes::HTTP_OK);

            } else {

                $error = ["error" => "Bad request" . (string) $form->getErrors()];

                $view = $this->view($error, Codes::HTTP_BAD_REQUEST);

            }

        } else {

            $error = ["error" => "Ressource not found"];

            $view = $this->view($error, Codes::HTTP_NOT_FOUND);

        }

        return $this->handleView($view);

    }

}
