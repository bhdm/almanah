<?php
namespace AdminBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CourseModule;
use AppBundle\Form\CourseModuleType;

/**
 * Class CourseModuleController
 * @package AdminBundle\Controller
 * @Route("/admin/course/{courseId}/module")
 */
class CourseModuleController extends Controller{
        const ENTITY_NAME = 'CourseModule';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/", name="admin_course_module_list")
     * @Template()
     */
    public function listAction(Request $request, $courseId){
        $course = $this->getDoctrine()->getRepository('AppBundle:Course')->findOneBy(['id' => $courseId]);
        $modules = $course->getModules();

        return array('modules' => $modules,'course' => $course);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/add", name="admin_course_module_add")
     * @Template()
     */
    public function addAction(Request $request, $courseId){
        $course = $this->getDoctrine()->getRepository('AppBundle:Course')->findOneBy(['id' => $courseId]);
        $em = $this->getDoctrine()->getManager();
        $item = new CourseModule();
        $form = $this->createForm(CourseModuleType::class, $item);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $file = $item->getFile();
                if (!empty($file)){
                    $filename = time(). '.'.$file->guessExtension();
                    $file->move(
                        __DIR__.'/../../../web/upload/courseModule/',
                        $filename
                    );
                    $item->setFile(['path' => '/upload/courseModule/'.$filename ]);
                }

                $item->setCourse($course);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
//                return $this->redirect($this->generateUrl('admin_course_module_list'));
            }
        }
        return array('form' => $form->createView(), 'course' => $course);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="admin_course_module_edit")
     * @Template()
     */
    public function editAction(Request $request, $courseId, $id){
        $course = $this->getDoctrine()->getRepository('AppBundle:Course')->findOneBy(['id' => $courseId]);
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(CourseModuleType::class, $item);
        $form->add('submit', SubmitType::class, ['label' => 'Сохранить', 'attr' => ['class' => 'btn-primary']]);
        $formData = $form->handleRequest($request);

        $olfFile = $item->getFile();


        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $file = $item->getFile();
                if ($file == null){
                    $item->setFile($olfFile);
                }else{
                    $filename = time(). '.'.$file->guessExtension();
                    $file->move(
                        __DIR__.'/../../../web/upload/courseModule',
                        $filename
                    );
                    $item->setFile(['path' => '/upload/courseModule/'.$filename ]);
                }

                $em->flush($item);
                $em->refresh($item);
//                return $this->redirect($this->generateUrl('admin_course_module_list'));
            }
        }
        return array('form' => $form->createView(), 'item' => $item,'course' => $course);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="admin_course_module_remove")
     */
    public function removeAction(Request $request, $courseId, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        if ($item){
            $em->remove($item);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
}