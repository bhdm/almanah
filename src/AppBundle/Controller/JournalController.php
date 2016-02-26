<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JournalController extends Controller
{
    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/journals", name="journals")
     * @Template()
     */
    public function journalsAction()
    {
        $journals = $this->getDoctrine()->getRepository('AppBundle:Journal')->findBy(['enabled' => true],['id' => 'DESC']);
        return ['journals' => $journals];
    }

    /**
     * @param $journalId
     * @return Request
     *
     * @Route("/journal/{journalId}", name="journal")
     * @Template()
     */
    public function journalAction($journalId){

        $journal = $this->getDoctrine()->getRepository('AppBundle:Journal')->findOneBy(['enabled' => true, 'id' => $journalId]);
        return ['journal' => $journal];

    }

    /**
     * @param $journalId
     * @param $postId
     * @return Request
     *
     * @Route("/journal/{journalId}/post/{postId}")
     * @Template()
     */
    public function journalPostAction($journalId, $postId){

        $journal = $this->getDoctrine()->getRepository('AppBundle:Journal')->findOneBy(['enabled' => true, 'id' => $journalId]);
        $post =    $this->getDoctrine()->getRepository('AppBundle:JournalPost')->findOneBy(['enabled' => true, 'id' => $postId]);
        return ['journal' => $journal, 'post' => $post];

    }
}
