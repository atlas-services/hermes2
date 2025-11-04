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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FrontController extends AbstractController
{
    #[Route('/robots.txt', name: 'robots_txt', defaults: ['_format' => 'txt'])]
    public function robots(Request $request): Response
    {
        $host = $request->getHttpHost();

        $response = new Response(
            "User-agent: *
            Disallow: /admin/
            Disallow: /login/
            Allow: /
            Sitemap: https://$host/sitemap.xml"
        );

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }


    #[Route(path: '/{_locale}/sitemap.xml', name: 'sitemap', methods: ['GET'])]
    public function sitemap(Request $request, MenuService $menuService)
    {
        $localeRouting = $request->attributes->get('_locale' , 'fr');
        // $locale = $menuService->getLocale($localeRouting);
        $locale = $localeRouting;
        $host = $request->getSchemeAndHttpHost();
        $urls = $menuService->getSitemapByLocale($locale, $host);

        $response = new Response(
            $this->renderView('front/base/hermes/sitemap/sitemapxml.html.twig', ['urls' => $urls['xml']]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;


    }

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
