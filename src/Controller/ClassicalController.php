<?php

namespace App\Controller;

use App\Entity\ClassicalFilm;
use App\Entity\Category;
use App\Entity\Director;
use App\Repository\CategoryRepository;
use App\Repository\DirectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\ClassicalFilmRepository;

class ClassicalController extends AbstractController
{
    // List all classical films
    #[Route('/classical', name: 'classical_index')]
    public function index(ClassicalFilmRepository $classicalFilmRepository): Response
    {
        // Get all classical films
        $films = $classicalFilmRepository->findAll();

        return $this->render('admin/classical_films.html.twig', [
            'films' => $films,
        ]);
    }

    // Create a create classical film
    #[Route('/admin/classical/new', name: 'classical_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        // Create an empty ClassicalFilm object
        $film = new ClassicalFilm();
    
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $releaseDate = new \DateTime($request->request->get('releaseDate'));
            $price = $request->request->get('price');
    
            // Handle file uploads for image and video
            $imageFile = $request->files->get('imagePath');
            $videoFile = $request->files->get('videoPath');
    
            if ($imageFile) {
                $imagePath = 'uploads/' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('upload_directory'), $imagePath);
                $film->setImagePath($imagePath);
            }
    
            if ($videoFile) {
                $videoPath = 'uploads/' . uniqid() . '.' . $videoFile->guessExtension();
                $videoFile->move($this->getParameter('upload_directory'), $videoPath);
                $film->setVideoPath($videoPath);
            }
    
            // Set the basic film details
            $film->setTitle($title)
                ->setDescription($description)
                ->setReleaseDate($releaseDate)
                ->setPrice($price);
    
            // Handle categories
            $categoryIds = $request->request->get('categories');
            if (is_array($categoryIds)) {
                foreach ($categoryIds as $categoryId) {
                    $category = $categoryRepo->find($categoryId);
                    if ($category) {
                        $film->addCategory($category);
                    }
                }
            }
    
            // Handle directors
            $directorIds = $request->request->get('directors');
            if (is_array($directorIds)) {
                foreach ($directorIds as $directorId) {
                    $director = $directorRepo->find($directorId);
                    if ($director) {
                        $film->addDirector($director);
                    }
                }
            }
    
            // Persist the film to the database
            $entityManager->persist($film);
            $entityManager->flush();
    
            // Redirect to the film's detail page
            return $this->redirectToRoute('classical_show', ['id' => $film->getId()]);
        }
    
        // Fetch all categories and directors for the form
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();
    
        // Pass the empty film object to the template
        return $this->render('admin/classic/create.html.twig', [
            'film' => $film,
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }
    

    // Edit an existing classical film
    #[Route('/admin/classical/{id}/edit', name: 'classical_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        $film = $entityManager->getRepository(ClassicalFilm::class)->find($id);

        if (!$film) {
            throw new NotFoundHttpException('Film not found');
        }

        if ($request->isMethod('POST')) {
            // Update film details
            $film->setTitle($request->request->get('title'))
                ->setDescription($request->request->get('description'))
                ->setReleaseDate(new \DateTime($request->request->get('releaseDate')))
                ->setPrice($request->request->get('price'));

            // Handle file uploads
            $imageFile = $request->files->get('imagePath');
            $videoFile = $request->files->get('videoPath');

            if ($imageFile) {
                $imagePath = 'uploads/' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('upload_directory'), $imagePath);
                $film->setImagePath($imagePath);
            }

            if ($videoFile) {
                $videoPath = 'uploads/' . uniqid() . '.' . $videoFile->guessExtension();
                $videoFile->move($this->getParameter('upload_directory'), $videoPath);
                $film->setVideoPath($videoPath);
            }

            // Update categories
            $categoryIds = $request->request->get('categories');
            if (is_array($categoryIds)) {
                foreach ($categoryIds as $categoryId) {
                    $category = $categoryRepo->find($categoryId);
                    if ($category) {
                        $film->addCategory($category);
                    }
                }
            }

            // Update directors
            $directorIds = $request->request->get('directors');
            if (is_array($directorIds)) {
                foreach ($directorIds as $directorId) {
                    $director = $directorRepo->find($directorId);
                    if ($director) {
                        $film->addDirector($director);
                    }
                }
            }

            // Persist the updated film
            $entityManager->flush();

            // Redirect to the film's detail page
            return $this->redirectToRoute('classical_show', ['id' => $film->getId()]);
        }

        // Fetch categories and directors for the form
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();

        return $this->render('admin/classic/edit.html.twig', [
            'film' => $film,
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }

    // Delete a classical film
    #[Route('admin/classical/{id}/delete', name: 'classical_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $film = $entityManager->getRepository(ClassicalFilm::class)->find($id);

        if (!$film) {
            throw new NotFoundHttpException('Film not found');
        }

        $entityManager->remove($film);
        $entityManager->flush();

        return $this->redirectToRoute('classical_index');
    }
}
