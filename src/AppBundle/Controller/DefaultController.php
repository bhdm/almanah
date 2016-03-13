<?php

namespace AppBundle\Controller;

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


}
