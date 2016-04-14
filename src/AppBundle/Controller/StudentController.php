<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
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
}
