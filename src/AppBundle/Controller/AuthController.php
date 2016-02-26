<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\University;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AuthController extends Controller
{
    public function csrfTokenAction(){
        $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate');
        return $this->render('AppBundle:Auth:csrfToken.html.twig', ['csrf_token' => $csrfToken]);
    }

    /**
     * @Route("/get-city", name="get_city", options={"expose" = true})
     */
    public function getCityAction(Request $request){
        $title = $request->query->get('title');
        $cities = $this->getDoctrine()->getRepository('AppBundle:City')->findForAutocomplete($title);
        $us = [];
        foreach ($cities as $city) {
            $us[] = $city['title'];
        }
        return new JsonResponse($us);
    }

    /**
     * @Route("/get-university", name="get_university", options={"expose" = true})
     */
    public function getUniversityAction(Request $request){
        $title = $request->query->get('title');
        $universities = $this->getDoctrine()->getRepository('AppBundle:University')->findForAutocomplete($title);
        $us = [];
        foreach ($universities as $university) {
            $us[] = $university['title'];
        }
        return new JsonResponse($us);
    }

    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

//        if ( $request->getMethod('POST') ){
//            dump($form);
//            exit;
//        }


        if ($request->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();
            //city
            $cityTitle = $user->getCity();

            $city = $this->getDoctrine()->getRepository('AppBundle:City')->findOneByTitle($cityTitle);
            if ($city === null){
                $city = new City();
                $city->setTitle($cityTitle);
                $city->setCountry($user->getCountry());
                $em->persist($city);
                $em->flush($city);
                $em->refresh($city);
            }
            $user->setCity($city);

            //university
            $universityTitle = $user->getUniversity();
            $university = $this->getDoctrine()->getRepository('AppBundle:University')->findOneByTitle($cityTitle);
            if ($university === null){
                $university = new University();
                $university->setTitle($universityTitle);
                $university->setCountry($user->getCountry());
                $em->persist($university);
                $em->flush($university);
                $em->refresh($university);
            }
            $user->setUniversity($university);

            $user->setCertificate([]);

            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
