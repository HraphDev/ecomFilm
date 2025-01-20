<?php

// src/Controller/CategoryController.php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        // Retrieve all categories
        $categories = $categoryRepository->findAll();

        return $this->render('/admin/category.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/create', name: 'category_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        if ($request->isMethod('POST')) {
            // Manually process form data
            $category->setName($request->request->get('name'));

            // Save the entity
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('/admin/category/create.html.twig');
    }

    #[Route('/admin/category/edit/{id}', name: 'category_edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Manually process form data
            $category->setName($request->request->get('name'));

            // Save the updated entity
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('/admin/category/edit.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/admin/category/delete/{id}', name: 'category_delete')]
    public function delete(Category $category, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($category);  // Remove the category from the database
        $entityManager->flush();            // Commit the changes to the database
    
        return $this->redirectToRoute('category_index');  // Redirect back to the category listing page
    }
}
