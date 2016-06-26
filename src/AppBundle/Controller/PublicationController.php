<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Event;
use AppBundle\Form\EventUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PublicationController extends Controller
{
    /**
     * @Route("/news/{url}", name="publications")
     * @Template("AppBundle:Publication:publication.html.twig")
     */
    public function indexAction(Request $request, $url)
    {
        $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->findOneBy(['slug' => $url,'enabled' => true]);
        if (!$publication){
            $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->findOneBy(['id' => $url,'enabled' => true]);
            if ($publication === null){
                return $this->createNotFoundException('Данной страницы не существует');
            }
            if ($publication->getSlug()){
                return $this->redirect($this->generateUrl('publications',['url' => $publication->getSlug()]));
            }
        }
        return ['publication' => $publication];
    }

    /**
     * @Template("AppBundle:Publication:page.html.twig")
     */
    public function pageAction(Request $request, $url)
    {
        $page = $this->getDoctrine()->getRepository('AppBundle:Page')->findOneBySlug($url);
        if ($page === null){
            throw $this->createNotFoundException('Данной страницы не существует');
        }
        return ['page' => $page];
    }

    /**
     * @Route("event/{url}", name="event", options={"expose"=true})
     * @Template("AppBundle:Publication:event.html.twig")
     */
    public function eventAction(Request $request, $url)
    {
        $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findOneById($url);
        $importants =  $this->getDoctrine()->getRepository('AppBundle:Calendar')->findBy(['enabled' => true],['id' => 'DESC'], 3);
        return ['event' => $event, 'importants' => $importants];
    }

    /**
     * @Route("events/{url}", name="events", defaults={"url"=null})
     * @Template("AppBundle:Publication:eventList.html.twig")
     */
    public function eventListAction(Request $request, $url = null)
    {

        if ($url !== null){
            $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findOneBy(['slug' => $url]);
        }else{
            $category = null;
        }
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $text = $request->query->get('searchtext');
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->filter($category,$start,$end,$text);

        if ($category == null){
            $category = "Мероприятия";
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events,
            $request->query->get('page', 1),
            15
        );

//        $specialt/ies = $this->getDoctrine()->getRepository('AppBundle:Specialty')->findAll();

        $form = $this->createFormBuilder()
            ->add('start', TextType::class, ['label' => 'c', 'attr' => ['class' => 'form-calendar'],'required' => false])
            ->add('end', TextType::class, ['label' => 'c', 'attr' => ['class' => 'form-calendar'],'required' => false])
            ->add('specialty', EntityType::class, [
                'label' => '',
                'class' => 'AppBundle\Entity\Specialty',
                'required' => false,
                'placeholder' => 'Специальность',

            ])
            ->add('search', TextType::class, ['label' => '', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Фильтровать', 'attr' => ['class' => 'btn-primary']])
//            ->add('button', ButtonType::class, ['label' => 'Добавить событие', 'attr' => ['class' => 'btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        /**
         * @TODO event@medalmanah.ru
         */
        return ['events' => $pagination, 'category' => $category, 'form' => $form->createView()];
    }


    /**
     * @Route("category/{categoryUrl}", name="category")
     * @Template("AppBundle:Publication:category.html.twig")
     */
    public function categotyAction($categoryUrl){
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findOneBySlug($categoryUrl);
        $publications = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled' => true, 'category' => $category ]);

        return ['category' => $category,'publications' => $publications];
    }


    /**
     * @Route("news", name="news")
     * @Template("AppBundle:Publication:news.html.twig")
     */
    public function newsAction(Request $request){
        $news = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled' => true],['created' => 'DESC']);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news,
            $request->query->get('page', 1),
            15
        );
        return ['news' => $pagination];
    }

    /**
     * @param Request $request
     * @return array
     * @Route("/comment-add/{id}/{type}", name="comment_add", requirements={"type" = "publication|event|course"})
     * @Method("POST")
     */
    public function commentAddAction(Request $request, $id, $type){
        $em = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $comment->setOwner($this->getUser());
        $comment->setBody($request->request->get('comment'));
        if ($type === 'publication'){
            $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->findOneBy(['id' => $id]);
            $comment->setPublication($publication);
        }elseif($type === 'event'){
            $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findOneBy(['id' => $id]);
            $comment->setEvent($event);
        }else{
            throw $this->createNotFoundException('Вы пытаетесь прикрепить комментарий к странице, на который запрещены комментарии');
        }
        $em->persist($comment);
        $em->flush($comment);

        $session = $request->getSession();
        $session->getFlashBag()->add('notice', 'Ваш комментарий оставлен');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("/event-add", name="event_add")
     * @Template("")
     */
    public function eventAddAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $item = new Event();
        $form = $this->createForm(EventUserType::class, $item);
        $form->add('submit', SubmitType::class, ['label' => 'Отправить заявку', 'attr' => ['class' => 'btn-primary']]);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();

                $file = $item->getPreview();
                if ($file){
                    $filename = time(). '.'.$file->guessExtension();
                    $file->move(
                        __DIR__.'/../../../web/upload/event/',
                        $filename
                    );
                    $item->setPreview(['path' => '/upload/event/'.$filename ]);
                }
                $item->setEnabled(false);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);

                $this->addFlash(
                    'notice',
                    'Ваша заявка на добавление события отправлена и будер расмотрена в ближайшее время'
                );

                return $this->redirect($this->generateUrl('homepage'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/events-map", name="events_map")
     * @Template()
     */
    public function eventsmapAction(){
        return [];
    }

}