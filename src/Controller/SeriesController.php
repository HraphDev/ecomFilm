<?php

namespace App\Controller;

use App\Entity\Series;
use App\Entity\Category;
use App\Entity\Actor;
use App\Entity\Director;
use App\Repository\CategoryRepository;
use App\Repository\DirectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\SeriesRepository;
final class SeriesController extends AbstractController
{
    #[Route('/series', name: 'series_index')]
    public function index(SeriesRepository $seriesRepository): Response
    {
        $seriesList = $seriesRepository->findAll();
    
        return $this->render('admin/series.html.twig', [
            'series' => $seriesList,
        ]);
    }

    #[Route('/series/create', name: 'series_create')]
    public function create(
        Request $request,
        CategoryRepository $categoryRepo,
        DirectorRepository $directorRepo,
        EntityManagerInterface $em
    ): Response {
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();

        if ($request->isMethod('POST')) {
            $series = new Series();
            $series->setTitle($request->request->get('title'))
                   ->setSeasons($request->request->get('seasons'))
                   ->setEpisodes($request->request->get('episodes'))
                   ->setPrice($request->request->get('price'))
                   ->setReleaseDate(new \DateTime($request->request->get('releaseDate')))
                   ->setDescription($request->request->get('description'));

              // Handle Categories
$categoryIds = $request->request->all('categories');
if (!$categoryIds) {
    $categoryIds = [];
}
$series->getCategories()->clear();
foreach ($categoryIds as $categoryId) {
    $category = $categoryRepo->find($categoryId);
    if ($category) {
        $series->addCategory($category);
    }
}

// Handle Directors
$directorIds = $request->request->all('directors');
if (!$directorIds) {
    $directorIds = [];
}
$series->getDirectors()->clear();
foreach ($directorIds as $directorId) {
    $director = $directorRepo->find($directorId);
    if ($director) {
        $series->addDirector($director);
    }
}

            // Handle file uploads (image and video)
            if ($videoFile = $request->files->get('video')) {
                $videoPath = $this->handleFileUpload($videoFile, 'videos');
                $series->setVideoPath($videoPath);
            }

            if ($imageFile = $request->files->get('image')) {
                $imagePath = $this->handleFileUpload($imageFile, 'images');
                $series->setImagePath($imagePath);
            }

            // Persist the entity
            $em->persist($series);
            $em->flush();

            return $this->redirectToRoute('series_index');
        }

        return $this->render('admin/series/create.html.twig', [
            'categories' => $categories,
            'directors' => $directors,
            'series' => new Series(),
        ]);
    }

    #[Route('/series/edit/{id}', name: 'series_edit')]
    public function edit(
        Request $request,
        Series $series,
        CategoryRepository $categoryRepo,
        DirectorRepository $directorRepo,
        EntityManagerInterface $em
    ): Response {
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();

        if ($request->isMethod('POST')) {
            $series->setTitle($request->request->get('title'))
                   ->setSeasons($request->request->get('seasons'))
                   ->setEpisodes($request->request->get('episodes'))
                   ->setPrice($request->request->get('price'))
                   ->setReleaseDate(new \DateTime($request->request->get('releaseDate')))
                   ->setDescription($request->request->get('description'));

          // Handle Categories
$categoryIds = $request->request->all('categories');
if (!$categoryIds) {
    $categoryIds = [];
}
$series->getCategories()->clear();
foreach ($categoryIds as $categoryId) {
    $category = $categoryRepo->find($categoryId);
    if ($category) {
        $series->addCategory($category);
    }
}

// Handle Directors
$directorIds = $request->request->all('directors');
if (!$directorIds) {
    $directorIds = [];
}
$series->getDirectors()->clear();
foreach ($directorIds as $directorId) {
    $director = $directorRepo->find($directorId);
    if ($director) {
        $series->addDirector($director);
    }
}


            // Handle file uploads (image and video)
            if ($videoFile = $request->files->get('video')) {
                $videoPath = $this->handleFileUpload($videoFile, 'videos');
                $series->setVideoPath($videoPath);
            }

            if ($imageFile = $request->files->get('image')) {
                $imagePath = $this->handleFileUpload($imageFile, 'images');
                $series->setImagePath($imagePath);
            }

            // Persist changes
            $em->flush();

            return $this->redirectToRoute('series_index');
        }

        return $this->render('admin/series/edit.html.twig', [
            'series' => $series,
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }

    #[Route('/series/delete/{id}', name: 'series_delete')]
    public function delete(Series $series, EntityManagerInterface $em): Response
    {
        $em->remove($series);
        $em->flush();

        return $this->redirectToRoute('series_index');
    }

    // Helper function to handle file uploads
    private function handleFileUpload($file, $directory): ?string
    {
        // Generate a unique filename
        $filename = uniqid() . '.' . $file->guessExtension();

        try {
            // Move the file to the designated directory
            $file->move(
                $this->getParameter('upload_directory') . '/' . $directory,
                $filename
            );
        } catch (FileException $e) {
            // Handle the error appropriately
            $this->addFlash('error', 'Failed to upload the file.');
            return null;
        }

        // Return the file path for database storage
        return '/uploads/' . $directory . '/' . $filename;
    }
}
