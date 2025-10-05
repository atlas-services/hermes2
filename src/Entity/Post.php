<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\AbstractContent;
use App\Entity\Section;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\PublishedTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
#[Vich\Uploadable]
#[ORM\Table(name: 'post')]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['section', 'name'], errorPath: 'name', message: 'post.exists')]
class Post extends AbstractContent
{
    use PositionTrait;
    use PublishedTrait;

    /**
     * @var Section
     */
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: Section::class, inversedBy: 'posts')]
    private $section;


    public function __toString(): string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }

    public function getSection()
    {
        return $this->section;
    }

    public function setSection(?Section $section): void
    {
        $this->section = $section;
    }

}
