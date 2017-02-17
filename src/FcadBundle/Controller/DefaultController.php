<?php

namespace FcadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FcadBundle\Entity\Feedback;

class DefaultController extends Controller
{
    
    /**
     * @Route("/",name="home")
     */
    public function indexAction(Request $request)
    {
        $news = $this->getDoctrine()->getRepository('FcadBundle:News')
                ->findBy(array(),array('id' => 'DESC'));
        $ads = $this->getDoctrine()->getRepository('FcadBundle:Ads')
                ->findBy(array(),array('id' => 'DESC'));
        if($request->getMethod()=="POST")
        {
            $feedBack = new Feedback();
            $sendername = $request->get('sendername');
            $sendermail = $request->get('sendermail');
            $text = $request->get('text');
             $message = \Swift_Message::newInstance()
                        ->setSubject('Тестовая система обращений')
                        ->setFrom('stelex98@mail.ru')
                        ->setTo($sendermail)
                        ->setBody(
                        '<h3>Здравствуйте!</h3> Ваше электронное обращение на сайте '
                                . 'успешно зарегистрировано.', 'text/html'
                );
                $this->get('mailer')->send($message);
            $feedBack->setSenderemail($sendermail);
            $feedBack->setSendername($sendername);
            $feedBack->setText($text);
            $feedBack->setSent(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
                $em->persist($feedBack);
                $em->flush();
        }
        return $this->render('FcadBundle:Default:layout.html.twig',array(''
            . 'news' => $news,''
            . 'ads' => $ads));
    }
    
    
    /**
     * @Route("/news", name="news")
     */
    public function newsAction()
    {
        $news = $this->getDoctrine()->getRepository('FcadBundle:News')
                ->findBy(array(),array('id' => 'DESC'));
        return $this->render('FcadBundle:Default:news.html.twig',array(''
            . 'news' => $news));
    }
    
    /**
     * @Route("/ads", name="ads")
     */
    public function adsAction()
    {
        $ads = $this->getDoctrine()->getRepository('FcadBundle:Ads')
                ->findBy(array(),array('id' => 'DESC'));
        return $this->render('FcadBundle:Default:ads.html.twig',array(''
            . 'ads' => $ads));
    }
    
    /**
     * @Route("/news/{id}", name="fullnews")
     */
    public function fullNews($id)
    {
        $news = $this->getDoctrine()->getRepository('FcadBundle:News')
                ->findOneBy(array('id' => $id));
        if(!$news)
        {
            return $this->redirectToRoute('home');
        }
        return $this->render('FcadBundle:news:news-item.html.twig',array(''
            . 'newss' => $news));
    }
    
    
    /**** ---------------- Функции блока "студенту" ---------------- *****/
    
    /**
     * @Route("/student/schedule", name="schedule")
     */
    public function scheduleAction()
    {
        return $this->render('FcadBundle:student:schedule.html.twig');
    }
    
    /**
     * @Route("/student/tempstatemnts", name="tempstatemnts")
     */
    public function templateStatementsAction()
    {
        return $this->render('FcadBundle:student:templates.html.twig');
    }
    
    /**
     * @Route("/student/dormitoty", name="dormitoty")
     */
    public function dormitotyAction() 
    {
        return $this->render('FcadBundle:student:dormitory.html.twig');
    }
    
    /**
     * @Route("/student/materialaid", name="materialaid")
     */
    public function materialAidAction() 
    {
        return $this->render('FcadBundle:student:material-aid.html.twig');
    }
    
    /**
     * @Route("/student/studprivs", name="studprivs")
     */
    public function benefitsAction() 
    {
        return $this->render('FcadBundle:student:benefits.html.twig');
    }
    
    /**
     * @Route("/student/scholarships", name="scholarships")
     */
    public function scholarshipAction() 
    {
        return $this->render('FcadBundle:student:scholarships.html.twig');
    }
    
    /**
     * @Route("/student/paideducation", name="paideducation")
     */
    public function tuitionFeesAction() 
    {
        return $this->render('FcadBundle:student:tuition-fees.html.twig');
    }
    
    /**
     * @Route("/student/payreduction", name="payreduction")
     */
    public function decreaseAction() 
    {
        return $this->render('FcadBundle:student:decrease.html.twig');
    }
    
    /**
     * @Route("/student/practice", name="practice")
     */
    public function practiceAction() 
    {
        return $this->render('FcadBundle:student:practice.html.twig');
    }
    
    /**
     * @Route("/student/graduationproject", name="graduationproject")
     */
    public function graduactionProjectAction() 
    {
        return $this->render('FcadBundle:student:graduation-project.html.twig');
    }
    
    /**
     * @Route("/student/distribution", name="distribution")
     */
    public function allocationAction() 
    {
        return $this->render('FcadBundle:student:allocation.html.twig');
    }
    
    /**
     * @Route("/student/militarytraining", name="militarytraining")
     */
    public function militaryTrainingAction() 
    {
        return $this->render('FcadBundle:student:military-training.html.twig');
    }
    
    
    /**** ---------------- Конец функций блока "студенту" ---------------- *****/
    
    
    
    /**** ---------------- Функции блока "о факультете" ---------------- *****/
    
    
    
    /**
     * @Route("/faculty/general", name="general")
     */
    public function generalAction()
    {
        return $this->render('FcadBundle:faculty:general.html.twig');
    }
    
             /** ------ Функции специальностей ----- **/
    
             /**
              * @Route("/enrollee/dapopspecialty",name="dapop")
              */
              public function dapopAction()
              {
                  return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:design-and-production-of-progra'
                          . 'm-controlled-electronic-means.html.twig');
              }
              
