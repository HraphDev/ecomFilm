<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Cartoon::class, mappedBy: 'categories')]
    private $cartoons;

    #[ORM\ManyToMany(targetEntity: Series::class, mappedBy: 'categories')]
    private $series;

    #[ORM\ManyToMany(targetEntity: Film::class, mappedBy: 'categories')]
    private $films;

    #[ORM\ManyToMany(targetEntity: ClassicalFilm::class, mappedBy: 'categories')]
    private $classicalFilms;

    public function __construct()
    {
        $this->cartoons = new ArrayCollection();
        $this->series = new ArrayCollection();
        $this->films = new ArrayCollection();
        $this->classicalFilms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    // Add/Remove for cartoons
    public function getCartoons(): Collection
    {
        return $this->cartoons;
    }

    public function addCartoon(Cartoon $cartoon): self
    {
        if (!$this->cartoons->contains($cartoon)) {
            $this->cartoons[] = $cartoon;
            $cartoon->addCategory($this);
        }

        return $this;
    }

    public function removeCartoon(Cartoon $cartoon): self
    {
        if ($this->cartoons->removeElement($cartoon)) {
            $cartoon->removeCategory($this);
        }

        return $this;
    }

    // Add/Remove for series
    public function getSeries(): Collection
    {
        return $this->series;
    }

    public function addSeries(Series $series): self
    {
        if (!$this->series->contains($series)) {
            $this->series[] = $series;
            $series->addCategory($this);
        }

        return $this;
    }

    public function removeSeries(Series $series): self
    {
        if ($this->series->removeElement($series)) {
            $series->removeCategory($this);
        }

        return $this;
    }

    // Add/Remove for films
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function addFilm(Film $film): self
    {
        if (!$this->films->contains($film)) {
            $this->films[] = $film;
            $film->addCategory($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): self
    {
        if ($this->films->removeElement($film)) {
            $film->removeCategory($this);
        }

        return $this;
    }

    // Add/Remove for classicalFilms
    public function getClassicalFilms(): Collection
    {
        return $this->classicalFilms;
    }

    public function addClassicalFilm(ClassicalFilm $classicalFilm): self
    {
        if (!$this->classicalFilms->contains($classicalFilm)) {
            $this->classicalFilms[] = $classicalFilm;
            $classicalFilm->addCategory($this);
        }

        return $this;
    }

    public function removeClassicalFilm(ClassicalFilm $classicalFilm): self
    {
        if ($this->classicalFilms->removeElement($classicalFilm)) {
            $classicalFilm->removeCategory($this);
        }

        return $this;
    }
}
