<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Event;
use AppBundle\Entity\Publication;
use AppBundle\Form\EventUserType;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     * @route("/api/publications/{page}", name="api_get_publications", defaults={"page" = 1})
     */
    public function getPublicationsJson($page = null){
        $news = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled' => true],['created' => 'DESC']);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news,
            $page,
            15
        );
        $publications = array();
        foreach ($pagination->getItems() as $item){
            $publications[] = array(
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'anons' => $item->getAnons(),
                'preview' => (isset($item->getPreview()['path']) ? $item->getPreview()['path'] : ''),
                'created' => $item->getCreated()->format('d.m.Y'),
                'slug' => $item->getSlug(),
                'category' => ( $item->getCategory() != null  ? $item->getCategory()->getTitle() : null ),
                'type' => $item->getType(),
                'like' => $item->getLike(),
                'dislike' => $item->getDislike(),
                'commentCount' => count($item->getComments()),
                'rating' => $item->getShow()*3 + $item->getLike() * 5 + $item->getDislike(),

            );
        }

        return new JsonResponse(['publications' => $publications]);
    }

    /**
     * @route("/api/publication/{id}", name="api_get_publication")
     */
    public function getPublicationJson($id){
        $item = $this->getDoctrine()->getRepository('AppBundle:Publication')->find($id);
        $publication = array(
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'anons' => $item->getAnons(),
            'preview' => (isset($item->getPreview()['path']) ? $item->getPreview()['path'] : ''),
            'created' => $item->getCreated()->format('d.m.Y'),
            'slug' => $item->getSlug(),
            'category' => ( $item->getCategory() != null  ? $item->getCategory()->getTitle() : null ),
            'type' => $item->getType(),
            'like' => $item->getLike(),
            'dislike' => $item->getDislike(),
            'commentCount' => count($item->getComments()),
            'rating' => $item->getShow()*3 + $item->getLike() * 5 + $item->getDislike(),
        );
        return new JsonResponse(['publication' => $publication]);
    }

    /**
     * @route("/api/publication/{id}/comment", name="api_get_publication_comment")
     */
    public function getCommentJson($id){
        $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->find($id);
        $comments = $this->getDoctrine()->getRepository('AppBundle:Comment')->findBy(['publication' => $publication], ['id' => 'ASC']);

        $commentsJson = array();
        foreach ($comments as $item){
            $commentsJson[] = array(
                'id' => $item->getId(),
                'body' => $item->getBody(),
                'author' => $item->getOwner(),
                'created' => $item->getCreated()->format('d.m.Y H:i')
            );
        }

        return new JsonResponse(['comments' => $commentsJson]);
    }

    /**
     * @route("/api/specialty", name="api_get_specialty")
     */
    public function getSpecialtyJson(){
        $specialties = $this->getDoctrine()->getRepository('AppBundle:Specialty')->findBy([],['title' => 'ASC']);
        $specialtiesJson = array();
        foreach ($specialties as $item){
            $specialtiesJson[] = array(
                'id' => $item->getId(),
                'title' => $item->getTitle(),
            );
        }

        return new JsonResponse(['specialties' => $specialtiesJson]);
    }



    /**
     * @route("/api/event/{id}", name="api_get_event")
     */
    public function getEventJson($id){
        $item = $this->getDoctrine()->getRepository('AppBundle:Event')->find($id);
        $event = array(
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'anons' => $item->getAnons(),
            'preview' => (isset($item->getPreview()['path']) ? $item->getPreview()['path'] : ''),
            'start' => $item->getStart()->format('d.m.Y'),
            'end' => $item->getEnd()->format('d.m.Y'),
            'slug' => $item->getSlug(),
            'category' => ( $item->getCategory() != null  ? $item->getCategory()->getTitle() : null ),
        );
        return new JsonResponse(['event' => $event]);
    }




    /**
     * @route("/api/events/{page}", name="api_get_events", defaults={"page" = 1})
     */
    public function getEventsJson(Request $request, $page = null){
        $category = null;
        $filter = $request->query->get('form');
        $start = (isset($filter['start']) ? $filter['start'] : null );
        $end =   (isset($filter['end']) ? $filter['end'] : null );
        $text =   (isset($filter['searchtext']) ? $filter['searchtext'] : null );
        $specialty =   (isset($filter['specialty']) ? $filter['specialty'] : null );
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->filter($category,$start,$end,$text, $specialty);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events,
            $page,
            15
        );
        $events = array();
        foreach ($pagination->getItems() as $item){
            $events[] = array(
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'anons' => $item->getAnons(),
                'preview' => (isset($item->getPreview()['path']) ? $item->getPreview()['path'] : ''),
                'start' => $item->getStart()->format('d.m.Y'),
                'end' => $item->getEnd()->format('d.m.Y'),
                'slug' => $item->getSlug(),
                'category' => ( $item->getCategory() != null  ? $item->getCategory()->getTitle() : null ),
            );
        }

        return new JsonResponse(['events' => $events]);
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
    public function newAction(Request $request, $url){
        $publication = $this->getPublication($url, 1);

        if ($publication instanceof Publication){
            $featuredPublications = $this->getFeaturedPublications($publication);

            $session = $request->getSession();
            if ($session->get('show-'.$publication->getId()) === null){
                $session->set('show-'.$publication->getId(), true);
                $session->save();
                $publication->setShow($publication->getShow()+1);
                $this->getDoctrine()->getManager()->flush($publication);
                $this->getDoctrine()->getManager()->refresh($publication);
            }

            $poll = $publication->getPoll();
            return ['publication' => $publication, 'featuredPublications' => $featuredPublications , 'poll' => $poll];
        }else{
            return $publication;
        }
    }

    /**
     * @Route("/publications/study/{url}", name="study")
     * @Template("AppBundle:Publication:publication.html.twig")
     */
    public function studyAction(Request $request, $url){
        $publication = $this->getPublication($url, 2);
        if ($publication instanceof Publication){
            $featuredPublications = $this->getFeaturedPublications($publication);

            $session = $request->getSession();
            if ($session->get('show-'.$publication->getId()) === null){
                $session->set('show-'.$publication->getId(), true);
                $session->save();
                $publication->setShow($publication->getShow()+1);
                $this->getDoctrine()->getManager()->flush($publication);
                $this->getDoctrine()->getManager()->refresh($publication);
            }

            $poll = $publication->getPoll();
            return ['publication' => $publication, 'featuredPublications' => $featuredPublications , 'poll' => $poll];
        }else{
            return $publication;
        }
    }

    /**
     * @Route("/publications/articles/{url}", name="article")
     * @Template("AppBundle:Publication:publication.html.twig")
     */
    public function articleAction(Request $request, $url){
        $publication = $this->getPublication($url, 0);
        if ($publication instanceof Publication){
            $featuredPublications = $this->getFeaturedPublications($publication);

            $session = $request->getSession();
            if ($session->get('show-'.$publication->getId()) === null){
                $session->set('show-'.$publication->getId(), true);
                $session->save();
                $publication->setShow($publication->getShow()+1);
                $this->getDoctrine()->getManager()->flush($publication);
                $this->getDoctrine()->getManager()->refresh($publication);
            }

            $poll = $publication->getPoll();
            return ['publication' => $publication, 'featuredPublications' => $featuredPublications , 'poll' => $poll];
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
     * @Route("event/{url}/{pageUrl}", name="event", options={"expose"=true}, defaults={"pageUrl"=null})
     * @Template("AppBundle:Publication:event.html.twig")
     */
    public function eventAction(Request $request, $url, $pageUrl = null)
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
        if ($pageUrl != null){
            $page = $this->getDoctrine()->getRepository('AppBundle:EventPage')->findOneBy(['slug'=> $pageUrl, 'event' => $event]);
        }else{
            $page = null;
        }
        $pages = $this->getDoctrine()->getRepository('AppBundle:EventPage')->findBy(['type' => 0]);
        $modals = $this->getDoctrine()->getRepository('AppBundle:EventPage')->findBy(['type' => 2]);


        return [
            'event' => $event,
            'page' => $page,
            'pages' => $pages,
            'modals' => $modals,
        ];
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
        $data = array('city' => null, 'period' => null, 'search' => null, 'specialty' => null);
        $form = $this->createFormBuilder($data)
            ->add('period', TextType::class, ['label' => 'c', 'attr' => ['class' => 'form-calendar', 'placeholder' => 'Дата'],'required' => false])
            ->add('specialty', EntityType::class, [
                'label' => '',
                'class' => 'AppBundle\Entity\Specialty',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC');
                },
                'required' => false,
                'placeholder' => 'Специальность',

            ])
            ->add('city', TextType::class, ['label' => 'Город', 'required'=> false, 'attr' => ['class' => 'format', 'placeholder' => 'Город']])
            ->add('search', TextType::class, ['label' => '', 'required' => false, 'attr' => ['placeholder' => 'Строка поиска']])
            ->add('submit', SubmitType::class, ['label' => 'Поиск', 'attr' => ['class' => 'btn-primary']])
            ->setMethod('GET')
            ->getForm();

        $form->handleRequest($request);

        $data = $form->getData();



        if ($data['period'] != null){
            $start = $this->redate(explode(' - ',$data['period'])[0]);
            $end =   $this->redate(explode(' - ',$data['period'])[1]);
        }else{
            $start = null;
            $end = null;
        }
        $text =   $data['search'];
        $specialty =   $data['specialty'];
        $city =   $data['city'];
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->filter($category,$start,$end,$text, $specialty, $city);

        if ($category == null){
            $category = "Мероприятия";
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events,
            $request->query->get('page', 1),
            15
        );

        return ['events' => $pagination, 'category' => $category, 'form' => $form->createView()];
    }

    /**
     * @todo Пока что ID потом переделать на title
     * @Route("/events/city/{cityId}", name="eventsOfCity")
     * @Template("AppBundle:Publication:eventListOfCity.html.twig")
     */
    public function eventListOfCityAction(Request $request, $cityId)
    {

        $city = $this->getDoctrine()->getRepository('AppBundle:City')->find($cityId);
        $data = array('city' => $city->getTitle(), 'period' => null, 'search' => null, 'specialty' => null);
        $form = $this->createFormBuilder($data)
            ->add('period', TextType::class, ['label' => 'c', 'attr' => ['class' => 'form-calendar', 'placeholder' => 'Дата'],'required' => false])
            ->add('specialty', EntityType::class, [
                'label' => '',
                'class' => 'AppBundle\Entity\Specialty',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC');
                },
                'required' => false,
                'placeholder' => 'Специальность',

            ])
            ->add('city', TextType::class, ['label' => 'Город', 'required'=> false, 'attr' => ['class' => 'format', 'placeholder' => 'Город']])
            ->add('search', TextType::class, ['label' => '', 'required' => false, 'attr' => ['placeholder' => 'Строка поиска']])
            ->add('submit', SubmitType::class, ['label' => 'Поиск', 'attr' => ['class' => 'btn-primary']])
            ->setMethod('GET')
            ->setAction($this->generateUrl('events'))
            ->getForm();

        $form->handleRequest($request);

        $data = $form->getData();


        if ($data['period'] != null){
            $start = $this->redate(explode(' - ',$data['period'])[0]);
            $end =   $this->redate(explode(' - ',$data['period'])[1]);
        }else{
            $start = null;
            $end = null;
        }
        $text =   $data['search'];
        $city =   $data['city'];
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->filter(null,$start,$end,$text, null, $city);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events,
            $request->query->get('page', 1),
            15
        );

        return ['events' => $pagination, 'city' => $city, 'form' => $form->createView()];
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

    /**
     * @Route("/set-like/{id}", name="set_like", options={"expose"=true})
     */
    public function likeAction(Request $request, $id){
        $session = $request->getSession();

        $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->find($id);
        if ($publication){
            if ($session->get('like-'.$publication->getId()) === null){
                $publication->setLike($publication->getLike()+1);
                $session->set('like-'.$publication->getId(), 1);
            }elseif($session->get('like-'.$publication->getId()) === -1){
                $publication->setLike($publication->getLike()+1);
                $publication->setDislike($publication->getDislike()-1);
                $session->set('like-'.$publication->getId(), 1);
            }
            $session->save();
            $this->getDoctrine()->getManager()->flush($publication);


            return new JsonResponse(['like' => $publication->getLike(), 'dislike' => $publication->getDislike()]);
        }
        return new Response('error');
    }

    /**
     * @Route("/set-dislike/{id}", name="set_dislike", options={"expose"=true})
     */
    public function dislikeAction(Request $request, $id){
        $session = $request->getSession();
        $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->find($id);
        if ($publication){
            if ($session->get('like-'.$publication->getId()) === null){
                $publication->setLike($publication->getDislike()-1);
                $session->set('like-'.$publication->getId(), -1);
            }elseif($session->get('like-'.$publication->getId()) === 1){
                $publication->setLike($publication->getLike()-1);
                $publication->setDislike($publication->getDislike()+1);
                $session->set('like-'.$publication->getId(), -1);
            }
            $session->save();
            $this->getDoctrine()->getManager()->flush($publication);
            return new JsonResponse(['like' => $publication->getLike(), 'dislike' => $publication->getDislike()]);
        }
        return new Response('error');
    }

    /**
     * @Route("/events-map", name="events_map")
     * @Template("@App/Publication/eventsmap.html.twig")
     */
    public function mapEventAction(){
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findCountOfCity();
        $events = json_encode(['events' => $events]);
        return ['events' => $events];
    }

    /**
     * @Route("/publication/answer/poll/{id}", name="publication_poll")
     */
    public function answerPublicationPollAction(Request $request, $id){
        $session = $request->getSession();
        $poll = $this->getDoctrine()->getRepository('AppBundle:Poll')->find($id);
        $answer = $request->request->get('poll')[$id];
        $question = $this->getDoctrine()->getRepository('AppBundle:PollQuestion')->find($answer);
        $question->setCount($question->getCount()+1);
        $this->getDoctrine()->getManager()->flush($question);

        $session->set('poll-'.$poll->getId(), true);
        $session->getFlashBag()->add('notice', 'Ваш ответ сохранен, спасибо за ваше мнение');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    private function redate($str){
        $str = explode('.',$str);
        return $str[2].'-'.$str[1].'-'.$str[0].' 00:00:00';

    }
}