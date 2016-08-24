<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("AppBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {

        $publications = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled' => true],['created' => 'DESC'], 5);
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findBy(['enabled' => true],['start' => 'DESC'], 5);
        $carusels =  $this->getDoctrine()->getRepository('AppBundle:Event')->findImmediate(true);
        return [
            'publications' => $publications,
            'events' => $events,
            'carusels' => $carusels
        ];

    }

    /**
     * @Route("/generate-menu", name="generate_menu")
     * @Template("AppBundle::menu.html.twig")
     */
    public function generateMenuAction(){
        $menu = $this->getDoctrine()->getRepository('AppBundle:Menu')->findBy(['parent' => null, 'enabled' => true]);

        return ['menu' => $menu];
    }


    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction(Request $request){
        $search = $request->query->get('search');
        $publications = $this->getDoctrine()->getRepository('AppBundle:Publication')->search($search);
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->search($search);
        $courses = $this->getDoctrine()->getRepository('AppBundle:Course')->search($search);
        return [
            'publications' => $publications,
            'events' => $events,
            'courses' => $courses,
            'search' => $search,
        ];
    }

    /**
     * @Route("/partners", name="partners")
     * @Template()
     */
    public function partnersAction(){
        $partners = $this->getDoctrine()->getRepository('AppBundle:Partner')->findBy([],['id' => 'DESC']);
        return ['partners' => $partners];
    }

    public function caruselAction(){
        $carusel = $this->getDoctrine()->getRepository('AppBundle:Slidebar')->findAll();
        return $this->render('@App/Widget/carusel.html.twig',['carusel' => $carusel]);
    }

//    /**
//     * @Route("/csv/{url}", name="csv")
//     */
//    public function csvAction($url){
//        $em = $this->getDoctrine()->getManager();
//        $url = 'https://www.evrika.ru/calendar/rss/2016/'.$url;
//        $xml = simplexml_load_string(file_get_contents($url));
//        foreach ($xml->channel->item as $item){
//            $event = new Event();
//            $event->setTitle($item->title);
//            $event->setAnons(strip_tags($item->description));
//            $event->setAdrs($item->address);
//            $event->setBody($item->description);
//            $event->setStart(new \DateTime($item->dateStart));
//            $event->setStart(new \DateTime($item->dateEnd));
//            $em->persist($event);
//            $em->flush($event);
//        }
//        exit;
//    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/upcoming-events")
     */
    public function getUpcomingEventsAction(){
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findImmediate(5);

        return $this->render('AppBundle:Widget:upcomingEvents.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/feedback", name="feedback")
     * @Template()
     */
    public function feedbackAction(Request $request){
        $post = false;
        if ($request->getMethod() == 'POST'){
            $txt = $request->request->get('name').'<br />';
            $txt.= $request->request->get('email').'<br />';
            $txt.= $request->request->get('phone').'<br />';
            $txt.= $request->request->get('type').'<br />';
            $txt.= $request->request->get('text').'<br />';
            mail('admin@medalmanah.ru','Сообщение с сайта', $txt);
            $post = true;
        }
        return ['post' => $post];
    }

    /**
     * @Route("/redirect", name="redirect")
     */
    public function redirectAction(Request $request){
        $url = $request->query->get('url');
        sleep(2);
        return $this->redirect($url);
    }

    /**
     * @Route("/getXml")
     */
    public function getXmlAction(){
        $publications = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy([],['created' => 'ASC']);
        foreach ($publications as $publication){
            echo "<url>\n";
            echo "<loc>https://medalmanah.ru/news/".($publication->getSlug() ? $publication->getSlug() : $publication->getId())."</loc>\n";
            echo "<lastmod>".$publication->getCreated()->format('Y-m-d H:i:s')."+01:00</lastmod>\n";
            echo "<priority>0.8</priority>\n";
            echo "</url>";
        }

        $publications = $this->getDoctrine()->getRepository('AppBundle:Event')->findBy([],['created' => 'ASC']);
        foreach ($publications as $publication){
            echo "<url>\n";
            echo "<loc>https://medalmanah.ru/event/".$publication->getId()."</loc>\n";
            echo "<lastmod>".$publication->getCreated()->format('Y-m-d H:i:s')."+01:00</lastmod>\n";
            echo "<priority>0.8</priority>\n";
            echo "</url>";
        }

        exit;
    }
}
