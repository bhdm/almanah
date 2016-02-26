<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RecordBook;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class EducationController
 * @package AppBundle\Controller
 */
class EducationController extends Controller
{
    /**
     * Страница списка курсов
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/courses/{specialty}", name="courses", defaults={"specialty" = null})
     * @Template("")
     */
    public function coursesAction(){
        $page = $this->getDoctrine()->getRepository('AppBundle:Page')->findBy(['slug' => 'courses']);
        $courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findBy(['enabled' => true]);
        return ['page' => $page, 'courses' => $courses];
    }

    /**
     * Информация о курсе
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/course/{id}/info", name="course_info")
     * @Template("")
     */
    public function courseInfoAction($id){
        $course = $this->getDoctrine()->getRepository('AppBundle:Course')->findOneBy(['id' => $id]);
        $recordBook = $this->getDoctrine()->getRepository('AppBundle:RecordBook')->findOneBy(['course' => $course]);
        if ($recordBook === null){
            $statusCourse = 'NEW';
        }else{
            if ($recordBook->getPassed() === null){
                $statusCourse = 'PROGRESS';
            }else{
                $statusCourse = 'PASSED';
            }
        }
        return ['course' => $course, 'statusCourse' => $statusCourse, 'recordBook' => $recordBook];
    }

    /**
     * Зачетная книжка
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/recordbook", name="record_book")
     * @Template("")
     */
    public function recordBookAction()
    {
        $recordbook = $this->getDoctrine()->getRepository('AppBundle:RecordBook')->findBy(['user' => $this->getUser()]);
        return ['recordbook' => $recordbook];
    }


    /**
     * @param Request $request
     * @param $courseId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/enrolment/{courseId}", name="enrolment")
     */
    public function enrolmentAction(Request $request, $courseId){
        $course = $this->getDoctrine()->getRepository('AppBundle:Course')->findOneBy(['id' => $courseId]);
        $session = new Session();
        if ($course !== null && $course->getEnabled() == true){
            $em = $this->getDoctrine()->getManager();
            $recordBook = new RecordBook();
            $recordBook->setCourse($course);
            $recordBook->setUser($this->getUser());
            $firstModule = $this->getDoctrine()->getRepository('AppBundle:CourseModule')->findOneBy(['course' => $course],['sort' => 'ASC','id' => 'ASC']);
            $recordBook->setActiveModule($firstModule);
            $em->persist($recordBook);
            $em->flush($recordBook);
            $session->getFlashBag()->add('notice', 'Вы записались на курс "'.$course->getTitle().'"');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }else{
            throw $this->createNotFoundException('Данный курс закрыт или не существует ');
        }
    }

    /**
     * Переключает на модуль курса, который не выше активного
     * @Route("/passing/{recordBookId}/{moduleId}", name="course_passing_step", requirements={"recordBookId" = "\d+", "moduleId" = "\d+"})
     */
    public function passingStepAction(Request $request, $recordBookId, $moduleId){
        $em = $this->getDoctrine()->getManager();
        $recordBook = $this->getDoctrine()->getRepository('AppBundle:RecordBook')->findOneBy(['id' => $recordBookId, 'user' => $this->getUser()]);
        if ($recordBook === null){
            throw $this->createNotFoundException('
                        Произошла ошибка - Данная запись в вашей записной книжке не найдена.
                        Если эта ошибка произошла при прохождении курса - обратить, пожалуйста, к администратору');
        }
        $module = $this->getDoctrine()->getRepository('AppBundle:CourseModule')->stepModule($recordBook->getCourse(), $recordBook->getActiveModule(), $moduleId);
        if ($module !== null){
            return $this->render('AppBundle:Education:showModule.html.twig', ['recordBook' => $recordBook, 'module' => $module ]);
        }else{
            $module = $recordBook->getActiveModule();
            return $this->render('AppBundle:Education:showModule.html.twig', ['recordBook' => $recordBook, 'module' => $module ]);
        }
    }

    /**
     * Переключает на следующий модуль курса
     * @Route("/passing/next/{recordBookId}", name="course_passing")
     */
    public function passingAction(Request $request, $recordBookId){
        $em = $this->getDoctrine()->getManager();
        $recordBook = $this->getDoctrine()->getRepository('AppBundle:RecordBook')->findOneBy(['id' => $recordBookId, 'user' => $this->getUser()]);
        $module = $recordBook->getActiveModule();
        if ($recordBook === null){
            throw $this->createNotFoundException('
                        Произошла ошибка - Данная запись в вашей записной книжке не найдена.
                        Если эта ошибка произошла при прохождении курса - обратить, пожалуйста, к администратору1');
        }

        if ($request->getMethod() === 'POST'){

            if ($request->request->get('test') == 1){
                $questions = $this->getDoctrine()->getRepository('AppBundle:CourseModuleQuestion')->findBy(['module' => $module]);
                $answers = $request->request->get('answer');
                $true = 0;
                foreach ( $questions as $q ){
                    if (isset($answers[$q->getId()])){
                        foreach($q->getAnswers() as $a){
                            if ($a->getCorrect()){
                                if ($answers[$q->getId()] == $a->getId()){
                                    $true ++;
                                }
                            }
                        }
                    }
                }
//                dump($true);
//                dump($questions);
//                exit;
                $result = ceil(($true * 100) / count($questions));


//                dump($result);
//                exit;

//                $result = 50;
                $recordBook->setPercent($result);
                $em->flush($recordBook);

            }

            # Находим следующий модуль курса
            $nextModule = $this->getDoctrine()->getRepository('AppBundle:CourseModule')->nextModule($recordBook->getCourse(), $recordBook->getActiveModule());
            #todo Доделать прохождение теста
            # Если null - то курс пройден
            if ($nextModule === null){
                $recordBook->setPassed((new \DateTime()));
                if ($recordBook->getPercent() == 0){
                    $recordBook->setPercent(100);
                }
                $em->flush($recordBook);
                $em->refresh($recordBook);
                return $this->render('AppBundle:Education:coursePassed.html.twig', ['recordBook' => $recordBook]);
                # Иначе переключаем на новый модуль
            }else{
                $recordBook->setActiveModule($nextModule);
                $em->flush($recordBook);
                $em->refresh($recordBook);
                $module = $recordBook->getActiveModule();
                return $this->render('AppBundle:Education:showModule.html.twig', ['recordBook' => $recordBook, 'module' => $recordBook->getActiveModule() ]);
            }
        }else{
            return $this->render('AppBundle:Education:showModule.html.twig', ['recordBook' => $recordBook, 'module' => $recordBook->getActiveModule() ]);
        }

    }

}
