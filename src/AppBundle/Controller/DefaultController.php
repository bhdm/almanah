<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $carusels =  $this->getDoctrine()->getRepository('AppBundle:Event')->findImmediate(true,15);
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
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findImmediate(false, 5);

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

            $mail = new \PHPMailer();

            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->SMTPDebug = 0;
            $mail->SMTPSecure = 'tls';
            $mail->CharSet  = 'UTF-8';
            $mail->From     = 'mailer@medalmanah.ru';
            $mail->FromName = 'Альманах медицинских событий';
            $mail->Host     = 'localhost';
            $mail->Username = 'mailer';
            $mail->Password = '3245897';
            $mail->SMTPAuth = false;
            $mail->Port     = 25;
            $mail->Subject  = 'Сообщение из формы обратной связи';
            $mail->Body     = $txt;
            $mail->addAddress('bhd.m@ya.ru');
            $mail->addCustomHeader('X-Postmaster-Msgtype', "firstDelivery");
            $mail->send();

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
     * @Route("/sitemap", defaults={"_format"="xml"})
     * @Template("AppBundle::sitemap.html.twig")
     */
    public function getXmlAction(){
        ini_set('memory_limit', '-1');

        $publications = $this->getDoctrine()->getRepository('AppBundle:Publication')->findBy(['enabled'=>true],['created' => 'ASC']);
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findBy(['enabled'=>true],['created' => 'ASC']);
        $pages = $this->getDoctrine()->getRepository('AppBundle:Page')->findBy([],['id' => 'ASC']);
        $calendar = $this->getDoctrine()->getRepository('AppBundle:Calendar')->findBy([],['id' => 'ASC']);
        $standarts = $this->getDoctrine()->getRepository('AppBundle:Standart')->findBy([],['id' => 'ASC']);

        return [
            'publications'  => $publications,
            'events'        => $events,
            'pages'         => $pages,
            'calendar'      => $calendar,
            'standarts'     => $standarts,
        ];
    }
}
