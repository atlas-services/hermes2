<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 06/09/19
 * Time: 10:28
 */

namespace App\Controller\Front;

use App\Entity\Menu;
use App\Service\ConfigService;
use App\Service\MenuService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FrontController extends AbstractController
{
    #[Route(path: '/{_locale?}', name: 'homepage', methods: ['GET'], defaults: ['_locale' => 'fr'])]
    #[Route(path: '/{_locale?}/{menu}/{submenu}', name: 'menu-submenu', methods: ['GET'], defaults: ['_locale' => 'fr'])]
    #[Route(path: '/', name: 'home', methods: ['GET'], defaults: ['_locale' => 'fr'])]
    public function homepage(Request $request, MenuService $menuService, ConfigService $configService, #[MapEntity(mapping: ['menu' => 'slug'])] ?Menu $menu, #[MapEntity(mapping: ['submenu' => 'slug'])] ?Menu $submenu )
    {

        $configs = $configService->getActiveConfig();
        $pages = $menuService->getMenusAndPosts($submenu);

        $array = [
            'menus' => $pages['menus'],
            'posts' => $pages['posts']
        ];

        $array = array_merge($array, $configs);

        // dd($array);

        return $this->render('front/index.html.twig', $array);
    }





}
