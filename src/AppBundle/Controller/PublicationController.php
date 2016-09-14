<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Event;
use AppBundle\Entity\Publication;
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
     * @Route("/new/{url}", name="publications")
     * @Route("/news/{url}")
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
        }
        if ($publication->getSlug()){
            if ($publication->getType() == 0){
                return $this->redirect($this->generateUrl('article',['url' => $publication->getSlug()]));
            }elseif($publication->getType() == 1){
                return $this->redirect($this->generateUrl('new',['url' => $publication->getSlug()]));
            }elseif($publication->getType() == 2){
                return $this->redirect($this->generateUrl('study',['url' => $publication->getSlug()]));
            }else{
                return $this->createNotFoundException('Данной страницы не существует');
            }
        }else{
            if ($publication->getType() == 0){
                return $this->redirect($this->generateUrl('article',['url' => $publication->getId()]));
            }elseif($publication->getType() == 1){
                return $this->redirect($this->generateUrl('new',['url' => $publication->getId()]));
            }elseif($publication->getType() == 2){
                return $this->redirect($this->generateUrl('study',['url' => $publication->getId()]));
            }else{
                return $this->createNotFoundException('Данной страницы не существует');
            }
        }
    }

    /**
     * @Route("/publications/new/{url}")
     */
    public function newOldAction($url){
        return $this->redirectToRoute('new',['url' => $url]);
    }

    /**
     * @Route("/publications/news/{url}", name="new")
     * @Template("AppBundle:Publication:publication.html.twig")
     */
    public function newAction($url){
        $publication = $this->getPublication($url, 1);

        if ($publication instanceof Publication){
            $featuredPublications = $this->getFeaturedPublications($publication);
            return ['publication' => $publication, 'featuredPublications' => $featuredPublications ];
        }else{
            return $publication;
        }
    }

    /**
     * @Route("/publications/study/{url}", name="study")
     */
    public function studyAction($url){
        $publication = $this->getPublication($url, 2);
        if ($publication instanceof Publication){
            $featuredPublications = $this->getFeaturedPublications($publication);
            return ['publication' => $publication, 'featuredPublications' => $featuredPublications ];
        }else{
            return $publication;
        }
    }

    /**
     * @Route("/publications/articles/{url}", name="article")
     * @Template("AppBundle:Publication:publication.html.twig")
     */
    public function articleAction($url){
        $publication = $this->getPublication($url, 0);
        if ($publication instanceof Publication){
            $featuredPublications = $this->getFeaturedPublications($publication);
            return ['publication' => $publication, 'featuredPublications' => $featuredPublications ];
        }else{
            return $publication;
        }
    }

    /**
     * @Route("/publications/{category}", name="publications_by_category", defaults={"category"=null})
     * @Template("AppBundle:Publication:publications.html.twig")
     */
    public function publicationsAction(Request $request, $category = null){
        switch ($category){
            case '0':  return $this->redirect($this->generateUrl('publications_by_category',['category' => 'articles']),301); break;
            case '1':  return $this->redirect($this->generateUrl('publications_by_category',['category' => 'news']),301); break;
            case '2':  return $this->redirect($this->generateUrl('publications_by_category',['category' => 'study']),301); break;
            case 'articles': $cat = '0'; break;
            case 'news': $cat = '1'; break;
            case 'study': $cat = '2'; break;
            default: $cat = null; break;
        }
        if ($cat == null){
            $news = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled' => true],['created' => 'DESC']);
        }else{
            $news = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled' => true, 'type' => $cat ],['created' => 'DESC']);
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news,
            $request->query->get('page', 1),
            15
        );
        return ['news' => $pagination, 'category' => $category];
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
        $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findOneBy(['slug' => $url,'enabled' => true]);
        if (!$event){
            $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findOneBy(['id' => $url,'enabled' => true]);
            if ($event === null){
                return $this->createNotFoundException('Данного события не существует или оно было удалено с сайта');
            }
            if ($event->getSlug()){
                return $this->redirect($this->generateUrl('event',['url' => $event->getSlug()]));
            }
        }

        return ['event' => $event];
    }

    /**
     * @Route("/events/{url}", name="events", defaults={"url"=null})
     * @Template("AppBundle:Publication:eventList.html.twig")
     */
    public function eventListAction(Request $request, $url = null)
    {

        if ($url !== null){
            $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findOneBy(['slug' => $url]);
        }else{
            $category = null;
        }
        $filter = $request->query->get('form');
        $start = (isset($filter['start']) ? $filter['start'] : null );
        $end =   (isset($filter['end']) ? $filter['end'] : null );
        $text =   (isset($filter['searchtext']) ? $filter['searchtext'] : null );
        $specialty =   (isset($filter['specialty']) ? $filter['specialty'] : null );
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->filter($category,$start,$end,$text, $specialty);

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
            ->add('start', TextType::class, ['label' => 'c', 'attr' => ['class' => 'form-calendar', 'placeholder' => 'Дата начала'],'required' => false])
            ->add('end', TextType::class, ['label' => 'c', 'attr' => ['class' => 'form-calendar', 'placeholder' => 'Дата окончания'],'required' => false])
            ->add('specialty', EntityType::class, [
                'label' => '',
                'class' => 'AppBundle\Entity\Specialty',
                'required' => false,
                'placeholder' => 'Специальность',

            ])
            ->add('search', TextType::class, ['label' => '', 'required' => false, 'attr' => ['placeholder' => 'Строка поиска']])
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
     * @Route("/news", name="news")
     * @Template("AppBundle:Publication:publications.html.twig")
     */
    public function newsAction(Request $request){
        return $this->redirect($this->generateUrl('publications_by_category'));

//        $news = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled' => true],['created' => 'DESC']);
//        $paginator  = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $news,
//            $request->query->get('page', 1),
//            15
//        );
//        return ['news' => $pagination];
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

    private function getFeaturedPublications($publication){
        $date = $publication->getCreated();
        $featuredPublications = $this->getDoctrine()->getRepository('AppBundle:Publication')->findFeaturedPublications($date, $publication->getId(), $publication->getSpecialties(), $publication->getType(), 5);
        return $featuredPublications;
    }

    private function getPublication($url, $type)
    {
        $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->findOneBy(['slug' => $url,'enabled' => true]);
        if (!$publication){
            $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->findOneBy(['id' => $url,'enabled' => true]);
            if ($publication === null){
                return $this->createNotFoundException('Данной страницы не существует');
            }
        }
        if ($type === $publication->getType()){
            return $publication;
        }else{
            if ($publication->getSlug()){
                if ($publication->getType() == 0){
                    return $this->redirect($this->generateUrl('article',['url' => $publication->getSlug()]));
                }elseif($publication->getType() == 1){
                    return $this->redirect($this->generateUrl('new',['url' => $publication->getSlug()]));
                }elseif($publication->getType() == 2){
                    return $this->redirect($this->generateUrl('study',['url' => $publication->getSlug()]));
                }else{
                    throw $this->createNotFoundException('Данной страницы не существует');
                }
            }else{
                if ($publication->getType() == 0){
                    return $this->redirect($this->generateUrl('article',['url' => $publication->getId()]));
                }elseif($publication->getType() == 1){
                    return $this->redirect($this->generateUrl('new',['url' => $publication->getId()]));
                }elseif($publication->getType() == 2){
                    return $this->redirect($this->generateUrl('study',['url' => $publication->getId()]));
                }else{
                    throw $this->createNotFoundException('Данной страницы не существует');
                }
            }
        }
    }

}