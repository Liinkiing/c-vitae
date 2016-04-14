<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends Controller
{
    /**
     * @Route("/students/", name="students_list")
     */
    public function indexAction(Request $request)
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findByAlphabeticalOrder();
        return $this->render('student/index.html.twig', ['students' => $students]);
    }

    /**
     * @Route("/profile/me", name="my_profile")
     * @Method({"GET", "POST"})
     */
    public function showMyProfileAction(Request $request){
        $currentStudent = $this->get('security.token_storage')->getToken()->getUser();
        if($request->getMethod() == 'GET'){

            if($currentStudent != "anon."){
                return $this->render('student/my_profile.html.twig', ['title' => 'Mon profil', 'subtitle' => '', 'isFullscreen' => true]);
            }
            else {
                $this->addFlash('danger', "Vous devez être connecté avant d'accéder à cette page !");
                return $this->redirectToRoute('login');
            }
        } else {
            $currentPassword = $request->get('userCurrentPassword');
            if(!$this->get('security.password_encoder')->isPasswordValid($currentStudent, $currentPassword)) {
                $this->addFlash('danger', "Mauvais mot de passe !");
                return $this->redirectToRoute('my_profile');
            }
            $newPassword = ($request->get('userPasswordFirst') == $request->get('userPasswordSecond')) ? $request->get('userPasswordFirst') : null;
            if($newPassword == null){
                $this->addFlash('danger', "Les mots de passe doivent correspondre !");
                return $this->redirectToRoute('my_profile');
            }
            $currentStudent->setPlainPassword($newPassword);
            $hashedPassword = $this->get('security.password_encoder')->encodePassword($currentStudent, $currentStudent->getPlainPassword());
            $currentStudent->setPassword($hashedPassword);
            $this->getDoctrine()->getManager()->persist($currentStudent);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Vos informations ont bien été modifiées !");
            return $this->redirectToRoute('my_profile');
        }
    }


    /**
     * @Route("/profile/{username}", name="profile")
     */
    public function showProfileAction(Student $student){
        if(!$student){
            throw $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
        }
        return $this->render('student/profile.html.twig', ['user' => $student, 'title' => $student->getFirstName() . ' ' . $student->getLastName(), 'subtitle' => 'Son profil', 'isFullscreen' => true]);
    }

}
