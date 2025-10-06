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
                if(0 == count($menu->getChildren())){
                    $menuPage[] = $menu;
                }
            }
            return $menuPage;
    }

}
