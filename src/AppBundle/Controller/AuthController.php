<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request){
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        if($error){
            switch ($error->getCode()){
                case 0:
                    $this->addFlash('danger', "Mauvaise combinaison utilisateur/mot de passe");
                    break;
            }

        }
        return $this->render('auth/login.html.twig', ['last_username' => $lastUsername]);

    }


}
