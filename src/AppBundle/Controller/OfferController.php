<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Offer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
        $offers = $this->getDoctrine()->getRepository('AppBundle:Offer')->findBy(['isActive' => true]);
        return $this->render('offer/index.html.twig', ['offers' => $offers]);
    }

    /**
     * @Route("/offer/{id}", name="offer_show")
     */
    public function showOfferAction(Request $request, Offer $offer){
        if(!$offer) throw new NotFoundHttpException();
        $this->render('offer/show.html.twig', ['offer' => $offer]);
    }

    /**
     * @Route("/offers/add", name="offer_add")
     */
    public function offerAddAction(Request $request){
        if($this->getUser() != null) throw new AccessDeniedException();
        if($request->getMethod() == "GET"){
            return $this->render('offer/add.html.twig');
        } else {
            $parsedown = new \Parsedown();
            $offer = new Offer();
            $offer->setName($request->get('offer')['name']);
            $offer->setSecteur($request->get('offer')['secteur']);
            $offer->setPublishedAt(new \DateTime('now'));
            $offer->setDuree($request->get('offer')['duree']);
            $offer->setNbPost($request->get('offer')['nb_post']);
            $offer->setRemuneration($request->get('offer')['remuneration']);
            $offer->setTypeContrat($request->get('offer')['type_contrat']);
            $offer->setLocalisation($request->get('offer')['localisation']);
            $offer->setContact($request->get('offer')['contact']);
            $offer->setEntreprise($request->get('offer')['entreprise']);
            $offer->setDescription($parsedown->text($request->get('offer')['description']));
            $offer->setImageFile($request->files->get('offer')['image']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();
            $message = \Swift_Message::newInstance();
            $message->setSubject("Approbation d'une nouvelle offre !")
                ->setFrom($this->get('twig')->getGlobals()['site_name'] . '@' . strtolower($this->get('twig')->getGlobals()['site_name']) . '.com')
                ->setTo("omar.jbara2@gmail.com")
                ->setBody($this->renderView("mails/validate_offer.html.twig", ['offer' => $offer]), 'text/html');
            $this->get('mailer')->send($message);
            $this->addFlash('success', "L'offre a été ajouté et est en cours d'approbation. Un mail vous préviendra lorsque celle ci aura été accepté !");
            return $this->redirectToRoute("offers_index");
        }
    }

    /**
     * @Route("/offers/activate/{id}", name="activate_offer")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function activateOfferAction(Request $request, Offer $offer){
        if(!$offer) throw new NotFoundHttpException();
        $offer->setIsActive(true);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', "L'offre n°" . $offer->getId() . " a bien été activé !");
        return $this->redirectToRoute("offers_index");
    }

    /**
     * @Route("/offers/delete/{id}", name="delete_offer")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteOfferAction(Request $request, Offer $offer){
        if(!$offer) throw new NotFoundHttpException();
        $this->getDoctrine()->getManager()->remove($offer);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("success", "L'offre a bien été supprimé !");
        return $this->redirectToRoute("offers_index");
    }
}
