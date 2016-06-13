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
        $importants =  $this->getDoctrine()->getRepository('AppBundle:Calendar')->findBy(['enabled' => true],['id' => 'DESC'], 3);
        $carusels =  $this->getDoctrine()->getRepository('AppBundle:Event')->findBy(['enabled' => true,'main' => true],['id' => 'DESC'], 4);
        return [
            'publications' => $publications,
            'events' => $events,
            'importants' => $importants,
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
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findBy(['enabled'=> true],[], 5);

        return $this->render('AppBundle:Widget:upcomingEvents.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/feedback", name="feedback")
     * @Template()
     */
    public function feedbackAction(Request $request){
        return [];
    }
}
