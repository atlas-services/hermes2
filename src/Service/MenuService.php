<?php

namespace App\Service;

use App\Repository\MenuRepository;

class MenuService
{

    public function __construct(private MenuRepository $menuRepository)
    {

    }

    public function createMenu($menu){
        $lastPosition = $this->menuRepository->getLastMenuPosition() ;
        $newPosition = $lastPosition + 1;
        $menu->setPosition($newPosition);
        $this->menuRepository->save($menu);
        if(is_null($menu->getParent())){
            $menu->setParent($menu);
            $this->menuRepository->save($menu);
        }
    }

    public function getMenus(): array
    {
        $menus = $this->menuRepository->getMenus();

        return $menus;
    }


    public function getMenusAndPosts($submenu): array
    {
        $menus = $this->menuRepository->getMenus();
        $posts = $submenu?->getPosts();
        if(is_null($posts)){
            if(isset($menus[0])){
                $submenus = $menus[0]?->getChildren();
                $posts = $submenus[1]?->getPosts();
            }
        }

        return [
            'menus' => $menus,
            'posts' => $posts,
        ];

    }


    public function getCurrentMenu($menus, $menu, $submenu): array
    {
        // dd($menu);
        return $menus;
    }

    public function getAllSubMenus(): array
    {
        $subMenus = [];
        $menus = $this->menuRepository->getMenus();

        foreach($menus as $menu){
            $subMenus[$menu->getName()] = $this->getSubMenus($menu);
        }

        return $subMenus;
    }

    public function getSubMenus($menu): array
    {
        $subMenus = $this->menuRepository->getSubMenus($menu);
        return $subMenus;
    }

    /**
     * @return Menu[] Returns an array of Menu objects that are their own Menu
     */
    public function getMenusPage(): array
    {
            $result = $this->menuRepository->findAll();
            foreach($result as $menu){
                if(1 == count($menu->getChildren())){
                    $menuPage[] = $menu;
                }
            }
            return $menuPage;
    }

    public function getSitemapByLocale($locale, $host="")
    {
        $menus = $this->getMenus();


        $urls = $urls_xml = $urls_html = [];
        $menusLocale = $this->menuRepository
        ->findAll()
        // ->findBy(['locale' => $locale])
        ;
        foreach ($menusLocale as $key => $menu){
            $updated = $menu->getUpdatedAt();
            if(is_null($updated)){
                $updated = (new \DateTime("now"))->format('Y-m-d');
            }else{
                $updated = $menu->getUpdatedAt()->format('Y-m-d');
            }
            if($locale == $menu->getSheet()->getLocale()){
                $name = $menu->getName();
                $sheet_name = $menu->getName();
                if($menu->getSlug() == $menu->getSlug()){
                    $name = $menu->getSheet()->getName();
                    $loc = $host. '/'. $locale. '/'.$menu->getSheet()->getSlug();
                }else{
                    $loc = $host. '/'. $locale. '/'.$menu->getSheet()->getSlug(). '/' . $menu->getSlug();                
                }
                // homepage
                if(0 == $key){
                    $loc = $host. '/'. $locale;
                }
                $urls_xml[] = [
                    'name' => $name,
                    'sheetname' => $sheet_name,
                    'loc' => $loc,
                    'lastmod' => $updated,
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
                $urls_html[$menu->getSheet()->getSlug()][] = [
                    'name' => $name,
                    'sheetname' => $sheet_name,
                    'loc' => $loc,
                    'lastmod' => $updated,
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
            }
        }
        $urls['xml'] = $urls_xml;
        $urls['html'] = $urls_html;
        return $urls;
    }

}
