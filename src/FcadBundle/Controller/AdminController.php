<?php

namespace FcadBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FcadBundle\Entity\News;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FcadBundle\Entity\Ads;

/**
 * Description of AdminController
 *
 * @author artur
 */
class AdminController extends Controller {

    /**
     * @Route("/admin/users", name="users")
     */
    public function listUsersAction() {
        $userManager = $this->get('fos_user.user_manager');
        $user = $this->getUser();
        if(!$user->hasRole('ROLE_SUPER_ADMIN'))
        {
            return $this->render('FcadBundle:Admin:error.html.twig',array(''
                . 'error' => 'У вас нет прав!'));
        }
        $users = $userManager->findUsers();
        return $this->render('FcadBundle:Admin:users.html.twig', array(''
                    . 'users' => $users));
    }

    /**
     * @Route("/admin/", name="feedback")
     */
    public function listfeedBackAction() {
        $feedBack = $this->getDoctrine()->getRepository('FcadBundle:Feedback')
                ->findAll();
        if (!$feedBack) {
            $message = "Обращений нет.";
            return $this->render('FcadBundle:Admin:feedback.html.twig',array(''
                . 'message' => $message));
        }
        return $this->render('FcadBundle:Admin:feedback.html.twig', array(''
                    . 'messages' => $feedBack));
    }

    /**
     * @Route("/admin/reply/{id}", name="replymsg")
     */
    public function replyMessage($id, Request $request) {
        $messages = $this->getDoctrine()->getRepository('FcadBundle:Feedback')
                ->findOneBy(array('id' => $id));
        if (!$messages) {
            return $this->redirectToRoute('feedback');
        }
        if ($request->getMethod() == "POST") {
            $sendermail = $request->get('sendermail');
            $textreply = $request->get('text');
            $message = \Swift_Message::newInstance()
                    ->setSubject('Тестовая система обращений')
                    ->setFrom('stelex98@mail.ru')
                    ->setTo($sendermail)
                    ->setBody('<h2>Здравствуйте! '
                            . 'Ваше электронное обращение было '
                            . 'рассмотрено.</h2>'
                            . $textreply, 'text/html');

            $this->get('mailer')->send($message);
            $em = $this->getDoctrine()->getManager();
            $em->remove($messages);
            $em->flush();
            return $this->redirectToRoute('feedback');
        }
        return $this->render('FcadBundle:Admin:reply.html.twig', array(
                    'messages' => $messages, ''
                    . 'id' => $id));
    }

    /**
     * @Route("/admin/deletemsg/{id}", name="deletemsg")
     */
    public function deleteMessageAction($id) {
        $userManager = $this->get('fos_user.user_manager');
        $current_logged_in = $this->getUser();

        $msg = $this->getDoctrine()->getRepository('FcadBundle:Feedback')
                ->findOneBy(array('id' => $id));
        if (!$msg) {
            return $this->redirectToRoute('feedback');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($msg);
        $em->flush();
        return $this->redirectToRoute('feedback');
    }

    /**
     * @Route("/admin/registration", name="registration")
     */
    public function registerAction(Request $request) {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->addRole('ROLE_ADMIN');

        $form = $formFactory->createForm();
        $form->setData($user);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $userManager->updateUser($user);
                $msg = "Пользователь был успешно создан";
                return $this->render('FcadBundle:Admin:register.html.twig', array(''
                            . 'message' => $msg));
            }
        }

