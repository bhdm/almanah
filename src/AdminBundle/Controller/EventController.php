<?php
namespace AdminBundle\Controller;

use AppBundle\Entity\EventPage;
use AppBundle\Form\EventPageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;

/**
 * Class EventController
 * @package AdminBundle\Controller
 * @Route("/admin/event")
 */
class EventController extends Controller{
        const ENTITY_NAME = 'Event';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/", name="admin_event_list")
     * @Template()
     */
    public function listAction(Request $request){

        $name = $request->query->get('name');
        $date = new \DateTime($request->query->get('date'));
        if ($name || $date){
            $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->filter(null,$date,null,$name, null);
        }else{
            $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findBy([],['start' => 'ASC']);
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $request->query->get('page', 1),
            20
        );

        return array('pagination' => $pagination);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/add", name="admin_event_add")
     * @Template()
     */
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $item = new Event();
        $form = $this->createForm(EventType::class, $item);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();

                if ($request->request->get('thumbail')){
                    $image = new \Imagick();
                    $image->readImageBlob($this->convertBase64Image($request->request->get('thumbail')));
                    $image->setImageFormat('png');
                    $filename = '/upload/event/'.time().'.'.$image->getImageFormat();
                    $image->writeImage(__DIR__.'/../../../web'.$filename);
                    $item->setPreview(['path' => $filename]);
                }

                $file = $item->getSlider();
                if ($file){
                    $filename = time(). '.'.$file->guessExtension();
                    $file->move(
                        __DIR__.'/../../../web/upload/eventslider/',
                        $filename
                    );
                    $item->setSlider(['path' => '/upload/eventslider/'.$filename ]);
                }

                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_event_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="admin_event_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $oldFile2 = $item->getSlider();

        $form = $this->createForm(EventType::class, $item);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();

                if ($request->request->get('thumbail')){
                    $image = new \Imagick();
                    $image->readImageBlob($this->convertBase64Image($request->request->get('thumbail')));
                    $image->setImageFormat('png');
                    $filename = '/upload/event/'.time().'.'.$image->getImageFormat();
                    $image->writeImage(__DIR__.'/../../../web'.$filename);
                    $item->setPreview(['path' => $filename]);
                }

                $file = $item->getSlider();
                if ($file == null){
                    $item->setSlider($oldFile2);
                }else{
                    $filename = time(). '.'.$file->guessExtension();
                    $file->move(
                        __DIR__.'/../../../web/upload/eventslider/',
                        $filename
                    );
                    $item->setSlider(['path' => '/upload/eventslider/'.$filename ]);
                }

                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_event_list'));
            }
        }

        $pagesForm = [];
        foreach ($item->getPages() as $page){
            $pageForm = $this->createForm(EventPageType::class, $page);
            $pageForm->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
            $pagesForm[$page->getId()] = $pageForm->createView();
        }

        $newPage = new EventPage();
        $newForm = $this->createForm(EventPageType::class, $newPage);
        $newForm->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $newForm = $newForm->createView();


        return array('item' => $item, 'form' => $form->createView(), 'pagesForm' => $pagesForm, 'newForm' => $newForm);
    }


    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}/create-page", name="admin_event_create_page")
     * @Template()
     */
    public function createPageAction(Request $request, $id){
        $event = $this->getDoctrine()->getRepository('AppBundle:Event')->find($id);
        $em = $this->getDoctrine()->getManager();

        $page = new EventPage();

        $form = $this->createForm(EventPageType::class, $page);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);

        $formData = $form->handleRequest($request);

        if ($formData->isValid()){
            $page = $formData->getData();
            $page->setEvent($event);
            $em->persist($page);
            $em->flush($page);
            $em->refresh($page);
            return $this->redirect($this->generateUrl('admin_event_edit', ['id' => $id]));
        }else{
            return $this->redirectToRoute('admin_event_edit',['id' => $id]);
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}/edit-page/{pageId}", name="admin_event_edit_page")
     * @Template()
     */
    public function editPageAction(Request $request, $id, $pageId){

        $em = $this->getDoctrine()->getManager();

        $page = $this->getDoctrine()->getRepository('AppBundle:EventPage')->find($pageId);

        $form = $this->createForm(EventPageType::class, $page);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);

        $formData = $form->handleRequest($request);

        if ($formData->isValid()){
            $page = $formData->getData();
            $em->persist($page);
            $em->flush($page);
            $em->refresh($page);
            return $this->redirect($this->generateUrl('admin_event_edit', ['id' => $id]));
        }else{
            return $this->redirectToRoute('admin_event_edit',['id' => $id]);
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}/remove-page/{pageId}", name="admin_event_remove_page")
     */
    public function removePageAction(Request $request, $id, $pageId){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:EventPage')->findOneById($pageId);
        if ($item){
            $em->remove($item);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="admin_event_remove")
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

    private function convertBase64Image($base64_image_string) {
        $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
        $data= $splited[1];
        return base64_decode($data);
    }



}