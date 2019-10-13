<?php


namespace App\Controller\System;


use App\Entity\Page;
use App\Security\RegistrationEnabledChecker;
use App\Security\SecurityDisabledChecker;
use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmbeddedController extends AbstractController
{
    public function leftMenu($path = "", StorageManager $manager)
    {
        $homePage       = new Page();
        $manager->listAllFiles("", $homePage);
        $homePage->name = "Home";
        $homePage->path = "readme";

        return $this->render("embedded/left.html.twig", ["nav" => $homePage, "path" => $path]);
    }

    public function navbar(SecurityDisabledChecker $securityDisabledChecker, RegistrationEnabledChecker $registrationEnabledChecker)
    {
        return $this->render("embedded/top.html.twig", [
            "security_enabled"     => !$securityDisabledChecker->check(),
            "registration_enabled" => $registrationEnabledChecker->check(),
        ]);
    }

}