<?php

namespace App\Controller;

use App\Entity\Series;
use App\Repository\CategoryRepository;
use App\Repository\DirectorRepository;
use App\Repository\SeriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SeriesController extends AbstractController
{
    #[Route(path: "/admin/series", name: "admin_series")]
    public function index(SeriesRepository $seriesRepository): Response
    {
        $series = $seriesRepository->findAll();

        return $this->render('admin/series.html.twig', [
            'series' => $series,
        ]);
    }

    #[Route(path: "/admin/series/create", name: "admin_series_create")]
    public function create(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        if ($request->isMethod('POST')) {
            $series = new Series();
            $series->setTitle($request->request->get('title'));
            $series->setDescription($request->request->get('description'));
            $series->setReleaseDate(new \DateTime($request->request->get('releaseDate')));
            $series->setPrice((float)$request->request->get('price'));
            $series->setSeasons((int)$request->request->get('seasons'));
            $series->setEpisodes((int)$request->request->get('episodes'));

            $categoryIds = $request->request->all('categories') ?? [];
            foreach ((array)$categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $series->addCategory($category);
                }
            }

            $directorIds = $request->request->all('directors') ?? [];
            foreach ((array)$directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $series->addDirector($director);
                }
            }

            $imagePath = $request->files->get('imagePath');
            if ($imagePath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/images/';
                $newFilename = uniqid() . '.' . $imagePath->guessExtension();
                $imagePath->move($uploadDir, $newFilename);
                $series->setImagePath('uploads/images/' . $newFilename);
            }

            $videoPath = $request->files->get('videoPath');
            if ($videoPath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/videos/';
                $newFilename = uniqid() . '.' . $videoPath->guessExtension();
                $videoPath->move($uploadDir, $newFilename);
                $series->setVideoPath('uploads/videos/' . $newFilename);
            }

            $entityManager->persist($series);
            $entityManager->flush();

            $this->addFlash('success', 'Series created successfully!');
            return $this->redirectToRoute('admin_series');
        }

        return $this->render('admin/series/create.html.twig', [
            'categories' => $categoryRepo->findAll(),
            'directors' => $directorRepo->findAll(),
        ]);
    }

    #[Route(path: "/admin/series/edit/{id}", name: "admin_series_edit")]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        $series = $entityManager->getRepository(Series::class)->find($id);

        if (!$series) {
            $this->addFlash('error', 'Series not found!');
            return $this->redirectToRoute('admin_series');
        }

        if ($request->isMethod('POST')) {
            $series->setTitle($request->request->get('title'));
            $series->setDescription($request->request->get('description'));
            $series->setReleaseDate(new \DateTime($request->request->get('releaseDate')));
            $series->setPrice((float)$request->request->get('price'));
            $series->setSeasons((int)$request->request->get('seasons'));
            $series->setEpisodes((int)$request->request->get('episodes'));

            $categoryIds = $request->request->all('categories') ?? [];
            $series->getCategories()->clear();
            foreach ((array)$categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $series->addCategory($category);
                }
            }

            $directorIds = $request->request->all('directors') ?? [];
            $series->getDirectors()->clear();
            foreach ((array)$directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $series->addDirector($director);
                }
            }

            $imagePath = $request->files->get('imagePath');
            if ($imagePath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/images/';
                $newFilename = uniqid() . '.' . $imagePath->guessExtension();
                $imagePath->move($uploadDir, $newFilename);
                $series->setImagePath('uploads/images/' . $newFilename);
            }

            $videoPath = $request->files->get('videoPath');
            if ($videoPath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/videos/';
                $newFilename = uniqid() . '.' . $videoPath->guessExtension();
                $videoPath->move($uploadDir, $newFilename);
                $series->setVideoPath('uploads/videos/' . $newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Series updated successfully!');
            return $this->redirectToRoute('admin_series');
        }

        return $this->render('admin/series/edit.html.twig', [
            'series' => $series,
            'categories' => $categoryRepo->findAll(),
            'directors' => $directorRepo->findAll(),
        ]);
    }

    #[Route(path: "/admin/series/delete/{id}", name: "admin_series_delete")]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $series = $entityManager->getRepository(Series::class)->find($id);

        if (!$series) {
            $this->addFlash('error', 'Series not found!');
            return $this->redirectToRoute('admin_series');
        }

        $entityManager->remove($series);
        $entityManager->flush();

        $this->addFlash('success', 'Series deleted successfully!');
        return $this->redirectToRoute('admin_series');
    }
}
