<?php
namespace AdminBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\JournalPost;
use AppBundle\Form\JournalPostType;

/**
 * Class JournalPostController
 * @package AdminBundle\Controller
 * @Route("/admin/journalpost")
 */
class JournalPostController extends Controller{
        const ENTITY_NAME = 'JournalPost';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{journalId}/list", name="admin_journalpost_list")
     * @Template()
     */
    public function listAction(Request $request, $journalId){
        $journal = $this->getDoctrine()->getRepository('AppBundle:Journal')->findOneBy(['id' => $journalId]);
//        dump($journal);
//        exit;
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findBy(['journal' => $journal]);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $request->query->get('page', 1),
            20
        );

        return array('pagination' => $pagination, 'journal' => $journal);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{journalId}/add", name="admin_journalpost_add")
     * @Template()
     */
    public function addAction(Request $request, $journalId){
        $journal = $this->getDoctrine()->getRepository('AppBundle:Journal')->find($journalId);
        $em = $this->getDoctrine()->getManager();
        $item = new JournalPost();
        $form = $this->createForm(JournalPostType::class, $item);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
//                $item->setPhoto(['path' => '/upload/journalpost/'.$filename ]);
                $item->setJournal($journal);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_journal_edit', ['id' => $journalId]));
            }
        }
        return array('form' => $form->createView(), 'journal' => $journal);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="admin_journalpost_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(JournalPostType::class, $item);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $formData = $form->handleRequest($request);

        $olfFile = $item->getPhoto();


        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $file = $item->getPhoto();
                if ($file == null){
                    $item->setPhoto($olfFile);
                }else{
                    $filename = time(). '.'.$file->guessExtension();
                    $file->move(
                        __DIR__.'/../../../web/upload/journalpost/',
                        $filename
                    );
                    $item->setPhoto(['path' => '/upload/journalpost/'.$filename ]);
                }

                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_journalpost_list'));
            }
        }
        return array('form' => $form->createView(), 'item' => $item);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="admin_journalpost_remove")
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