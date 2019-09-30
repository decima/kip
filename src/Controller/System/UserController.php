<?php

namespace App\Controller\System;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\RegistrationEnabledChecker;
use App\Security\SecurityDisabledChecker;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller\System
 * @Route("/user", name="user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/login", name="_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, SecurityDisabledChecker $securityDisabled): Response
    {
        //dd($parameterBag->get("auth_ldap_enabled"),$parameterBag->get("auth_database_enabled"));
        if ($securityDisabled->check()) {
            return $this->redirectToRoute('knowledge_read_home');

        }

        if ($this->getUser()) {
            return $this->redirectToRoute('knowledge_read_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * @Route("/logout", name="_logout")
     */
    public
    function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        UserAuthenticator $authenticator,
        RegistrationEnabledChecker $registrationEnabledChecker
    ): Response
    {
        if (!$registrationEnabledChecker->check()) {
            $this->addFlash("error", "Registration is disabled");
            return $this->redirectToRoute("knowledge_read_home");
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
