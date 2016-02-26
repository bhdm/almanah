<?php
namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Faq;
use AppBundle\Form\FaqType;

/**
 * Class FaqController
 * @package AdminBundle\Controller
 * @Route("/admin")
 */
class TestStatisticController extends Controller{
    const ENTITY_NAME = 'TestStatistic';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/test-statistic", name="admin_teststatistic_list")
     * @Template()
     */
    public function listAction(){
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('page', 1),
            20
        );

        $sum = 0;
        foreach ($items as $val){
            $sum += $val->getBalls();
        }
        if ( $items != null ){
            $avg = $sum / count($items);
        }else{
            $avg = 0;
        }
        return array('pagination' => $pagination, 'avg' => $avg, 'count' => count($items) );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/stats/list", name="admin_stats_list")
     * @Template()
     */
    public function statsAction(){
        #Скачивания
        $doc = $this->getDoctrine()->getRepository('AppBundle:Stats')->stats('doc');
        #Просмотры карты
        $map = $this->getDoctrine()->getRepository('AppBundle:Stats')->stats('map');
        #Прохождение теста
        $test = $this->getDoctrine()->getRepository('AppBundle:Stats')->stats('test');
        #Прохождение нового теста
        $test2 = $this->getDoctrine()->getRepository('AppBundle:Stats')->stats('test_new');
        return array(
            'test' => $test,
            'test2' => $test2,
            'map' => $map,
            'doc' => $doc
        );
    }


    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/test-statistic/remove/{id}", name="admin_teststatistic_remove")
     */
    public function removeAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        if ($item){
            $em->remove($item);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
}