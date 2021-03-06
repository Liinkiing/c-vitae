<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StudentController extends Controller
{
    /**
     * @Route("/students/", name="students_list")
     */
    public function indexAction(Request $request)
    {

        $studentRepository = $this->getDoctrine()->getRepository('AppBundle:Student');
        $students = $studentRepository->findByAlphabeticalOrder();
        return $this->render('student/index.html.twig', ['title' => 'Liste des élèves',
            'students' => $students,
            'projectRoles' => $studentRepository->findProjectRoles(),
            'secteurVise' => $studentRepository->findSecteurVise(),
            'programmingLanguages' => $studentRepository->findProgrammingLanguages(),
            'langs' => $studentRepository->findLanguages(),
            'minAge' => $studentRepository->getMinOf('age'),
            'maxAge' => $studentRepository->getMaxOf('age')]);
    }

    /**
     * @Route("/students/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $studentRepository = $this->getDoctrine()->getRepository('AppBundle:Student');
        $students = $studentRepository->findWithParams(
            ($request->get('group') == '') ? ['A', 'B', 'C', 'D'] : $request->get('group'),
            $request->get('name'),
            $request->get('age'),
            $request->get('role'),
            $request->get('bac'),
            $request->get('prog_lang'),
            $request->get('gender'),
            $request->get('linkedin'),
            $request->get('viadeo'),
            $request->get('langs'),
            $request->get('cv'),
            $request->get('sort'),
            $request->get('by'));
        return $this->render('student/index.html.twig', ['students' => $students,
            'search' => true,
            'title' => 'Recherche',
            'projectRoles' => $studentRepository->findProjectRoles(),
            'secteurVise' => $studentRepository->findSecteurVise(),
            'programmingLanguages' => $studentRepository->findProgrammingLanguages(),
            'langs' => $studentRepository->findLanguages(),
            'minAge' => $studentRepository->getMinOf('age'),
            'maxAge' => $studentRepository->getMaxOf('age')]);
    }

    /**
     * @Route("/profile/me", name="my_profile")
     * @Method({"GET", "POST"})
     */
    public function showMyProfileAction(Request $request)
    {
        $currentStudent = $this->get('security.token_storage')->getToken()->getUser();
        if ($request->getMethod() == 'GET') {

            if ($currentStudent != "anon.") {
                return $this->render('student/my_profile.html.twig', ['user' => $this->get('security.token_storage')->getToken()->getUser()]);
            } else {
                $this->addFlash('danger', "Vous devez être connecté avant d'accéder à cette page !");
                return $this->redirectToRoute('login');
            }
        } else {
            $currentPassword = $request->get('userCurrentPassword');
            if (!$this->get('security.password_encoder')->isPasswordValid($currentStudent, $currentPassword)) {
                $this->addFlash('danger', "Mauvais mot de passe !");
                return $this->redirectToRoute('my_profile');
            }
            $newPassword = ($request->get('userPasswordFirst') == $request->get('userPasswordSecond')) ? $request->get('userPasswordFirst') : null;
            if ($newPassword == null) {
                $this->addFlash('danger', "Les mots de passe doivent correspondre !");
                return $this->redirectToRoute('my_profile');
            }
            $currentStudent->setPlainPassword($newPassword);
            $hashedPassword = $this->get('security.password_encoder')->encodePassword($currentStudent, $currentStudent->getPlainPassword());
            $currentStudent->setPassword($hashedPassword);
            $currentStudent->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->persist($currentStudent);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Vos informations ont bien été modifiées !");
            return $this->redirectToRoute('my_profile');
        }
    }

    /**
     * @Route("/profile/{username}", name="profile")
     */
    public function showProfileAction(Student $student)
    {
        if (!$student) {
            throw $this->createNotFoundException("L'utilisateur n'a pas été trouvé");
        } elseif ($student->getUsername() == $this->get('security.token_storage')->getToken()->getUsername()) return $this->redirectToRoute('my_profile');
        return $this->render('student/profile.html.twig', ['user' => $student]);
    }


    /**
     * @Route("/profile/me/modify-image", name="modify_student_image")
     * @Method({"POST"})
     */
    public function modifyProfileImageAction(Request $request)
    {
        $currentStudent = $this->get('security.token_storage')->getToken()->getUser();
        if ($currentStudent != "anon.") {
            if ($request->get('action') == "delete") {
                $this->get('oneup_flysystem.ftp_student_image_fs_filesystem')->delete($currentStudent->getProfilePicture());
                $currentStudent->setProfilePicture(null);
                $this->getDoctrine()->getManager()->persist($currentStudent);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', "Votre photo a bien été supprimé !");
                return $this->redirectToRoute('my_profile');
            } else if ($request->get('action') == "edit") {
                if ($currentStudent != "anon.") {
                    $file = $request->files->get('profilePictureFile');
                    if ($file == null || !$file->isValid() || !mb_ereg_match("image/.*", $file->getMimeType())) {
                        $this->addFlash("danger", "Veuillez choisir une image valide !");
                        return $this->redirectToRoute('my_profile');
                    }
                    $currentStudent->setImageFile($file);
                    $this->getDoctrine()->getManager()->persist($currentStudent);
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash('success', "La photo a bien été transféré");
                    return $this->redirectToRoute('my_profile');
                } else {
                    $this->addFlash('danger', "Vous devez être connecté avant d'accéder à cette page !");
                    return $this->redirectToRoute('login');
                }
            } else {
                return new HttpException(500, "Paramètres passé au champs 'action' non valide");
                }
            } else {
            $this->addFlash('danger', "Vous devez être connecté avant d'accéder à cette page !");
            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Route("/profile/me/modify-cv", name="modify_student_cv")
     * @Method({"POST"})
     */
    public function modifyCvAction(Request $request)
    {
        $currentStudent = $this->get('security.token_storage')->getToken()->getUser();
        if ($currentStudent != "anon.") {
            if ($request->get('action') == "delete") {
                $this->get('oneup_flysystem.ftp_student_cv_fs_filesystem')->delete($currentStudent->getCv());
                $currentStudent->setCv(null);
                $this->getDoctrine()->getManager()->persist($currentStudent);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', "Votre CV a bien été supprimé !");
                return $this->redirectToRoute('my_profile');
            } else if ($request->get('action') == "edit") {

                $file = $request->files->get('cv');
                if ($file == null || !$file->isValid() || !mb_ereg_match("application/x-download|application/pdf", $file->getMimeType())) {
                    $this->addFlash("danger", "Veuillez choisir un fichier PDF valide !");
                    return $this->redirectToRoute('my_profile');
                }
                $currentStudent->setCvFile($file);
                $this->getDoctrine()->getManager()->persist($currentStudent);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', "Le CV a bien été enregistré");
                return $this->redirectToRoute('my_profile');

            } else {
                return new HttpException(500, "Paramètres passé au champs 'action' non valide");
            }
        } else {
            $this->addFlash('danger', "Vous devez être connecté avant d'accéder à cette page !");
            return $this->redirectToRoute('login');
        }
    }


    /**
     * @Route("/profile/me/modify-informations", name="modify_student_informations")
     * @Method({"POST"})
     */
    public function modifyInformationsAction(Request $request)
    {

        $currentStudent = $this->get('security.token_storage')->getToken()->getUser();
        if ($currentStudent != "anon.") {
            $utils = $this->get('app.utilities');
            $timezone = new \DateTimeZone('Europe/Paris');
            $d = \DateTime::createFromFormat('d/m/Y', $request->get('birthday'), $timezone);
            if (($d && $d->format('d/m/Y') === $request->get('birthday')) == false) {
                $this->addFlash('danger', "Veuillez entrer une date valide !");
                return $this->redirectToRoute('my_profile');
            }
            $currentStudent->setDescription($utils->setNullIfStringEmpty(trim($request->get('description'))));
            $currentStudent->setWebsite($utils->setNullIfStringEmpty(trim($request->get('website'))));
            $currentStudent->setLinkedin($utils->setNullIfStringEmpty(trim($request->get('linkedin'))));
            $currentStudent->setViadeo($utils->setNullIfStringEmpty(trim($request->get('viadeo'))));
            $errors = [];
            if ($currentStudent->getLinkedin() != '' && !$utils->matchWebsite('linkedin.com', $currentStudent->getLinkedin())) {
                array_push($errors, 'Veuillez entrer un profil LinkedIn valide !');
            }
            if ($currentStudent->getViadeo() != '' && !$utils->matchWebsite('viadeo.com', $currentStudent->getViadeo())) {
                array_push($errors, 'Veuillez entrer un profil Viadeo valide !');
            }
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('danger', $error);
                }
                return $this->redirectToRoute('my_profile');
            }

            $currentStudent->setProfessionalMail($utils->setNullIfStringEmpty($request->get('mail')));
            $currentStudent->setBirthday($d);
            $currentStudent->setAge($d->diff(new \DateTime('now', $timezone))->y);
            if ($request->get('hobbies') == '') $currentStudent->setHobbies(null); else $currentStudent->setHobbies(
                array_map(function ($item) {
                    return trim($item);
                }, explode(',', $request->get('hobbies'))));
            if ($request->get('softwares') == '') $currentStudent->setSoftwares(null); else $currentStudent->setSoftwares(
                array_map(function ($item) {
                    return trim($item);
                }, explode(',', $request->get('softwares'))));
            if ($request->get('languages') == '') $currentStudent->setLanguages(null); else $currentStudent->setLanguages(
                array_map(function ($item) {
                    return trim($item);
                }, explode(',', $request->get('languages'))));
            if ($request->get('programmingLanguages') == '') $currentStudent->setProgrammingLanguages(null); else $currentStudent->setProgrammingLanguages(
                array_map(function ($item) {
                    return trim($item);
                }, explode(',', $request->get('programmingLanguages'))));

            $currentStudent->setFavoriteMusic($utils->setNullIfStringEmpty($request->get('favMusic')));
            $currentStudent->setFavoriteQuote($utils->setNullIfStringEmpty($request->get('favQuote')));
            $currentStudent->setProjectRole($utils->setNullIfStringEmpty($request->get('projectRole')));
            $currentStudent->setUpdatedAt(new \DateTime('now', $timezone));
            $this->getDoctrine()->getManager()->persist($currentStudent);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Vos informations ont bien été enregistrés");
            return $this->redirectToRoute('my_profile');
        } else {
            $this->addFlash('danger', "Vous devez être connecté avant d'accéder à cette page !");
            return $this->redirectToRoute('login');
        }
    }

}
