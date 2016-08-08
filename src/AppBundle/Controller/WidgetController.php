<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AnalyticOpened;
use donatj\SimpleCalendar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WidgetController extends Controller
{
    /**
     * @Route("/widget-calendar/{year}/{month}", name="widget_calendar", defaults={"year"=null, "month"=null }, options={"exponse" = true})
     * @Template("")
     */
    public function calendarAction(Request $request, $year=null, $month=null){
        if ($year === null){
            $year = (new \DateTime())->format('Y');
        }
        if ($month === null){
            $month = (new \DateTime())->format('m');
        }

        $dateStart = new \DateTime($year.'-'.$month.'-01 00:00:00');
        $dateEnd = new \DateTime($year.'-'.($month+1).'-01 00:00:00');

        $owner = $request->query->get('owner');
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findEvent($owner,$dateStart,$dateEnd);

        return ['events' => $events];
    }

    /**
     * @param Request $request
     * @return Response
     * @Template()
     */
    public function bannerAction(Request $request){

        $banner = $this->getDoctrine()->getRepository('AppBundle:Banner')->findBy(['enabled' => true],[],3);
        $key = array_rand($banner);
        $banner = $banner[$key];
        return ['banner' => $banner];
    }

    /**
     * @param Request $request
     * @return Response
     * @Template()
     */
    public function importantsAction(Request $request){
        $months     = ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        $date       = new \DateTime('now');
        $dateStr    = intval($date->format('d')) . ' ' . $months[intval($date->format('m'))];
        $dateFormat = $date->format('d.m');

        $em        = $this->getDoctrine()->getManager();
        $calendars = $em->getRepository('AppBundle:Calendar')->byDate($dateFormat);

        return [
            'dateFormat' => $dateFormat,
            'dateStr'    => $dateStr,
            'calendars'  => $calendars,
        ];
    }

    /**
     *
     * @Route("/analytics/getimage/{deliveryname}/{id}")
     */
    public function getImagesAction($deliveryname, $id){
        $analytic = new AnalyticOpened();
        $analytic->setTitle($deliveryname);
        $analytic->setEmailId($id);
        $this->getDoctrine()->getManager()->persist($analytic);
        $this->getDoctrine()->getManager()->flush($analytic);

        header('Content-Type: image/png');
        die("\x89\x50\x4e\x47\x0d\x0a\x1a\x0a\x00\x00\x00\x0d\x49\x48\x44\x52\x00\x00\x00\x01\x00\x00\x00\x01\x01\x03\x00\x00\x00\x25\xdb\x56\xca\x00\x00\x00\x03\x50\x4c\x54\x45\x00\x00\x00\xa7\x7a\x3d\xda\x00\x00\x00\x01\x74\x52\x4e\x53\x00\x40\xe6\xd8\x66\x00\x00\x00\x0a\x49\x44\x41\x54\x08\xd7\x63\x60\x00\x00\x00\x02\x00\x01\xe2\x21\xbc\x33\x00\x00\x00\x00\x49\x45\x4e\x44\xae\x42\x60\x82");
    }
}