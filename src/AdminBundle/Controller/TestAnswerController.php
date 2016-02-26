<?php
namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TestAnswer;
use AppBundle\Form\TestAnswerType;

/**
 * Class TestAnswerController
 * @package AdminBundle\Controller
 * @Route("/admin/testanswer")
 */
class TestAnswerController extends Controller{
    const ENTITY_NAME = 'TestAnswer';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{questionId}", name="admin_testanswer_list")
     * @Template()
     */
    public function listAction($questionId){
        $question = $this->getDoctrine()->getRepository('AppBundle:TestQuestion')->findOneById($questionId);
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findByQuestion($question);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('testanswer', 1),
            20
        );

        return array('pagination' => $pagination, 'questionId' => $questionId);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/add/{questionId}", name="admin_testanswer_add")
     * @Template()
     */
    public function addAction(Request $request,$questionId){
        $em = $this->getDoctrine()->getManager();
        $question = $this->getDoctrine()->getRepository('AppBundle:TestQuestion')->findOneById($questionId);
        $item = new TestAnswer();
        $form = $this->createForm(new TestAnswerType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setQuestion($question);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                $question->addAnswer($item);
                $em->flush($question);
                $em->refresh($question);
                return $this->redirect($this->generateUrl('admin_testanswer_list', array('questionId' => $questionId)));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="admin_testanswer_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $question = $item->getQuestion();
        $form = $this->createForm(new TestAnswerType($em), $item);
        $formData = $form->handleRequest($request);
        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setQuestion($question);
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_testanswer_list', array('questionId' =>$question->getId() )));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="admin_testanswer_remove")
     */
    public function removeAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        if ($item){
            $em->remove($item);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
}