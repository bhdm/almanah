<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PublicationController extends Controller
{
    /**
     * @Route("publications/{url}", name="publications")
     * @Template("AppBundle:Publication:publication.html.twig")
     */
    public function indexAction(Request $request, $url)
    {
        $publication = $this->getDoctrine()->getRepository('AppBundle:Publication')->findOneBy(['id' => $url,'enabled' => true]);
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
        return ['event' => $event];
    }

    /**
     * @Route("events/{url}", name="events")
     * @Template("AppBundle:Publication:eventList.html.twig")
     */
    public function eventListAction(Request $request, $url)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findOneBy(['slug' => $url]);
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->searchEvents($category, $request->query->get('form'));
//        $specialt/ies = $this->getDoctrine()->getRepository('AppBundle:Specialty')->findAll();

        $form = $this->createFormBuilder()
            ->add('start', TextType::class, ['label' => 'c', 'attr' => ['class' => 'form-calendar'],'required' => false])
            ->add('end', TextType::class, ['label' => 'c', 'attr' => ['class' => 'form-calendar'],'required' => false])
            ->add('specialty', EntityType::class, [
                'label' => '',
                'class' => 'AppBundle\Entity\Specialty',
                'required' => false
            ])
            ->add('search', TextType::class, ['label' => '', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Отправить', 'attr' => ['class' => 'btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        return ['events' => $events, 'category' => $category, 'form' => $form->createView()];
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
    public function newsAction(){
        $news = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled' => true],['created' => 'DESC']);

        return ['news' => $news];
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

}