<?php

namespace Gobelins\NewsBundle\Controller;

use Gobelins\NewsBundle\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends Controller
{
    /**
     * @Route("/", name="news_list")
     */
    public function listAction()
    {
        $news = $this->getDoctrine()->getRepository('GobelinsNewsBundle:News')->findAll();

        return $this->render('GobelinsNewsBundle:News:list.html.twig', [

            'news' => $news

        ]);
    }


    /**
     * @Route("/create", name="news_create")
     */
    public function createAction(Request $request)
    {

        $form = $this->createForm(new NewsType());

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if($form->isValid()) {

                $news = $form->getData();

                if ($this->getUser()){

                    $news->setAuthor($this->getUser());

                } else {

                    $this->get('session')->getFlashBag()->add('error', 'Vous devez etre logué pour poster une news');

                    return $this->redirect($this->generateUrl('fos_user_security_login'));
                }

                $em = $this->getDoctrine()->getManager();

                $em->persist($news);

                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'La news a bien été ajouté');

                return $this->redirect($this->generateUrl('news_list'));

            }

        }

        return $this->render('GobelinsNewsBundle:News:create.html.twig', [

            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/update/{id}", requirements={"id" = "\d+"}, name="news_update")
     *
     */
    public function updateAction(Request $request, $id)
    {

        $news = $this->getDoctrine()->getRepository('GobelinsNewsBundle:News')->find($id);


        if (!empty($news)) {

            $form = $this->createForm(new NewsType(), $news);

            if ($request->isMethod('POST')) {

                $form->handleRequest($request);

                if($form->isValid()) {

                    $news = $form->getData();

                    if ($this->getUser()){

                        $news->setAuthor($this->getUser());

                    } else {

                        $this->get('session')->getFlashBag()->add('error', 'Vous devez etre logué pour modifier une news');

                        return $this->redirect($this->generateUrl('fos_user_security_login'));
                    }

                    $em = $this->getDoctrine()->getManager();

                    $em->persist($news);

                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', 'La news a bien été modifié');

                    return $this->redirect($this->generateUrl('news_list'));

                }

            }

            return $this->render('GobelinsNewsBundle:News:edit.html.twig', [

                'form' => $form->createView()

            ]);

        } else {

            $this->get('session')->getFlashBag()->add('error', 'La news n\'existe pas');

            return $this->redirect($this->generateUrl('news_list'));

        }


    }
}
