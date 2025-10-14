<?php

namespace App\Entity;

use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\CodeTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\SummaryTrait;
use App\Entity\Traits\TypeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
#[ORM\Table(name: 'template')]
#[ORM\Entity()]
class Template
{
    const TEMPLATE_TYPE_LISTE = 'liste';
    const TEMPLATE_LIBRE = 'libre';
    const TEMPLATE_LISTE = 'folio1';
    const TEMPLATE_MODALE = 'modale1';
    const TEMPLATE_FORM = 'formulaire';

    use IdTrait;
    use ActiveTrait;
    use TypeTrait;
    use CodeTrait;
    use NameTrait;
    use SummaryTrait;

    /**
     * @var Post[]|ArrayCollection
     */
    #[ORM\JoinTable(name: 'post_template')]
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'template', cascade: ['persist', 'remove'])]
    private $posts;

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function __toString(): string
    {
        if(!is_null($this->summary)){
            return $this->summary;
        }
        return '';
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function __isset($prop) : bool
    {
        return isset($this->$prop);
    }

    public function addPost(?Post ...$posts): void
    {
        foreach ($posts as $post) {
            if (!$this->posts->contains($post)) {
                if($post->isActive()){
                    $this->posts->add($post);
                }
            }
        }
    }

    public function removePost(Post $post): void
    {
        $this->posts->removeElement($post);
        $post->setPost(null);
    }

    public function getPosts(): ?Collection
    {
        foreach ($this->posts as $post){
            if(!$post->isActive()){
                $this->removePost($post);
            }
        }
        return $this->posts;
    }

}
