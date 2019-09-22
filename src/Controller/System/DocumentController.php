<?php

namespace App\Controller\System;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentController
 * @package App\Controller
 * @Route("documents",name="documents")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("", name="document")
     */
    public function index()
    {
        return $this->render('document/index.html.twig', [
            'controller_name' => 'DocumentController',
        ]);
    }

    /**
     *
     * @Route("/{path}",requirements={"path"=".+"}, methods={"PUT"},name="_store")
     */
    public function store($path, Request $request)
    {
        dd($path);
    }
}
