<?php

// src/Controller/Admin/FilmController.php
namespace App\Controller;

use App\Entity\Film;
use App\Entity\Category;
use App\Entity\Director;
use App\Repository\CategoryRepository;
use App\Repository\DirectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\FilmRepository;

class FilmController extends AbstractController
{
    #[Route(path: "/admin/movies", name: "admin_movies")]
    public function index(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();

        // Pass films to the template
        return $this->render('admin/movies.html.twig', [
            'films' => $films,  // This line ensures films are passed to the Twig template
        ]);
    }
    #[Route(path: "/admin/films/create", name: "admin_films_create")]
    public function create(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo,  DirectorRepository $directorRepo): Response
    {
        if ($request->isMethod('POST')) {
            $film = new Film();
            $film->setTitle($request->request->get('title'));
            $film->setDescription($request->request->get('description'));
            $film->setReleaseDate(new \DateTime($request->request->get('releaseDate')));
            $film->setPrice((float)$request->request->get('price'));

            // Handle Categories (ensure it's always an array)
            $categoryIds = $request->request->get('categories');
            $categoryIds = is_array($categoryIds) ? $categoryIds : (array)$categoryIds;
            foreach ($categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $film->addCategory($category);
                }
            }

           

            // Handle Directors (ensure it's always an array)
            $directorIds = $request->request->get('directors');
            $directorIds = is_array($directorIds) ? $directorIds : (array)$directorIds;
            foreach ($directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $film->addDirector($director);
                }
            }

            // Handle image and video paths (upload them if present)
            $imagePath = $request->files->get('imagePath');
            $videoPath = $request->files->get('videoPath');

            if ($imagePath) {
                $film->setImagePath($imagePath->getClientOriginalName()); // Save only file name
            }
            if ($videoPath) {
                $film->setVideoPath($videoPath->getClientOriginalName()); // Save only file name
            }

            // Save the film entity to the database
            $entityManager->persist($film);
            $entityManager->flush();

            $this->addFlash('success', 'Film created successfully!');
            return $this->redirectToRoute('admin_movies');
        }

        // Fetch all categories, actors, and directors for the form
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();

        return $this->render('admin/films/create.html.twig', [
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }
    #[Route(path: "/admin/films/edit/{id}", name: "admin_films_edit")]
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepo,
        DirectorRepository $directorRepo
    ): Response {
        $film = $entityManager->getRepository(Film::class)->find($id);
    
        if (!$film) {
            $this->addFlash('error', 'Film not found!');
            return $this->redirectToRoute('admin_movies');
        }
    
        if ($request->isMethod('POST')) {
            $film->setTitle($request->request->get('title'));
            $film->setDescription($request->request->get('description'));
            $film->setReleaseDate(new \DateTime($request->request->get('releaseDate')));
            $film->setPrice((float)$request->request->get('price'));
    
       // Handle Categories
$categoryIds = $request->request->all('categories');
if (!$categoryIds) {
    $categoryIds = [];
}
$film->getCategories()->clear();
foreach ($categoryIds as $categoryId) {
    $category = $categoryRepo->find($categoryId);
    if ($category) {
        $film->addCategory($category);
    }
}

// Handle Directors
$directorIds = $request->request->all('directors');
if (!$directorIds) {
    $directorIds = [];
}
$film->getDirectors()->clear();
foreach ($directorIds as $directorId) {
    $director = $directorRepo->find($directorId);
    if ($director) {
        $film->addDirector($director);
    }
}

            // Handle image and video paths (upload them if present)
            $imagePath = $request->files->get('imagePath');
            $videoPath = $request->files->get('videoPath');
    
            if ($imagePath) {
                $uploadDir = 'uploads/images';
                $newFilename = uniqid() . '.' . $imagePath->guessExtension();
                $imagePath->move($uploadDir, $newFilename);
                $film->setImagePath($newFilename);
            }
    
            if ($videoPath) {
                $uploadDir = 'uploads/videos';
                $newFilename = uniqid() . '.' . $videoPath->guessExtension();
                $videoPath->move($uploadDir, $newFilename);
                $film->setVideoPath($newFilename);
            }
    
            // Save the updated film entity to the database
            $entityManager->flush();
    
            $this->addFlash('success', 'Film updated successfully!');
            return $this->redirectToRoute('admin_movies');
        }
    
        // Fetch all categories and directors for the form
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();
    
        return $this->render('admin/films/edit.html.twig', [
            'film' => $film,
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }
    #[Route(path: "/admin/films/delete/{id}", name: "admin_films_delete")]
public function delete(int $id, EntityManagerInterface $entityManager): Response
{
    $film = $entityManager->getRepository(Film::class)->find($id);

    if (!$film) {
        $this->addFlash('error', 'Film not found!');
        return $this->redirectToRoute('admin_movies');
    }

    $entityManager->remove($film);
    $entityManager->flush();

    $this->addFlash('success', 'Film deleted successfully!');
    return $this->redirectToRoute('admin_movies');
}

}    