<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Page;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getRepository(Page::class);
        return $this->render('default/index.html.twig', ['title' => 'Accueil']);
    }


    /**
     * @Route(name="menu")
     */
    public function dislayMenuAction($classNames = '')
    {
        $em = $this->getDoctrine()->getRepository(Page::class);
        $pages = $em->findAll();
        return $this->render('menu.html.twig', ['pages' => $pages, 'classNames' => $classNames]);
    }

    /**
     * @Route("/{slug}", name="page")
     */
    public function showPageAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getRepository(Page::class);
        $page = $em->findOneBy(['slug' => $slug]);
        if ($page == null) {
            throw new NotFoundHttpException('Page non trouvé');
        }
        return $this->render('default/page.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/mail/send", name="send_mail")
     * @Method({"POST"})
     */
    public function sendMailAction(Request $request)
    {
        $sender['firstName'] = $request->get('firstName');
        $sender['lastName'] = $request->get('lastName');
        $sender['mail'] = $request->get('mail');
        $parsedown = new \Parsedown();
        $student = $this->getDoctrine()->getRepository('AppBundle:Student')->findOneBy(['professionalMail' => $request->get('to')]);
        $message = \Swift_Message::newInstance()
            ->setSubject($request->get('subject'))
            ->setFrom($this->get('twig')->getGlobals()['site_name'] . '@' . strtolower($this->get('twig')->getGlobals()['site_name']) . '.com')
            ->setTo($request->get('to'))
            ->setBody($this->renderView('mails/contact.html.twig', ['content' => $parsedown->text($request->get('message')),
                'sender' => $sender,
                'student' => $student]), 'text/html')
            ->addPart($this->renderView('mails/contact.txt.twig', ['content' => $request->get('message'),
                'sender' => $sender,
                'student' => $student]), 'text/plain');
        $this->get('mailer')->send($message);
        $this->addFlash('success', "Le message a bien été envoyé !");
        return $this->redirectToRoute('profile', ['username' => $student->getUsername()]);
    }


}
