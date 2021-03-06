<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Page;
use AppBundle\Entity\Post;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Entity\Role;
use AppBundle\Entity\Student;
use AppBundle\Entity\User;
use DateTime;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends Controller
{

    

    /**
     * @Route("/admin/", name="admin_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        return $this->render('admin/index.html.twig', ['test']);
    }


    /**
     * @Route("/admin/homepage/edit", name="homepage_edit")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function editHomepageAction(Request $request){
        $homepage = $this->getDoctrine()->getRepository('AppBundle:Homepage')->find(1);
        if($request->getMethod() == 'GET'){
            return $this->render('admin/homepage_edit.html.twig', ['homepage' => $homepage]);
        } else {
            $mmiDetail = $request->get('mmi_detail');
            $parsedown = new \Parsedown();
            $mmiDetail['content'] = $parsedown->text($mmiDetail['content']);
            $homepage->setTitle($request->get('title'))
                ->setSubtitle($request->get('subtitle'))
                ->setFeature($request->get('feature'))
                ->setMmiDetail($mmiDetail);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Les modifications ont bien été enregistrées. <a href="/">Voir la page</a>');
            return $this->redirectToRoute('homepage_edit');

        }

    }


    /**
     * @Route("/admin/students/", name="user_list")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listUserAction()
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findByAlphabeticalOrder();
        return $this->render('student/student_list.html.twig', ['students' => $students]);
    }



    /**
     * @Route("/admin/student/edit/{username}", name="edit_profile")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editProfileAction(Request $request, Student $student)
    {
        if (!$student) {
            throw $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
        }
        if ($request->getMethod() == "GET") {
            $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
            return $this->render('student/student_edit.html.twig', ['student' => $student, 'availableRoles' => $roles]);
        } else {
            $canBeAdmashallah = ($student->getUsername() == "ojbara") ? true : false;
            $rolesId = $request->get('roles');
            foreach ($student->getRolesObject() as $role) {
                $student->removeRole($role);
            }
            if($this->get('security.token_storage')->getToken()->getUsername() != 'ojbara' && $student->getUsername() == 'ojbara'){
                $this->addFlash('danger', "Vous ne pouvez pas modifier les permissions d'Omar. Seul Omar le peut.");
                return $this->redirectToRoute('edit_profile', ['username' => $student->getUsername()]);
            }
            foreach ($rolesId as $roleId) {
                $tempRole = $this->getDoctrine()->getRepository(Role::class)->find($roleId);
                if ($tempRole->getName() == "L'admashallah" && !$canBeAdmashallah) {
                    $this->addFlash('danger', "Seul Omar peut devenir Admashallah");
                    return $this->redirectToRoute('edit_profile', ['username' => $student->getUsername()]);
                }
                $student->addRole($tempRole);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Les permissions ont bien été modifiées");
            return $this->redirectToRoute('edit_profile', ["username" => $student->getUsername()]);
        }
    }

    /**
     * @Route("/admin/student/delete/{username}", name="delete_profile")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteProfileAction(Request $request, Student $student)
    {
        if (!$student) {
            throw $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
        }
        $this->getDoctrine()->getManager()->remove($student);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', "L'utilisateur a bien été supprimé !");
        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/admin/offers/", name="admin_offer_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listOfferAction(Request $request){
        $offers = $this->getDoctrine()->getRepository('AppBundle:Offer')->findAll();
        return $this->render('offer/admin/index.html.twig', ['offers' => $offers]);
    }

}
