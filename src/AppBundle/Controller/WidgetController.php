<?php

namespace AppBundle\Controller;

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
}