              /**
              * @Route("/enrollee/macadspecialty",name="macad")
              */
              public function macadoemAction()
              {
                  return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:modeling-and-computer-aided-design-of-'
                          . 'electronic-means.html.twig');
              }
              
              /**
               * @Route("/enrollee/pmsspecialty", name="pms")
               */
              public function pmsAction()
              {
                  return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:programmable-mobile-systems.html.twig');
              }
              
              /**
               * @Route("/enrollee/seceosspecialty", name="sceos")
               */
              public function sceosAction()
              {
                   return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:software-controlled-electro-optical-sys'
                           . 'tems.html.twig');
              }
              
              
              /**
               * @Route("/enrollee/medelspecialty", name="medel")
               */
              public function medelAction()
              {
                  return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:medical-electronics.html.twig');
              }
              
              /**
               * @Route("/enrollee/epmoitspecialty",name="epmoit")
               */
              public function epmoitAction()
              {
                  return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:engineering-psychological-maintenance-of-in'
                          . 'formation-technology.html.twig');
              }
              
              /**
               * @Route("/enrollee/ecsspecialty",name="ecs")
               */
              public function ecsAction()
              {
                  return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:electronic-security-systems.html.twig');
              }
              
              /**
               * @Route("/enrollee/isatieisspecialty",name="isatieis")
               */
              public function isatieisAction()
              {
                  return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:information-systems-and-technology-in-en'
                          . 'suring-industrial-safety.html.twig');
              }
              
              /**
               * @Route("/enrollee/isatbmspecialty",name="isatbm")
               */
              public function isatbmAction()
              {
                  return $this->render('FcadBundle:enrollee/speci'
                          . 'alties:information-systems-and-technologies-busi'
                          . 'ness-management.html.twig');
              }
              
              /** ------ Конец функций специальностей ----- **/
    
    /**
     * @Route("/faculty/deanery", name="deanery")
     */
    public function deaneryAction()
    {
        return $this->render('FcadBundle:faculty:deanery.html.twig');
    }
    
    /**
     * @Route("/faculty/location", name="locationfaculty")
     */
    public function locationFacultyAction()
    {
        return $this->render('FcadBundle:faculty:location.html.twig');
    }
    
    /**
     * @Route("/faculty/history", name="history")
     */
    public function historyAction()
    {
        return $this->render('FcadBundle:faculty:history.html.twig');
    }
    
    /**
     * @Route("/faculty/student-council", name="studentcouncil")
     */
    public function studentcouncilAction()
    {
        return $this->render('FcadBundle:faculty:student-council.html.twig');
    }
    
    /**
     * @Route("/faculty/departments", name="departments")
     */
    public function departmentsAction()
    {
        return $this->render('FcadBundle:faculty:departments.html.twig');
    }
    
        /*** ------ Функции кафедр ------ ***/
        
        /**
         * @Route("/faculty/departments/engineering-graphics", name="eg")
         */
        public function engineeringGraphicsAction()
        {
            return $this->render('FcadBundle:faculty/departments:engin'
                    . 'eering-graphics.html.twig');
        }
        
        /**
         * @Route("/faculty/departments/engineering-psychology-and-ergonomics",
         *  name="epae")
         */
        public function engineeringPsychologyAction()
        {
            return $this->render('FcadBundle:faculty/departments:engin'
                    . 'eering-psychology-and-ergonomics.html.twig');
        }
        
        /**
         * @Route("/faculty/departments/foreign-languages", name="fl")
         */
        public function foreignLanguagesction()
        {
            return $this->render('FcadBundle:faculty/departments:fore'
                    . 'ign-languages.html.twig');
        }
        
        /**
         * @Route("/faculty/departments/design-info-and-computer-systems", name="dics")
         */
        public function designInfoAndCSAction()
        {
            return $this->render('FcadBundle:faculty/departments:design-info'
                    . 'rmation-and-computer-systems.html.twig');
        }
        
        /**
         * @Route("/faculty/departments/ecology", name="ecology")
         */
        public function ecologyAction()
        {
            return $this->render('FcadBundle:faculty/departments:ecology.html.twig');
        }
        
         /**
         * @Route("/faculty/departments/electronic-engineering-and-technologies", name="eeat")
         */
        public function engineeringAndTechnologyAction()
        {
            return $this->render('FcadBundle:faculty/departments:elect'
                    . 'ronic-engineering-and-technology.html.twig');
        }
        
        /*** ------ Конец функций кафедр ------ ***/
        
        
    /**** ---------------- Конец Функций блока "о факультете" ---------------- *****/
        
        
        
        
    /*** ---------------- Функции блока "Абитуриенту" ---------------- *****/
        
    /**
     * @Route("/enrollee/specialties", name="enrollee")
     */
    public function specialtiesAction()
    {
        return $this->render('FcadBundle:enrollee:specialties.html.twig');
    }
    
    /**
     * @Route("/enrollee/location", name="location")
     */
    public function locationEnrolleeAction()
    {
        return $this->render('FcadBundle:enrollee:location-of-buildings.html.twig');
    }
    
    /**
     * @Route("/enrollee/introductory-campaign", name="introduction")
     */
    public function introductionAction()
    {
        return $this->render('FcadBundle:enrollee:introductory-campaign.html.twig');
    }
    
    /**
     * @Route("/enrollee/passingscores", name="passingscores")
     */
    public function passingScoresAction()
    {
        return $this->render('FcadBundle:enrollee:passing-scores.html.twig');
    }


    /*** ---------------- Конец Функций блока "Абитуриенту" ---------------- *****/
        

}
