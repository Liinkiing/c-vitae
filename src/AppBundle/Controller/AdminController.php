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
use AppBundle\Form\UserType;


class AdminController extends Controller
{

    /**
     * @Route("/admin/", name="admin_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request){
        return $this->render('admin/index.html.twig', ['test']);


    }


    /**
     * @Route("/admin/posts/", name="blog_list_posts")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listPostAction(Request $request){
        $em = $this->getDoctrine()->getRepository(Post::class);
        $posts = $em->findAll('DESC');
        return $this->render('admin/blog_list_posts.html.twig', ['posts' => $posts]);


    }

    /**
     * @Route("/admin/projects/", name="portfolio_list_projects")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listProjectsAction(Request $request){
        $em = $this->getDoctrine()->getRepository(Project::class);
        $projects = $em->findAll();
        return $this->render('admin/portfolio_list_projects.html.twig', ['projects' => $projects]);


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
    public function editProfileAction(Request $request, Student $student){
        if(!$student){
            throw $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
        }
        if($request->getMethod() == "GET"){
            $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
            return $this->render('student/student_edit.html.twig', ['student' => $student, 'availableRoles' => $roles]);
        } else {
            $canBeAdmashallah = ($student->getUsername() == "ojbara") ? true : false;
            $rolesId = $request->get('roles');
            foreach($student->getRolesObject() as $role){
                $student->removeRole($role);
            }
            foreach($rolesId as $roleId){
                $tempRole = $this->getDoctrine()->getRepository(Role::class)->find($roleId);
                if($tempRole->getName() == "L'admashallah" && !$canBeAdmashallah) {
                    $this->addFlash('danger', "Si tu ne sais pas lire, y'a que Omar (ojbara) qui peut devenir Admashallah. FDP lis mieux la prochaine fois");
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
    public function deleteProfileAction(Request $request, Student $student){
        if(!$student){
            throw $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
        }
        $this->getDoctrine()->getManager()->remove($student);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', "L'utilisateur a bien été supprimé !");
        return $this->redirectToRoute('user_list');
    }




}
