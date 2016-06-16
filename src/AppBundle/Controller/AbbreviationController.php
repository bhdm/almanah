<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use
	Symfony\Component\HttpFoundation\Request,
	Symfony\Component\HttpFoundation\Response,
	Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AbbreviationController extends Controller
{
    /**
     * @Route("/abbreviations", name="abbreviations")
     * @Template()
     */
	public function abbreviationsAction(Request $request){
        $file = $request->server->get('DOCUMENT_ROOT')
            . '/bundles/app/dictionary/Abbreviations.json';

        return ['abbs' =>json_decode(file_get_contents($file), true)];
    }
}