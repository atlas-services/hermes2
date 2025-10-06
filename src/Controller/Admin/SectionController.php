<?php

namespace App\Controller\Admin;

use App\Entity\Section;
use App\Form\SectionType;
use App\Service\SectionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/section', defaults: ['_locale' => 'fr'], requirements: ['_locale' => 'fr|en'],)]
final class SectionController extends BaseController
{

    #[Route(name: 'section_index', methods: ['GET'])]
    public function index(SectionService $sectionService): Response
    {

        $sections = $sectionService->getSections();

        return $this->render('admin/section/index.html.twig', [
            'sections' => $sections,
        ]);
    }

    #[Route('/new', name: 'section_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SectionService $sectionService): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionService->createSection($section);
             $this->addFlash('info', sprintf('Section "%s" created!', $section->getName()));
            return $this->redirectToRoute('section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/section/new.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'section_show', methods: ['GET'])]
    public function show(Section $section): Response
    {
        return $this->render('admin/section/show.html.twig', [
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'section_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        // $form = $this->createForm(sectionType::class, $section);
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
             $this->addFlash('info', sprintf('Section "%s" updated!', $section->getName()));
            return $this->redirectToRoute('section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'section_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($section);
            $entityManager->flush();
            $this->addFlash('info', sprintf('Section "%s" deleted!', $section->getName()));
        }

        return $this->redirectToRoute('section_index', [], Response::HTTP_SEE_OTHER);
    }

}
