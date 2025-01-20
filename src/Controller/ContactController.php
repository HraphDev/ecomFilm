<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $successMessage = null;

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $message = $request->request->get('message');

            // Simple validation
            if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $contactMessage = new ContactMessage();
                $contactMessage->setName($name);
                $contactMessage->setEmail($email);
                $contactMessage->setMessage($message);

                $em->persist($contactMessage);
                $em->flush();

                $successMessage = 'Your message has been sent successfully!';
            } else {
                $errorMessage = 'Please fill in all fields correctly.';
            }
        }

        return $this->render('contact/index.html.twig', [
            'successMessage' => $successMessage ?? null,
            'errorMessage' => $errorMessage ?? null,
        ]);
    }
}
