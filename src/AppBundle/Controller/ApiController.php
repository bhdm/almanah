<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/api/v1/getEvents", name="api_get_events", options={"expose"=true})
     */
    public function getEventsAction(Request $request)
    {
        $date = $request->request->get('date');
        if ($date === null){
            $date = 'now';
        }
        $date = new \DateTime($date);

        $owner = $request->query->get('owner');
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findEvent($owner, $date,$date,[], true);

        return new JsonResponse(['events' => $events]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/api/v1/getEventsMonth", name="api_get_events_month", options={"expose"=true})
     */
    public function getEventsMonthAction(Request $request)
    {
        $year = $request->request->get('year');
        if ($year == null){
            $year = (new \DateTime())->format('Y');
        }
        $month = $request->request->get('month');
        if ($month == null){
            $month = (new \DateTime())->format('m');
        }

        $start = new \DateTime($year.'-'.$month.'-1');
        $end = new \DateTime($year.'-'.$month.'-1');
        $end->modify('+1 month');
        $owner = $request->query->get('owner');
        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findEvent($owner,$start,$end,[]);

        $dates = [];
        foreach ($events as $e){

            for (
                $tmpDate = $e->getStart() ;
                $tmpDate <= $e->getEnd() ;
                $tmpDate->modify('+1 day')
            ){
                $dates[$tmpDate->format('d.m.Y')] = true;
                if ($e->getEnd() == null){
                    break;
                }
            }
        }

        return new JsonResponse(['dates' => $dates]);
    }
}
