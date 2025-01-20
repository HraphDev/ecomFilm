<?php

namespace App\Controller;

use App\Entity\Cartoon;
use App\Entity\Category;
use App\Entity\Director;
use App\Repository\CategoryRepository;
use App\Repository\DirectorRepository;
use App\Repository\CartoonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CartoonsController extends AbstractController
{
    // List all cartoons
    #[Route('/cartoons', name: 'cartoon_index')]
    public function index(CartoonRepository $cartoonRepository ,CategoryRepository $categoryRepo ): Response
    {
        // Get all cartoons
        $cartoons = $cartoonRepository->findAll();
        $categories = $categoryRepo->findAll();
        return $this->render('admin/cartoons.html.twig', [
            'cartoons' => $cartoons,
            'categories' => $categories
        ]);
    }

    // Create a new cartoon
    #[Route('/admin/cartoons/create', name: 'cartoon_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        if ($request->isMethod('POST')) {
            // Create a new cartoon object
            $cartoon = new Cartoon();
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $releaseDate = new \DateTime($request->request->get('releaseDate'));
            $price = $request->request->get('price');

            // Handle Categories (ensure it's always an array)
            $categoryIds = $request->request->get('categories');
            $categoryIds = is_array($categoryIds) ? $categoryIds : (array)$categoryIds;
            foreach ($categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $cartoon->addCategory($category);
                }
            }

            // Handle Directors (ensure it's always an array)
            $directorIds = $request->request->get('directors');
            $directorIds = is_array($directorIds) ? $directorIds : (array)$directorIds;
            foreach ($directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $cartoon->addDirector($director);
                }
            }

            // Handle file uploads for image and video
            $imageFile = $request->files->get('imagePath');
            $videoFile = $request->files->get('videoPath');

            if ($imageFile) {
                $imagePath = 'uploads/' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('upload_directory'), $imagePath);
                $cartoon->setImagePath($imagePath);
            }

            if ($videoFile) {
                $videoPath = 'uploads/' . uniqid() . '.' . $videoFile->guessExtension();
                $videoFile->move($this->getParameter('upload_directory'), $videoPath);
                $cartoon->setVideoPath($videoPath);
            }

            // Set the basic cartoon details
            $cartoon->setTitle($title)
                ->setDescription($description)
                ->setReleaseDate($releaseDate)
                ->setPrice($price);

            // Persist the cartoon to the database
            $entityManager->persist($cartoon);
            $entityManager->flush();

            // Redirect to the cartoon's index page
            return $this->redirectToRoute('cartoon_index');
        }

        // Fetch all categories and directors for the form
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();

        return $this->render('admin/cartoons/create.html.twig', [
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }

    // Edit an existing cartoon
    #[Route('/admin/cartoons/{id}/edit', name: 'cartoon_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        $cartoon = $entityManager->getRepository(Cartoon::class)->find($id);

        if (!$cartoon) {
            throw new NotFoundHttpException('Cartoon not found');
        }

        if ($request->isMethod('POST')) {
            // Update cartoon details
            $cartoon->setTitle($request->request->get('title'))
                ->setDescription($request->request->get('description'))
                ->setReleaseDate(new \DateTime($request->request->get('releaseDate')))
                ->setPrice($request->request->get('price'));

            // Clear existing categories and directors before adding new ones
            $cartoon->getCategories()->clear();
            $cartoon->getDirectors()->clear();

            // Handle Categories
            $categoryIds = $request->request->get('categories');
            $categoryIds = is_array($categoryIds) ? $categoryIds : (array)$categoryIds;
            foreach ($categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $cartoon->addCategory($category);
                }
            }

            // Handle Directors
            $directorIds = $request->request->get('directors');
            $directorIds = is_array($directorIds) ? $directorIds : (array)$directorIds;
            foreach ($directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $cartoon->addDirector($director);
                }
            }

            // Handle file uploads
            $imageFile = $request->files->get('imagePath');
            $videoFile = $request->files->get('videoPath');

            if ($imageFile) {
                $imagePath = 'uploads/' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('upload_directory'), $imagePath);
                $cartoon->setImagePath($imagePath);
            }

            if ($videoFile) {
                $videoPath = 'uploads/' . uniqid() . '.' . $videoFile->guessExtension();
                $videoFile->move($this->getParameter('upload_directory'), $videoPath);
                $cartoon->setVideoPath($videoPath);
            }

            // Persist the updated cartoon
            $entityManager->flush();

            // Redirect to the cartoon's index page
            return $this->redirectToRoute('cartoon_index');
        }

        // Fetch categories and directors for the form
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();

        return $this->render('admin/cartoon/edit.html.twig', [
            'cartoon' => $cartoon,
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }

    // Delete a cartoon
    #[Route('/admin/cartoons/{id}/delete', name: 'cartoon_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $cartoon = $entityManager->getRepository(Cartoon::class)->find($id);

        if (!$cartoon) {
            throw new NotFoundHttpException('Cartoon not found');
        }

        $entityManager->remove($cartoon);
        $entityManager->flush();

        return $this->redirectToRoute('cartoon_index');
    }
}
