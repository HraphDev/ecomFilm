<?php

namespace App\Controller;
use App\Entity\Series;
use App\Entity\ClassicalFilm;
use App\Entity\Film;
use App\Entity\Cartoon;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SeriesRepository;
use App\Repository\CartoonRepository;
use App\Repository\ClassicalFilmRepository;
use App\Repository\CategoryRepository;
class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/movies', name: 'admin_movies')]
    public function movies(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();

        // Pass films to the template
        return $this->render('admin/movies.html.twig', [
            'films' => $films,  // This line ensures films are passed to the Twig template
        ]);
    }

    #[Route('/admin/cartoons', name: 'admin_cartoons')]
    public function cartoons(CartoonRepository $cartoonRepository ,CategoryRepository $categoryRepo ): Response
    {
        // Get all cartoons
        $cartoons = $cartoonRepository->findAll();
        $categories = $categoryRepo->findAll();
        return $this->render('admin/cartoons.html.twig', [
            'cartoons' => $cartoons,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/series', name: 'admin_series')]
    public function series(SeriesRepository $seriesRepository): Response
    {
        $seriesList = $seriesRepository->findAll();
    
        return $this->render('admin/series.html.twig', [
            'series' => $seriesList,
        ]);
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function users(UserRepository $userRepository): Response
    {
           // Fetch all users from the database
           $users =  $userRepository->findAll();

           // Render the user list in the 'admin/users.html.twig' template
           return $this->render('admin/users.html.twig', [
               'users' => $users,
           ]);
    }

    #[Route('/admin/classical_films', name: 'admin_classical_films')]
    public function classicalFilms(ClassicalFilmRepository  $classicalFilmRepository ): Response
    {
        // Get all classical films
        $films = $classicalFilmRepository->findAll();

        // Render the films index page
        return $this->render('admin/classical_films.html.twig', [
            'films' => $films,
        ]);
    }
}
