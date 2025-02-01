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
    #[Route('/admin/classical', name: 'classical_index')]
    public function index(ClassicalFilmRepository $classicalFilmRepository): Response
    {
        $films = $classicalFilmRepository->findAll();

        return $this->render('admin/classical_films.html.twig', [
            'films' => $films,
        ]);
    }

    #[Route('/admin/classical/new', name: 'classical_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        $film = new ClassicalFilm();
    
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $releaseDate = new \DateTime($request->request->get('releaseDate'));
            $price = $request->request->get('price');
    
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

            
    
            $film->setTitle($title)
                ->setDescription($description)
                ->setReleaseDate($releaseDate)
                ->setPrice($price);
    
            $categoryIds = $request->request->all()['categories'] ?? [];
            if (is_array($categoryIds)) {
                foreach ($categoryIds as $categoryId) {
                    $category = $categoryRepo->find($categoryId);
                    if ($category instanceof Category) {
                        $film->addCategory($category);
                    }
                }
            }
    
            $directorIds = $request->request->all()['directors'] ?? [];
            if (is_array($directorIds)) {
                foreach ($directorIds as $directorId) {
                    $director = $directorRepo->find($directorId);
                    if ($director instanceof Director) {
                        $film->addDirector($director);
                    }
                }
            }
    
            $entityManager->persist($film);
            $entityManager->flush();
    
            return $this->redirectToRoute('classical_index');
        }
    
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();
    
        return $this->render('admin/classic/create.html.twig', [
            'film' => $film,
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }
    
    

    #[Route('/admin/classical/{id}/edit', name: 'classical_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepo, DirectorRepository $directorRepo): Response
    {
        $film = $entityManager->getRepository(ClassicalFilm::class)->find($id);
    
        if (!$film) {
            throw new NotFoundHttpException('Film not found');
        }
    
        if ($request->isMethod('POST')) {
            $film->setTitle($request->request->get('title'))
                ->setDescription($request->request->get('description'))
                ->setReleaseDate(new \DateTime($request->request->get('releaseDate')))
                ->setPrice($request->request->get('price'));
    
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
    
            $film->getCategories()->clear(); 
            $categoryIds = $request->request->all()['categories'] ?? [];
            if (is_array($categoryIds)) {
                foreach ($categoryIds as $categoryId) {
                    $category = $categoryRepo->find($categoryId);
                    if ($category instanceof Category) {
                        $film->addCategory($category);
                    }
                }
            }
    
            $film->getDirectors()->clear(); 
            $directorIds = $request->request->all()['directors'] ?? [];
            if (is_array($directorIds)) {
                foreach ($directorIds as $directorId) {
                    $director = $directorRepo->find($directorId);
                    if ($director instanceof Director) {
                        $film->addDirector($director);
                    }
                }
            }
    
            $entityManager->flush();
            $this->addFlash('success', 'Classic Film updated successfully!');
            return $this->redirectToRoute('classical_index');
            }
    
        $categories = $categoryRepo->findAll();
        $directors = $directorRepo->findAll();
    
        return $this->render('admin/classic/edit.html.twig', [
            'film' => $film,
            'categories' => $categories,
            'directors' => $directors,
        ]);
    }
    

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
