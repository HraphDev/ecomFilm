<?php

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

        return $this->render('admin/movies.html.twig', [
            'films' => $films,
        ]);
    }

    #[Route(path: "/admin/films/create", name: "admin_films_create")]
    public function create(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        if ($request->isMethod('POST')) {
            $film = new Film();
            $film->setTitle($request->request->get('title'));
            $film->setDescription($request->request->get('description'));
            $film->setReleaseDate(new \DateTime($request->request->get('releaseDate')));
            $film->setPrice((float)$request->request->get('price'));

            $categoryIds = $request->request->all('categories') ?? [];
            foreach ((array)$categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $film->addCategory($category);
                }
            }

            $directorIds = $request->request->all('directors') ?? [];
            foreach ((array)$directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $film->addDirector($director);
                }
            }

            $imagePath = $request->files->get('imagePath');
            $videoPath = $request->files->get('videoPath');
            if ($imagePath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/images/';
                $newFilename = uniqid() . '.' . $imagePath->guessExtension();
                $imagePath->move($uploadDir, $newFilename);
                $film->setImagePath('uploads/images/' . $newFilename);
            }
            
            $videoPath = $request->files->get('videoPath');
            if ($videoPath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/videos/';
                $newFilename = uniqid() . '.' . $videoPath->guessExtension();
                $videoPath->move($uploadDir, $newFilename);
                $film->setVideoPath('uploads/videos/' . $newFilename);
            }

            

            $entityManager->persist($film);
            $entityManager->flush();

            $this->addFlash('success', 'Film created successfully!');
            return $this->redirectToRoute('admin_movies');
        }

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

            $categoryIds = $request->request->all('categories') ?? [];
            $film->getCategories()->clear();
            foreach ((array)$categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $film->addCategory($category);
                }
            }

            $directorIds = $request->request->all('directors') ?? [];
            $film->getDirectors()->clear();
            foreach ((array)$directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $film->addDirector($director);
                }
            }

            $imagePath = $request->files->get('imagePath');
            $videoPath = $request->files->get('videoPath');
            if ($imagePath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/images/';
                $newFilename = uniqid() . '.' . $imagePath->guessExtension();
                $imagePath->move($uploadDir, $newFilename);
                $film->setImagePath('uploads/images/' . $newFilename);
            }
            
            $videoPath = $request->files->get('videoPath');
            if ($videoPath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/videos/';
                $newFilename = uniqid() . '.' . $videoPath->guessExtension();
                $videoPath->move($uploadDir, $newFilename);
                $film->setVideoPath('uploads/videos/' . $newFilename);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Film updated successfully!');
            return $this->redirectToRoute('admin_movies');
        }

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
