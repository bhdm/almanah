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
            'title'       => "{$standart} | Федеральные стандарты | Evrika.ru - профессиональная социальная сеть для врачей",
            'description' => "Федеральный стандарт {$standart}. Evrika.ru - медицинский информационно-образовательный портал для врачей.",
            'keywords'    => "{$standart} медицинский врач портал сайт образовательный информационный социальная сеть"

        ));
    }

    /**
     * @Route("/federal-standards", name = "standards")
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
}
