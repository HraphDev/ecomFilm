<?php


namespace App\Controller;

use App\Entity\Director;
use App\Repository\DirectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DirectorController extends AbstractController
{
    #[Route('/director', name: 'director_index')]
    public function index(DirectorRepository $directorRepository): Response
    {
        $directors = $directorRepository->findAll();

        return $this->render('admin/director.html.twig', [
            'directors' => $directors,
        ]);
    }

    #[Route('/director/create', name: 'director_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $director = new Director();

        if ($request->isMethod('POST')) {
            $director->setName($request->request->get('name'));

            $entityManager->persist($director);
            $entityManager->flush();

            return $this->redirectToRoute('director_index');
        }

        return $this->render('admin/director/create.html.twig');
    }


    #[Route('/admin/director/edit/{id}', name: 'director_edit')]
    public function edit(Director $director, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $director->setName($request->request->get('name'));

            $entityManager->flush();

            return $this->redirectToRoute('director_index');
        }

        return $this->render('admin/director/edit.html.twig', [
            'director' => $director,
        ]);
    }

    #[Route('/admin/director/delete/{id}', name: 'director_delete')]
    public function delete(Director $director, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($director);
        $entityManager->flush();

        return $this->redirectToRoute('director_index');
    }
}
