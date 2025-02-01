<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\RegistrationFormType;

final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository
    ): Response {
        $categories = $categoryRepository->findAll();

        $categoriesData = $this->getCategoriesData($categories);

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registration successful! You can now log in.');

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'moviesCategories' => $categoriesData['moviesCategories'],
            'cartoonsCategories' => $categoriesData['cartoonsCategories'],
            'seriesCategories' => $categoriesData['seriesCategories'],
            'classicalFilmsCategories' => $categoriesData['classicalFilmsCategories'],
        ]);
    }

    /**
     * Organize categories into groups based on their content.
     *
     * @param array $categories
     * @return array
     */
    private function getCategoriesData(array $categories): array
    {
        $moviesCategories = array_filter($categories, function ($category) {
            return method_exists($category, 'getFilms') && count($category->getFilms()) > 0;
        });

        $cartoonsCategories = array_filter($categories, function ($category) {
            return method_exists($category, 'getCartoons') && count($category->getCartoons()) > 0;
        });

        $seriesCategories = array_filter($categories, function ($category) {
            return method_exists($category, 'getSeries') && count($category->getSeries()) > 0;
        });

        $classicalFilmsCategories = array_filter($categories, function ($category) {
            return method_exists($category, 'getClassicalFilms') && count($category->getClassicalFilms()) > 0;
        });

        return [
            'moviesCategories' => $moviesCategories,
            'cartoonsCategories' => $cartoonsCategories,
            'seriesCategories' => $seriesCategories,
            'classicalFilmsCategories' => $classicalFilmsCategories,
        ];
    }
}
