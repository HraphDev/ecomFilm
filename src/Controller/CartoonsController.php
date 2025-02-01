<?php

namespace App\Controller;

use App\Entity\Cartoon;
use App\Repository\CategoryRepository;
use App\Repository\DirectorRepository;
use App\Repository\CartoonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CartoonsController extends AbstractController
{
    #[Route("/admin/cartoons", name: "admin_cartoons")]
    public function index(CartoonRepository $cartoonRepository): Response
    {
        return $this->render('admin/cartoons.html.twig', [
            'cartoons' => $cartoonRepository->findAll(),
        ]);
    }

    #[Route("/admin/cartoons/create", name: "admin_cartoons_create")]
    public function create(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        if ($request->isMethod('POST')) {
            $cartoon = new Cartoon();
            $cartoon->setTitle($request->request->get('title'));
            $cartoon->setDescription($request->request->get('description'));
            $cartoon->setReleaseDate(new \DateTime($request->request->get('releaseDate')));
            $cartoon->setPrice((float)$request->request->get('price'));

            $categoryIds = $request->request->all('categories') ?? [];
            foreach ((array)$categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $cartoon->addCategory($category);
                }
            }

            $directorIds = $request->request->all('directors') ?? [];
            foreach ((array)$directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $cartoon->addDirector($director);
                }
            }

            $imagePath = $request->files->get('imagePath');
            if ($imagePath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/images/';
                $newFilename = uniqid() . '.' . $imagePath->guessExtension();
                $imagePath->move($uploadDir, $newFilename);
                $cartoon->setImagePath('uploads/images/' . $newFilename);
            }

            $videoPath = $request->files->get('videoPath');
            if ($videoPath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/videos/';
                $newFilename = uniqid() . '.' . $videoPath->guessExtension();
                $videoPath->move($uploadDir, $newFilename);
                $cartoon->setVideoPath('uploads/videos/' . $newFilename);
            }

            $entityManager->persist($cartoon);
            $entityManager->flush();

            $this->addFlash('success', 'Cartoon created successfully!');
            return $this->redirectToRoute('admin_cartoons');
        }

        return $this->render('admin/cartoons/create.html.twig', [
            'categories' => $categoryRepo->findAll(),
            'directors' => $directorRepo->findAll(),
        ]);
    }

    #[Route("/admin/cartoons/edit/{id}", name: "admin_cartoons_edit")]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        $cartoon = $entityManager->getRepository(Cartoon::class)->find($id);

        if (!$cartoon) {
            $this->addFlash('error', 'Cartoon not found!');
            return $this->redirectToRoute('admin_cartoons');
        }

        if ($request->isMethod('POST')) {
            $cartoon->setTitle($request->request->get('title'));
            $cartoon->setDescription($request->request->get('description'));
            $cartoon->setReleaseDate(new \DateTime($request->request->get('releaseDate')));
            $cartoon->setPrice((float)$request->request->get('price'));

            $cartoon->getCategories()->clear();
            $categoryIds = $request->request->all('categories') ?? [];
            foreach ((array)$categoryIds as $categoryId) {
                $category = $categoryRepo->find($categoryId);
                if ($category) {
                    $cartoon->addCategory($category);
                }
            }

            $cartoon->getDirectors()->clear();
            $directorIds = $request->request->all('directors') ?? [];
            foreach ((array)$directorIds as $directorId) {
                $director = $directorRepo->find($directorId);
                if ($director) {
                    $cartoon->addDirector($director);
                }
            }

            $imagePath = $request->files->get('imagePath');
            if ($imagePath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/images/';
                $newFilename = uniqid() . '.' . $imagePath->guessExtension();
                $imagePath->move($uploadDir, $newFilename);
                $cartoon->setImagePath('uploads/images/' . $newFilename);
            }

            $videoPath = $request->files->get('videoPath');
            if ($videoPath instanceof UploadedFile) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/videos/';
                $newFilename = uniqid() . '.' . $videoPath->guessExtension();
                $videoPath->move($uploadDir, $newFilename);
                $cartoon->setVideoPath('uploads/videos/' . $newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Cartoon updated successfully!');
            return $this->redirectToRoute('admin_cartoons');
        }

        return $this->render('admin/cartoons/edit.html.twig', [
            'cartoon' => $cartoon,
            'categories' => $categoryRepo->findAll(),
            'directors' => $directorRepo->findAll(),
        ]);
    }

    #[Route("/admin/cartoons/delete/{id}", name: "admin_cartoons_delete")]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $cartoon = $entityManager->getRepository(Cartoon::class)->find($id);

        if (!$cartoon) {
            $this->addFlash('error', 'Cartoon not found!');
            return $this->redirectToRoute('admin_cartoons');
        }

        $entityManager->remove($cartoon);
        $entityManager->flush();

        $this->addFlash('success', 'Cartoon deleted successfully!');
        return $this->redirectToRoute('admin_cartoons');
    }
}
