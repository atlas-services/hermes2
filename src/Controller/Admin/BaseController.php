<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin', defaults: ['_locale' => 'fr'], requirements: ['_locale' => 'fr|en'],)]
class BaseController extends AbstractController
{

    #[Route('/update-positions', name: 'app_update_positions', methods: ['POST'])]
    public function updatePositions(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        $entityClass = match($data['type']) {
            'menu' => Menu::class,
            'post' => Post::class,
        };

        foreach ($data['positions'] as $positionData) {
            $item = $entityManager->getRepository($entityClass)->find($positionData['id']);
            if ($item) {
                $item->setPosition($positionData['position']);
                $entityManager->persist($item);
            }
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'success']);
    }

    #[Route('/switch-active', name: 'app_switch_active', methods: ['POST'])]
    public function updatswitchActive(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        $entityClass = match($data['type']) {
            'menu' => Menu::class,
            'post' => Post::class,
        };

        $item = $entityManager->getRepository($entityClass)->find($data['id']);
        if ($item) {
            $item->setActive(!$item->isActive());
            $entityManager->persist($item);
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'success']);
    }

}
