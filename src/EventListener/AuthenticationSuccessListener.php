<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent; // Correct event class for newer versions
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent; // Fallback for older versions

class AuthenticationSuccessListener
{
    private RouterInterface $router;
    private RequestStack $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function onLoginSuccess($event): void
    {
        // Determine which event class we have
        if ($event instanceof LoginSuccessEvent) {
            $user = $event->getAuthenticationToken()->getUser();
            $roles = $user->getRoles();
            
            // Set the response directly in the LoginSuccessEvent
            $targetPath = null;

            // Check roles and set the target path accordingly
            if (in_array('ROLE_ADMIN', $roles, true)) {
                $targetPath = $this->router->generate('admin_dashboard');
            } else {
                $targetPath = $this->router->generate('homepage');
            }

            // Create the RedirectResponse and set it in the event
            $response = new RedirectResponse($targetPath);
            $event->setResponse($response);
        } elseif ($event instanceof AuthenticationSuccessEvent) {
            // For older versions of Symfony, we can't set the response directly on the event
            $user = $event->getAuthenticationToken()->getUser();
            $roles = $user->getRoles();

            $request = $this->requestStack->getCurrentRequest();
            $targetPath = null;

            // Check roles and set the target path accordingly
            if (in_array('ROLE_ADMIN', $roles, true)) {
                $targetPath = $this->router->generate('admin_dashboard');
            } else {
                $targetPath = $this->router->generate('home');
            }

            // Redirect manually for older Symfony versions
            $response = new RedirectResponse($targetPath);
            $response->send(); // Send the response immediately for older versions
        } else {
            throw new \RuntimeException('Unexpected event type.');
        }
    }
}
