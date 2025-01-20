<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends AbstractController
{
    #[Route('/users', name: 'user_index', methods: ['GET'])]
    public function index(  UserRepository $userRepository): Response
    {
        // Fetch all users from the database
        $users =  $userRepository->findAll();

        // Render the user list in the 'admin/users.html.twig' template
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Validate and set updated user data from the form
            $user->setFirstName($request->request->get('firstName'));
            $user->setLastName($request->request->get('lastName'));
            $user->setEmail($request->request->get('email'));
            $user->setUsername($request->request->get('username'));
            $user->setProfilePicture($request->request->get('profilePicture'));

            // Save changes to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to the user index page
            $this->addFlash('success', 'User updated successfully!');
            return $this->redirectToRoute('user_index');
        }

        // Render the edit form in 'admin/user/edit.html.twig'
        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
        ]);
    }
}
