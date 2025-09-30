<?php

namespace App\Service;

use App\Repository\MenuRepository;


class MenuService
{

    public function __construct(private MenuRepository $menuRepository)
    {

    }

    public function createMenu($menu){
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


}
