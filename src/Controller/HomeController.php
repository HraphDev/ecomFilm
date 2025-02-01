<?php

namespace App\Controller;

use App\Entity\Cartoon;
use App\Entity\ClassicalFilm;
use App\Entity\Film;
use App\Entity\Series;
use App\Repository\CartoonRepository;
use App\Repository\ClassicalFilmRepository;
use App\Repository\FilmRepository;
use App\Repository\SeriesRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        FilmRepository $filmRepository,
        CategoryRepository $categoryRepository,
        CartoonRepository $cartoonRepository,
        SeriesRepository $seriesRepository,
        ClassicalFilmRepository $classicalFilmRepository
    ): Response {
       
        $films = $filmRepository->findAll();
        $categories = $categoryRepository->findAll();
        $cartoons = $cartoonRepository->findAll();
        $series = $seriesRepository->findAll();
        $classicalFilms = $classicalFilmRepository->findAll();
    
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render('home/index.html.twig', [
            'films' => $films,
            'cartoons' => $cartoons,
            'series' => $series,
            'classicalFilms' => $classicalFilms,
            'categories' => $categories,
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
        ]);
    }

    #[Route('/movies/category/{categoryName}', name: 'movies_category')]
    public function moviesByCategory(
        string $categoryName,
        FilmRepository $filmRepository,
        CategoryRepository $categoryRepository
    ): Response {
  
        $films = $filmRepository->findByCategoryName($categoryName);
        $categories = $categoryRepository->findAll();

    
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render('movies/category.html.twig', [
            'films' => $films,
            'category' => $categoryName,
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
        ]);
    }

    #[Route('/cartoons/category/{categoryName}', name: 'cartoons_category')]
    public function cartoonsByCategory(
        string $categoryName,
        CartoonRepository $cartoonRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $cartoons = $cartoonRepository->findByCategoryName($categoryName);
        $categories = $categoryRepository->findAll();
    
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render('cartoons/category.html.twig', [
            'cartoons' => $cartoons,
            'category' => $categoryName,
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
        ]);
    }

    #[Route('/series/category/{categoryName}', name: 'series_category')]
    public function seriesByCategory(
        string $categoryName,
        SeriesRepository $seriesRepository,
        CategoryRepository $categoryRepository
    ): Response {
       
        $series = $seriesRepository->findByCategoryName($categoryName);
        $categories = $categoryRepository->findAll();
    
        
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render('series/category.html.twig', [
            'series' => $series,
            'category' => $categoryName,
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
        ]);
    }

    #[Route('/classicalfilms/category/{categoryName}', name: 'classicalfilms_category')]
 
public function classicalFilmsByCategory(
    string $categoryName,
    ClassicalFilmRepository $classicalFilmRepository,
    CategoryRepository $categoryRepository
): Response {
   
    $classicalFilms = $classicalFilmRepository->findByCategoryName($categoryName);
    $categories = $categoryRepository->findAll();

   
    $categoriesData = $this->getCategoriesData($categories);

    return $this->render('classical/category.html.twig', [
        'classicalFilms' => $classicalFilms,
        'category' => $categoryName,
        'moviesCategories' => $categoriesData['moviesCategories'],
        'cartoonsCategories' => $categoriesData['cartoonsCategories'],
        'seriesCategories' => $categoriesData['seriesCategories'],
        'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
    ]);
}


    #[Route('/movies', name: 'movies_page')]
    public function moviesPage(FilmRepository $filmRepository, CategoryRepository $categoryRepository): Response
    {
        
        $films = $filmRepository->findAll();
        $categories = $categoryRepository->findAll();
    
       
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render('movies/index.html.twig', [
            'films' => $films,
            'categories' => $categories,
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'],
        ]);
    }

    #[Route('/cartoons', name: 'cartoons_page')]
    public function cartoonsPage(CartoonRepository $cartoonRepository, CategoryRepository $categoryRepository): Response
    {

        $cartoons = $cartoonRepository->findAll();
        $categories = $categoryRepository->findAll();
    
 
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render('cartoons/index.html.twig', [
            'cartoons' => $cartoons,
            'categories' => $categories,
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'moviesCategories' => $categoriesData['moviesCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'],
        ]);
    }

    #[Route('/series', name: 'series_page')]
    public function seriesPage(SeriesRepository $seriesRepository, CategoryRepository $categoryRepository): Response
    {
   
        $series = $seriesRepository->findAll();
        $categories = $categoryRepository->findAll();
    
     
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render('series/index.html.twig', [
            'series' => $series,
            'categories' => $categories,
            'seriesCategories' => $categoriesData['seriesCategories'],
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
        ]);
    }

    #[Route('/classicalfilms', name: 'classicalfilms_page')]
    public function classicalFilmsPage(ClassicalFilmRepository $classicalFilmRepository, CategoryRepository $categoryRepository): Response
    {
        $classicalfilms = $classicalFilmRepository->findAll();
        $categories = $categoryRepository->findAll();
    
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render('classical/index.html.twig', [
            'classicalfilms' => $classicalfilms,
            'categories' => $categories,
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'],
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $categoriesData = $this->getCategoriesData($categories);

        return $this->render('home/about.html.twig', [
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
        ]);
    }

    #[Route('/team', name: 'team')]
    public function ourTeam(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $categoriesData = $this->getCategoriesData($categories);

        return $this->render('home/team.html.twig', [
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
        ]);
    }

    private function getCategoriesData(array $categories): array
    {
        $moviesCategories = array_filter($categories, function($category) {
            return count($category->getFilms()) > 0;
        });

        $cartoonsCategories = array_filter($categories, function($category) {
            return count($category->getCartoons()) > 0;
        });

        $seriesCategories = array_filter($categories, function($category) {
            return count($category->getSeries()) > 0;
        });

        $classicalFilmsCategories = array_filter($categories, function($category) {
            return count($category->getClassicalFilms()) > 0;
        });

        return [
            'moviesCategories' => $moviesCategories,
            'cartoonsCategories' => $cartoonsCategories,
            'seriesCategories' => $seriesCategories,
            'classicalFilmsCategories' => $classicalFilmsCategories,
        ];
    }



    #[Route('/film/{id}', name: 'show_movie')]
    public function showMovie(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        return $this->showEntity('movies', Film::class, $id, 'movies/show.html.twig', $entityManager, $categoryRepository);
    }

    #[Route('/series/{id}', name: 'show_series')]
    public function showSeries(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        return $this->showEntity('series', Series::class, $id, 'series/show.html.twig', $entityManager, $categoryRepository);
    }

    #[Route('/classicalfilm/{id}', name: 'show_classicalfilm')]
    public function showClassicalFilm(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        return $this->showEntity('classicalfilms', ClassicalFilm::class, $id, 'classical/show.html.twig', $entityManager, $categoryRepository);
    }

    #[Route('/cartoons/{id}', name: 'show_cartoon')]
    public function showCartoon(int $id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        return $this->showEntity('cartoons', Cartoon::class, $id, 'cartoons/show.html.twig', $entityManager, $categoryRepository);
    }

    private function showEntity(  string $categoryName, string $entityClass, int $id, string $template, EntityManagerInterface $entityManager , CategoryRepository $categoryRepository): Response
    {
        $entity = $entityManager->getRepository($entityClass)->find($id);
    
        if (!$entity) {
            throw $this->createNotFoundException("No entity found for id $id");
        }
    
        $categories = $categoryRepository->findAll();
    
        $categoriesData = $this->getCategoriesData($categories);
    
        return $this->render($template, [
            'entity' => $entity,
            'category' => $categoryName,
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
        ]);
    }
    
}
