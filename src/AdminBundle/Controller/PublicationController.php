<?php
namespace AdminBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Publication;
use AppBundle\Form\PublicationType;
use Endroid\Twitter\Twitter;

/**
 * Class PublicationController
 * @package AdminBundle\Controller
 * @Route("/admin/publication")
 */
class PublicationController extends Controller{
        const ENTITY_NAME = 'Publication';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/", name="admin_publication_list")
     * @Template()
     */
    public function listAction(Request $request){
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->search('', false);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $request->query->get('page', 1),
            20
        );

        return array('pagination' => $pagination);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/add", name="admin_publication_add")
     * @Template()
     */
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $item = new Publication();
        $form = $this->createForm(PublicationType::class, $item);
        $form->add('twitter', CheckboxType::class, array(
            'mapped' => false,
            'required'    => false,
            'label' => 'Сделать пост в twitter',
            'attr' => [
                'checked' => true
            ]
        ));
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();

                if ($request->request->get('thumbail')){
                    $image = new \Imagick();
                    $image->readImageBlob($this->convertBase64Image($request->request->get('thumbail')));
                    $image->setImageFormat('png');
                    $filename = '/upload/publication/'.time().'.'.$image->getImageFormat();
                    $image->writeImage(__DIR__.'/../../../web'.$filename);
                    $item->setPreview(['path' => $filename]);
                }

                $item->setAuthor($this->getUser());
                $em->persist($item);
                $em->flush();
                $em->refresh($item);

//                Тут проверяем нужно ли ретвитить, и если да - делаем пост в твиттер
//                dump($request->request->get('twitter'));
//                exit;
                if ($request->request->get('publication')['twitter'] == 1){
                    $consumerKey = $this->getParameter('twitter_consumer_key');
                    $consumerSecret = $this->getParameter('twitter_consumer_secret');
                    $accessToken = $this->getParameter('twitter_access_token');
                    $accessTokenSecret = $this->getParameter('twitter_access_secret');
                    $url = 'https://medalmanah.ru'.$this->generateUrl('publications',['url' => $item->getSlug()]);
                    $delimetr = " : ";
                    $twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

//                    $media = $twitter->query('media/upload', 'POST', 'json', array(
//                        'media' => $image->getImageBlob(),
//                    ));
//                    dump($media);
//                    exit;
                    $twitter->query('statuses/update', 'POST', 'json', array(
                        'status' => $item->getTitle() . $delimetr . $url,
//                        'entities' => array("urls" => ["url" => $url ])
                    ));
                }

                return $this->redirect($this->generateUrl('admin_publication_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="admin_publication_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(PublicationType::class, $item);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $oldFile = $item->getPreview();

        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();

                if ($request->request->get('thumbail')){
                    $image = new \Imagick();
                    $image->readImageBlob($this->convertBase64Image($request->request->get('thumbail')));
                    $image->setImageFormat('png');
                    $filename = '/upload/publication/'.time().'.'.$image->getImageFormat();
                    $image->writeImage(__DIR__.'/../../../web'.$filename);
                    $item->setPreview(['path' => $filename]);
                }

                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('admin_publication_list'));
            }
        }
        return array('form' => $form->createView(), 'item' => $item);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="admin_publication_remove")
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

    private function convertBase64Image($base64_image_string) {
        $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
        $data= $splited[1];
        return base64_decode($data);
    }

}