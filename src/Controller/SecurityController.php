<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\CategoryRepository;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $categoriesData = $this->getCategoriesData($categories);

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'categories' => $categories,
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'], 
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall.');
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
}
