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
use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\PublishedTrait;
use App\Entity\Traits\TemplateTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
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
#[UniqueEntity(fields: ['menu', 'name'], errorPath: 'name', message: 'post.exists')]
class Post extends AbstractContent
{
    use ActiveTrait;
    use PositionTrait;
    use PublishedTrait;
    use IdTrait;
    use TemplateTrait;
    use PositionTrait;
    use NameTrait;

    /**
     * @var Template
     */
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: Template::class, inversedBy: 'posts')]
    protected $template;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Length(max: 3)]
    protected $template_width;

    /**
     * @var bool
     *
     */
    protected $transparent;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Length(max: 11)]
    protected $template_bgcolor;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Length(max: 1)]
    protected $template_nb_col;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    protected $template_image_filter;

    /**
     * @var Template
     */
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\ManyToOne(targetEntity: Template::class, inversedBy: 'posts')]
    protected $template2;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Length(max: 3)]
    protected $template2_width;

    #[ORM\JoinColumn(name: 'menu', referencedColumnName: 'id', onDelete: 'CASCADE', nullable: true)]
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'posts')]
    protected $menu;


    public function __toString(): string
    {
        if(!is_null($this->name)){
            return $this->name;
        }
        return '';
    }


    /**
     * @return mixed
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param mixed $menu
     */
    public function setMenu(?Menu $menu): ?Post
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * @return int
     */
    public function getTemplateWidth(): ?int
    {
        if( '' == $this->template_width){
            return '10';
        }
        return $this->template_width;
    }

    /**
     * @param int $template_width
     */
    public function setTemplateWidth(?int $template_width): void
    {
        $this->template_width = $template_width;
        if(is_null($this->template_width)){
            $this->template_width = 10;
        }
    }

    /**
     * @return string
     */
    public function getTransparent(): ?bool
    {
        return $this->transparent;
    }

    /**
     * @param bool
     */
    public function setTransparent($tranparent): void
    {
        $this->transparent = $tranparent;
        if($this->transparent){
            $this->setTemplateBgcolor('transparent');
        }
    }

    /**
     * @return string
     */
    public function getTemplateBgcolor(): ?string
    {
        if( null == $this->template_bgcolor or $this->getTransparent()){
            return  'transparent';
        }
        return $this->template_bgcolor;
    }

    /**
     * @param string $template_bgcolor
     */
    public function setTemplateBgcolor(?string $template_bgcolor): void
    {
        $this->template_bgcolor = $template_bgcolor;
    }

    /**
     * @return int
     */
    public function getTemplateNbCol(): ?int
    {
        if( '' == $this->template_nb_col){
            return 4 ;
        }
        return $this->template_nb_col;
    }

    /**
     * @param int $template_nb_col
     */
    public function setTemplateNbCol(?int $template_nb_col): void
    {
        $this->template_nb_col = $template_nb_col;
    }

    /**
     * @return string
     */
    public function getTemplateImageFilter(): ?string
    {
        if( '' == $this->template_image_filter){
            return 'bd_154' ;
        }
        return $this->template_image_filter;
    }

    /**
     * @param string $template_image_filter
     */
    public function setTemplateImageFilter(?string $template_image_filter): void
    {
        $this->template_image_filter = $template_image_filter;
    }

    /**
     * @return int
     */
    public function getTemplate2Width(): ?int
    {
        if( '' == $this->template2_width || null == $this->template2_width){
            return '4';
        }
        return $this->template2_width;
    }

    /**
     * @param int $template2_width
     */
    public function setTemplate2Width(?int $template2_width=4): void
    {
        $this->template2_width = $template2_width;
    }

}
