<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Service\MenuService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/menu', defaults: ['_locale' => 'fr'], requirements: ['_locale' => 'fr|en'],)]
final class MenuController extends BaseController
{

    #[Route(name: 'menu_index', methods: ['GET'])]
    public function index(MenuService $menuService): Response
    {

        $menus = $menuService->getMenus();
        $subMenus = $menuService->getAllSubMenus();

        return $this->render('admin/menu/index.html.twig', [
            'menus' => $menus,
            'submenus' => $subMenus,
        ]);
    }

    #[Route('/new', name: 'menu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MenuService $menuService): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuService->createMenu($menu);
             $this->addFlash('info', sprintf('Menu "%s" created!', $menu->getName()));
            return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'menu_show', methods: ['GET'])]
    public function show(Menu $menu): Response
    {
        return $this->render('admin/menu/show.html.twig', [
            'menu' => $menu,
        ]);
    }

    #[Route('/{id}/edit', name: 'menu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        // $form = $this->createForm(MenuType::class, $menu);
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
             $this->addFlash('info', sprintf('Menu "%s" updated!', $menu->getName()));
            return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'menu_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($menu);
            $entityManager->flush();
            $this->addFlash('info', sprintf('Menu "%s" deleted!', $menu->getName()));
        }

        return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
    }

}
