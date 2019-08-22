<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActComercialController extends AbstractController
{
    /**
     * @Route("/actComercial", name="act_comercial")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $menus = new MenusController();
        $menus->setContainer($this->container);
        $menus_app[0]=$menus->dimeMenu(1);
        $menus_app[1]=$menus->dimeMenuPerfil(2);
        $menus_app[2]=$menus->dimeMenuVertical(7,'/actComercial');
        return $this->render('act_comercial/index.html.twig', [
            'controller_name' => 'ActComercialController',
            'menus' => $menus_app
        ]);
    }
}
