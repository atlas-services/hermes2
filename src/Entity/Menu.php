<?php

namespace App\Entity;

use App\Entity\Section;
use App\Entity\Traits\ActiveTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\PositionTrait;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    use ActiveTrait;
    use NameTrait;
    use PositionTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    /**
     * @var Section[]|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Section::class, mappedBy: 'menu', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    protected $sections;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->sections = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        if ($parent === $this) {
            $this->parent = $this;  // Rendre `parent` non nul en cas de référence à soi-même
        } else {
            $this->parent = $parent;
        }
        // $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addMenu(self $menu): static
    {
        if (!$this->children->contains($menu)) {
            $this->children->add($menu);
            $menu->setParent($this);
        }

        return $this;
    }

    public function removeMenu(self $menu): static
    {
        if ($this->children->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getParent() === $this) {
                $menu->setParent(null);
            }
        }

        return $this;
    }

    public function addSection(?Section ...$sections): void
    {
        foreach ($sections as $section) {
            if (!$this->sections->contains($section)) {
                if($section->isActive()){
                    $this->sections->add($section);
                    $section->setMenu($this);
                }
            }
        }
    }

    public function removeSection(Section $section): void
    {
        $this->sections->removeElement($section);
        $section->setMenu(null);
    }

    public function getSections(): ?Collection
    {
        foreach ($this->sections as $section){
            if(!$section->isActive()){
                $this->removeSection($section);
            }
        }
        return $this->sections;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->parent === null) {
            $context->buildViolation('The parent cannot be null.')
                ->atPath('parent')
                ->addViolation();
        }
    }
}
