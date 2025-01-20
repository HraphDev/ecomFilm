<?php

// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password encoding
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));

            // Handle profile picture upload
            /** @var UploadedFile $profilePicture */
            $profilePicture = $form->get('profilePicture')->getData();

            if ($profilePicture) {
                // Generate a unique file name for the image
                $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePicture->guessExtension();

                // Move the file to the directory where profile pictures are stored
                try {
                    $profilePicture->move(
                        $this->getParameter('profile_pictures_directory'), // You need to define this path in services.yaml
                        $newFilename
                    );
                } catch (\Exception $e) {
                    // Handle the exception if the file can't be moved
                    $this->addFlash('error', 'Could not upload profile picture');
                    return $this->redirectToRoute('register');
                }

                // Set the profile picture filename
                $user->setProfilePicture($newFilename);
            }

            // Persist the user
            $entityManager->persist($user);
            $entityManager->flush();


            // Redirect or show success message
            $this->addFlash('success', 'Registration successful!');
            return $this->redirectToRoute('login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
