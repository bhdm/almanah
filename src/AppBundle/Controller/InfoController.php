<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends Controller
{
    /**
     * @Route("/federal-standards/{id}", name = "showstandards")
     */
    public function showStandardsAction($id)
    {
        $standart = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Standart')
            ->findOneById($id);

        return $this->render('AppBundle:Info:standards.html.twig', array(
            'id'          => $id,
            'standart'    => $standart,
        ));
    }

    /**
     * @Route("/federal-standards", name = "federal-standards")
     */
    public function standardsAction()
    {
        $standartCategories = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:StandartCategory')
            ->findAll();

        $standarts = array();
        foreach ($standartCategories as $category) {
            $standarts[$category->getId()]   = array();
            $standarts[$category->getId()][] = $this->getDoctrine()->getRepository('AppBundle:Standart')->findForCategory($category->getId());

        }

        return $this->render('AppBundle:Info:standards.html.twig', array(
            'standartsCategory' => $standartCategories,
            'standarts'         => $standarts,
        ));
    }


    /** @Route("/medical-calendar/{dateFormat}", name="medical_calendar") */
    public function calendarAction($dateFormat = 'now')
    {
        if ($dateFormat != 'now' && !preg_match('/[0-9\.]/', $dateFormat)) {
            throw $this->createNotFoundException();
        }

        $medcalendar = $this->get('app.medcalendar');
        $medcalendar->init($dateFormat);

        $params = array(
            'title'       => 'Календарь медицинских событий',
            'medcalendar' => $medcalendar,
            'dateFormat'  => $dateFormat,
        );

        return $this->render('AppBundle:Calendar:calendar.html.twig', $params);
    }

    /** @Route("/medical-calendar/{dateFormat}/{id}", name="medical_calendar_event") */
    public function eventAction($dateFormat, $id)
    {
        $calendar = $this->getDoctrine()->getRepository('AppBundle:Calendar')->findOneById($id);

        if (!$calendar) {
            throw $this->createNotFoundException();
        }

        $medcalendar = $this->get('app.medcalendar');
        $medcalendar->init($dateFormat);

        $params = array(
            'title'       => $calendar->getTitle() . ' | Календарь медицинских событий',
            'calendar'    => $calendar,
            'medcalendar' => $medcalendar,
            'dateFormat'  => $dateFormat,
            'ogImage'	  => $calendar->getPhoto()['path']
        );

        return $this->render('AppBundle:Calendar:calendar_event.html.twig', $params);
    }

    /**
     * @Route("/ttt")
     */
    public function tttAction(){
        for($i = 4 ; $i <= 515 ; $i ++){
            echo "<url>\r
    <loc>http://medalmanah.ru/now/$i</loc>\r
    <lastmod>2016-07-20T00:00:00+01:00</lastmod>\r
    <priority>1.0</priority>\r
</url>\r\n";
        }
    }
}
