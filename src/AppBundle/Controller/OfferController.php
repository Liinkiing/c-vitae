<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OfferController extends Controller
{
    /**
     * @Route("/offers/", name="offers_index")
     */
    public function indexAction(Request $request)
    {
        $offers = $this->getDoctrine()->getRepository('AppBundle:Offer')->findAllActive();
        return $this->render('offer/index.html.twig', ['offers' => $offers]);
    }

    /**
     * @Route("/offer/{id}", name="offer_show")
     */
    public function showOfferAction(Request $request, Offer $offer)
    {
        if (!$offer) throw new NotFoundHttpException();
        $currentStudent = $this->getUser();
        if ($currentStudent == null && !$offer->getIsActive()) {
            $this->addFlash("danger", "L'offre n'a pas encore été approuvé, veuillez patienter !");
            return $this->redirectToRoute("offers_index");
        } elseif ($currentStudent != null && !$currentStudent->isGranted('ROLE_ADMIN') && !$offer->getIsActive()) {
            $this->addFlash("danger", "L'offre n'a pas encore été approuvé, veuillez patienter !");
            return $this->redirectToRoute("offers_index");
        }
        if ($currentStudent != null) {
            $hasCV = ($currentStudent->getCv() != null) ? "true" : "false";
        } else $hasCV = "false";
        return $this->render('offer/show.html.twig', ['offer' => $offer, 'hasCV' => $hasCV]);
    }

    /**
     * @Route("/offer/{id}/apply", name="offer_apply")
     * @Security("has_role('ROLE_USER')")
     */
    public function offerApplyAction(Request $request, Offer $offer)
    {
        if (!$offer) throw new NotFoundHttpException();
        $currentUser = $this->getUser();
        if (($currentUser == null && !$offer->getIsActive()) || (!$currentUser->isGranted('ROLE_ADMIN') && !$offer->getIsActive())) {
            $this->addFlash("danger", "L'offre n'a pas encore été approuvé, veuillez patienter !");
            return $this->redirectToRoute("offers_index");
        }
        if ($offer->hasPostuled($currentUser)) {
            $this->addFlash("danger", "Vous avez déjà postulé à cette offre !");
            return $this->redirectToRoute("offer_show", ['id' => $offer->getId()]);
        }

        $parsedown = new \Parsedown();
        $personnalMessage = $this->get('app.utilities')->setNullIfStringEmpty($parsedown->text($request->get('aboutme')));
        $message = \Swift_Message::newInstance();
        $message->setSubject($currentUser->getFullName() . " souhaite postuler pour votre offre « " . $offer->getTitle() . " »")
            ->setFrom([$this->get('twig')->getGlobals()['contact_mail'] => $this->get('twig')->getGlobals()['site_name']])
            ->setTo($offer->getContact())
            ->setBody($this->renderView('mails/apply.html.twig', ['offer' => $offer, 'student' => $currentUser, 'personnalMessage' => $personnalMessage]), 'text/html')
            ->addPart('texte brut en version text', 'text/plain');
        if ($currentUser->getCv()) {
            $url = $this->get('twig')->getGlobals()['base_upload_url'] . $this->get('vich_uploader.templating.helper.uploader_helper')->asset($currentUser, 'cvFile');
            $message->attach(Swift_Attachment::fromPath($url));
        }
        $this->get('mailer')->send($message);
        $studentsPostulated = $offer->getStudentsPostuled();
        array_push($studentsPostulated, $currentUser->getUsername());
        $offer->setStudentsPostuled($studentsPostulated);
        $this->getDoctrine()->getEntityManager()->flush();
        $this->addFlash("success", "Vous avez bien postulé à l'offre !");
        return $this->redirectToRoute("offer_show", ['id' => $offer->getId()]);
    }

    /**
     * @Route("/offers/add", name="offer_add")
     */
    public function offerAddAction(Request $request)
    {
        if ($this->getUser() != null && !in_array('ROLE_ADMIN', $this->getUser()->getRoles())) throw new AccessDeniedException();
        if ($request->getMethod() == "GET") {
            if (!$this->getUser()) $this->addFlash("info", "Votre offre ne sera pas instantanément disponible. Elle sera soumise à approbation");
            return $this->render('offer/add.html.twig');
        } else {
            $parsedown = new \Parsedown();
            $offer = new Offer();
            $offer->setTitle($request->get('offer')['title']);
            $offer->setSecteur($request->get('offer')['secteur']);
            $offer->setPublishedAt(new \DateTime('now'));
            $offer->setUpdatedAt(new \DateTime('now'));
            $offer->setDuree($request->get('offer')['duree']);
            $offer->setNbPost($request->get('offer')['nb_post']);
            $offer->setRemuneration($request->get('offer')['remuneration']);
            $offer->setTypeContrat($request->get('offer')['type_contrat']);
            $offer->setLocalisation($request->get('offer')['localisation']);
            $offer->setContact($request->get('offer')['contact']);
            if (array_key_exists('is_active', $request->get('offer'))) $offer->setIsActive($request->get('offer')['is_active']);
            $offer->setEntreprise($request->get('offer')['entreprise']);
            $offer->setDescription($parsedown->text($request->get('offer')['description']));

            $file = $request->files->get('offer')['image'];
            if (($file != null && !$file->isValid()) || ($file != null && !mb_ereg_match("image/.*", $file->getMimeType()))) {
                $this->addFlash("danger", "Veuillez choisir une image valide !");
                return $this->redirectToRoute('offer_add');
            }
            $offer->setImageFile($file);
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();
            if (!$offer->getIsActive()) {
                $message = \Swift_Message::newInstance();
                $message->setSubject("Approbation d'une nouvelle offre !")
                    ->setFrom([$this->get('twig')->getGlobals()['contact_mail'] => $this->get('twig')->getGlobals()['site_name']])
                    ->setTo("omar.jbara2@gmail.com")
                    ->setBody($this->renderView("mails/validate_offer.html.twig", ['offer' => $offer, 'ip' => $request->getClientIp()]), 'text/html');
                $this->get('mailer')->send($message);
                $this->addFlash('success', "L'offre a été ajouté et est en cours d'approbation. Un mail vous préviendra lorsque celle ci aura été accepté !");
            } else {
                $this->addFlash("success", "L'offre a été ajouté et n'a pas besoin d'approbation étant donné que vous êtes administrateur");
            }
            return $this->redirectToRoute("offers_index");
        }
    }


    /**
     * @Route("/admin/offers/edit/{id}", name="admin_offer_edit")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editOfferAction(Request $request, $id)
    {
        $offer = $this->getDoctrine()->getRepository('AppBundle:Offer')->find($id);
        if (!$offer) throw $this->createNotFoundException();
        if ($request->getMethod() == "GET") {
            return $this->render('offer/edit.html.twig', ['offer' => $offer]);
        } else {
            $parsedown = new \Parsedown();
            $offer->setTitle($request->get('offer')['title']);
            $offer->setSecteur($request->get('offer')['secteur']);
            $offer->setUpdatedAt(new \DateTime('now'));
            $offer->setDuree($request->get('offer')['duree']);
            $offer->setNbPost($request->get('offer')['nb_post']);
            $offer->setRemuneration($request->get('offer')['remuneration']);
            $offer->setTypeContrat($request->get('offer')['type_contrat']);
            $offer->setLocalisation($request->get('offer')['localisation']);
            $offer->setContact($request->get('offer')['contact']);
            $offer->setEntreprise($request->get('offer')['entreprise']);
            $offer->setDescription($parsedown->text($request->get('offer')['description']));

            $file = $request->files->get('offer')['image'];
            if (($file != null && !$file->isValid()) || ($file != null && !mb_ereg_match("image/.*", $file->getMimeType()))) {
                $this->addFlash("danger", "Veuillez choisir une image valide !");
                return $this->redirectToRoute('offer_add');
            }

            if ($file) {
                $offer->setImageFile($request->files->get('offer')['image']);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "L'offre a bien été mise à jour !");
            return $this->redirectToRoute('offer_show', ['id' => $offer->getId()]);
        }
    }

    /**
     * @Route("/offers/activate/{id}", name="activate_offer")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function activateOfferAction(Request $request, Offer $offer)
    {
        if (!$offer) throw new NotFoundHttpException();
        $offer->setIsActive(true);
        $this->getDoctrine()->getManager()->flush();
        $message = \Swift_Message::newInstance();
        $message->setSubject("Votre offre a été approuvé !")
            ->setFrom([$this->get('twig')->getGlobals()['contact_mail'] => $this->get('twig')->getGlobals()['site_name']])
            ->setTo($offer->getContact())
            ->setBody($this->renderView("mails/offer_validated.html.twig", ['offer' => $offer]), 'text/html');
        $this->get('mailer')->send($message);
        $this->addFlash('success', "L'offre n°" . $offer->getId() . " a bien été activé !");
        return $this->redirectToRoute("offers_index");
    }

    /**
     * @Route("/offers/delete/{id}", name="delete_offer")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteOfferAction(Request $request, Offer $offer)
    {
        if (!$offer) throw new NotFoundHttpException();
        $this->getDoctrine()->getManager()->remove($offer);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("success", "L'offre a bien été supprimé !");
        return $this->redirectToRoute("admin_offer_index");
    }

    /**
     * @Route("/offers/delete/image/{id}", name="admin_delete_offer_image")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteOfferImageAction(Request $request, Offer $offer)
    {
        if (!$offer) throw new NotFoundHttpException();
        $this->get('oneup_flysystem.ftp_offer_image_fs_filesystem')->delete($offer->getImageUrl());
        $offer->setImageUrl(null);
        $this->getDoctrine()->getEntityManager()->flush();
        $this->addFlash("success", "L'image a bien été supprimé !");
        return $this->redirectToRoute("admin_offer_edit", ['id' => $offer->getId()]);
    }
}
