<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends Controller
{
    /**
     * @Route("/calculators", name="calculators")
     * @Template()
     */
    public function listAction()
    {
        return [];
    }

    /**
     * @Route("/calculators/ocenka-clirensa-creatinina", name="ocenka_clirensa_creatinina")
     * @Template()
     */
    public function ocenkaClirensaCreatininaAction()
    {
        return [];
    }

    /**
     * @Route("/calculators/index-massy-tela", name="index_massy_tela")
     * @Template()
     */
    public function indexMassyTelaAction()
    {
        return [];
    }

    /**
     * @Route("/calculators/shkala-score", name="shkala_score")
     * @Template()
     */
    public function shkalaScoreAction()
    {
        return [];
    }
}