        return $this->render('FcadBundle:Admin:register.html.twig', array(''
                    . 'form' => $form->createView()));
    }

    /**
     * @Route("/admin/users/edit/{id}", name ="edit")
     */
    public function editAction(Request $request, $id) {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $current_logged_in = $this->getUser();
        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            $error = "У вас нет прав сделать это";
            return $this->render('FcadBundle:Admin:error.html.twig', array(''
                        . 'error' => $error));
        }
        if (!$user) {
            $error = "Пользователь не найден";
            return $this->render('FcadBundle:Admin:error.html.twig', array(''
                        . 'error' => $error));
        }
        $form = $this->createFormBuilder($user)
                ->add('username', 'text', array('label' => 'Имя пользователя'))
                ->add('email', 'email', array('label' => 'Электронный адрес'))
                ->add('submit', 'submit', array('label' => 'Сохранить'))
                ->getForm();
        $form->setData($user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $userManager->updateUser($user);

                return $this->redirectToRoute('feedback');
            }
        }
        return $this->render('FcadBundle:Admin:edit.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $id));
    }

    /**
     * @Route("/admin/delete/{username}", name="delete")
     */
    public function deleteAction($username) {
        $userManager = $this->get('fos_user.user_manager');
        $current_logged_in = $this->getUser();

        $user = $userManager->findUserByUsername($username);
        if (!$user) {
            $error = "Пользователь не найден";
            return $this->render('FcadBundle:Admin:error.html.twig', array(''
                        . 'error' => $error));
        }
        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            $error = "У вас нет прав сделать это";
            return $this->render('FcadBundle:Admin:error.html.twig', array(''
                        . 'error' => $error));
        }

        if ($user == $current_logged_in) {
            $error = "Вы не можете удалить самого себя";
            return $this->render('FcadBundle:Admin:error.html.twig', array(''
                        . 'error' => $error));
        } else
            $user = $userManager->deleteUser($user);
        return $this->redirectToRoute('feedback');
    }

    /**
     * @Route("/admin/deleteads/{id}", name="deleteads")
     */
    public function deleteAdsAction($id) {

        $ads = $this->getDoctrine()->getRepository('FcadBundle:Ads')
                ->findOneBy(array('id' => $id));
        if (!$ads) {
            $error = "Объявление не найдено";
            return $this->render('FcadBundle:Admin:error.html.twig', array(''
                        . 'error' => $error));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($ads);
        $em->flush();
        return $this->redirectToRoute('feedback');
    }

    /**
     * @Route("/admin/createnews", name="createnews")
     */
    public function addNewsAction(Request $request) {
        $userManager = $this->get('fos_user.user_manager');
        $news = new News();
        $news->setCreatedAt(new \DateTime('now'));
        $news->setAuthor($this->getUser());

        $form = $this->createFormBuilder($news)
                ->add('title', TextType::class, array('label' => 'Название новости'))
                ->add('text', TextareaType::class, array('label' => 'Текст новости'))
                ->add('picture', TextType::class, array('label' => 'URL изображения'))
                ->add('submit', SubmitType::class, array('label' => 'Добавить'))
                ->getForm();
        $form->setData($news);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($news);
                $em->flush();
                $msg = "Новость была успешно добавлена";
                return $this->render('FcadBundle:Admin:createnews.html.twig', array(''
                            . 'message' => $msg));
            }
        }

        return $this->render('FcadBundle:Admin:createnews.html.twig', array(''
                    . 'form' => $form->createView()));
    }

    /**
     * @Route("/admin/createadvs", name="createads")
     */
    public function addAdsAction(Request $request) {
        $userManager = $this->get('fos_user.user_manager');
        $ads = new Ads();
        $ads->setCreatedAt(new \DateTime('now'));

        $form = $this->createFormBuilder($ads)
                ->add('text', TextareaType::class, array('label' => 'Текст объявления'))
                ->add('submit', SubmitType::class, array('label' => 'Добавить'))
                ->getForm();
        $form->setData($ads);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($ads);
                $em->flush();
                $msg = "Объявление было успешно добавлено";
                return $this->render('FcadBundle:Admin:createads.html.twig', array(''
                            . 'message' => $msg));
            }
        }

        return $this->render('FcadBundle:Admin:createads.html.twig', array(''
                    . 'form' => $form->createView()));
    }

    /**
     * @Route("/admin/news", name="listnews")
     */
    public function listNewsAction() {

        $news = $this->getDoctrine()->getRepository('FcadBundle:News')
                ->findAll();
        return $this->render('FcadBundle:Admin:news.html.twig', array(''
                    . 'news' => $news));
    }

    /**
     * @Route("/admin/ads", name="listads")
     */
    public function listAdsAction() {

        $ads = $this->getDoctrine()->getRepository('FcadBundle:Ads')
                ->findAll();
        return $this->render('FcadBundle:Admin:ads.html.twig', array(''
                    . 'ads' => $ads));
    }

    /**
     * @Route("/admin/editads/{id}", name="editads")
     */
    public function editAdsAction($id, Request $request) {

        $current_logged_in = $this->getUser();
        $ads_found = $this->getDoctrine()->getRepository("FcadBundle:Ads")
                ->findOneBy(array('id' => $id));
        if (!$ads_found) {
            $error = "Новость не найдена";
            return $this->render("FcadBundle:Admin:error.html.twig", array(''
                        . 'error' => $error));
        }
        $form = $this->createFormBuilder($ads_found)
                ->add('text', TextareaType::class, array('label' => 'Текст', 'attr' => array('cols' => '50', 'rows' => '6'),))
                ->add('submit', SubmitType::class, array('label' => 'Сохранить'))
                ->getForm();
        $form->setData($ads_found);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $ads_found->setCreatedAt($ads_found->getCreatedAt());
                $em = $this->getDoctrine()->getManager();
                $ads_found->setUpdatedAt(new \DateTime('now'));
                $em->persist($ads_found);
                $em->flush();
                $msg = "Статья была обновлена!";
                $url = "/ads";

                return $this->redirect($url);
            }
        }

        return $this->render('FcadBundle:Admin:adsedit.html.twig', array(''
                    . 'form' => $form->createView(), ''
                    . 'id' => $id));
    }

    /**
     * @Route("/admin/editnews/{id}", name="editnews")
     */
    public function editNewsAction($id, Request $request) {

        $current_logged_in = $this->getUser();
        $news_found = $this->getDoctrine()->getRepository("FcadBundle:News")
                ->findOneBy(array('id' => $id));
        if (!$news_found) {
            $error = "Новость не найдена";
            return $this->render("FcadBundle:Admin:error.html.twig", array(''
                        . 'error' => $error));
        }
        $form = $this->createFormBuilder($news_found)
                ->add('title', TextType::class, array('label' => 'Заголовок'))
                ->add('text', TextareaType::class, array('label' => 'Текст новости', 'attr' => array('cols' => '50', 'rows' => '6'),))
                ->add('picture', TextType::class, array('label' => 'Изображение'))
                ->add('submit', SubmitType::class, array('label' => 'Сохранить'))
                ->getForm();
        $form->setData($news_found);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $news_found->setCreatedAt($news_found->getCreatedAt());
                $em = $this->getDoctrine()->getManager();
                $news_found->setUpdatedAt(new \DateTime('now'));
                $em->persist($news_found);
                $em->flush();
                $msg = "Статья была обновлена!";
                $url = "/news/$id";

                return $this->redirect($url);
            }
        }

        return $this->render('FcadBundle:Admin:newsedit.html.twig', array(''
                    . 'form' => $form->createView(), ''
                    . 'id' => $id));
    }

    /**
     * @Route("/admin/deletenews/{id}", name="delnews")
     */
    public function deleteNewsAction($id) {
        $news = $this->getDoctrine()->getRepository('FcadBundle:News')
                ->findOneBy(array('id' => $id));
        if (!$news) {
            $error = "Но стьне найдена";
            return $this->render('FcadBundle:Admin:error.html.twig', array(''
                        . 'error' => $error));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();
        return $this->redirectToRoute('feedback');
    }

}
