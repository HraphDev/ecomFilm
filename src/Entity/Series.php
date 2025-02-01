<?php

namespace App\Entity;

use App\Repository\SeriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeriesRepository::class)]
class Series
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $seasons;

    #[ORM\Column(type: 'integer')]
    private $episodes;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'date')]
    private $releaseDate;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private $imagePath;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private $videoPath;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'series')]
    #[ORM\JoinTable(name: 'series_category')]
    private $categories;

    #[ORM\ManyToMany(targetEntity: Director::class, inversedBy: 'series')]
    #[ORM\JoinTable(name: 'series_director')]
    private $directors;

 
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->directors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getSeasons(): ?int
    {
        return $this->seasons;
    }

    public function setSeasons(int $seasons): self
    {
        $this->seasons = $seasons;
        return $this;
    }

    public function getEpisodes(): ?int
    {
        return $this->episodes;
    }

    public function setEpisodes(int $episodes): self
    {
        $this->episodes = $episodes;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function getVideoPath(): ?string
    {
        return $this->videoPath;
    }

    public function setVideoPath(?string $videoPath): self
    {
        $this->videoPath = $videoPath;
        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addSeries($this); 
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeSeries($this);
        }

        return $this;
    }

    public function getDirectors(): Collection
    {
        return $this->directors;
    }

    public function addDirector(Director $director): self
    {
        if (!$this->directors->contains($director)) {
            $this->directors[] = $director;
        }

        return $this;
    }

    public function removeDirector(Director $director): self
    {
        $this->directors->removeElement($director);
        return $this;
    }

   
}
