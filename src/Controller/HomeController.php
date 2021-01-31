<?php
/**
 * Created by PhpStorm.
 * User: albancrepel
 * Date: 2020-04-16
 * Time: 09:54
 */

namespace App\Controller;

use App\Annotations\RouteExposed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/content/", name="home")
     */
    public function index(Request $request)
    {

        if ($request->getPreferredFormat() === "html") {
            return $this->render('base.html.twig');
        }

    }
}
