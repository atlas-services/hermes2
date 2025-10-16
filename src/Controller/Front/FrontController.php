<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Front;

use App\Service\ConfigService;
use App\Service\MenuService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FrontController extends AbstractController
{

    #[Route(path: '/', name: 'home', methods: ['GET|POST'])]
    #[Route(path: '/{_locale?}', name: 'homepage', methods: ['GET|POST'])]
    public function homepage(Request $request, MenuService $menuService, ConfigService $configService )
    {
        $menus = $menuService->getMenus();
        $subMenus = $menuService->getAllSubMenus();
        $configs = $configService->getActiveConfig();

        $array = [
            'menus' => $menus,
            'submenus' => $subMenus,
        ];

        $array = array_merge($array, $configs);

        // dd($array);

        return $this->render('front/index.html.twig', $array);
    }





}
