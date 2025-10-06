<?php

namespace App\Service;

use App\Repository\SectionRepository;

class SectionService
{

    public function __construct(private SectionRepository $sectionRepository)
    {

    }

    public function createSection($section){
        $lastPosition = $this->sectionRepository->getLastSectionPosition() ;
        $newPosition = $lastPosition + 1;
        $section->setPosition($newPosition);
        $this->sectionRepository->save($section);

    }

    public function getSections(): array
    {
        $sections = $this->sectionRepository->getSections();
        return $sections;
    }


}
