<?php

namespace App\Controller;

use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    private $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }


    // #[Route('/create-payment-intent', name: 'app_create_payment_intent')]
    // public function createPaymentIntent(): JsonResponse
    // {
    //     $paymentIntent = $this->stripeService->createPaymentIntent(1000); // Amount in cents

    //     return $this->json(['clientSecret' => $paymentIntent->client_secret]);
    // }

    #[Route('/create-payment-intent', name: 'app_create_payment_intent')]
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $amount = $data['amount'] ?? 0;

        if ($amount <= 0) {
            return $this->json(['error' => 'Invalid amount'], 400);
        }

        $paymentIntent = $this->stripeService->createPaymentIntent($amount);

        return $this->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    #[Route('/payment-success', name: 'app_payment_success')]
    public function paymentSuccess(Request $request, EntityManagerInterface $entityManager): Response
    {
        $amount = $request->query->get('amount');
        
        // update user balance
        /** @var User $user */
        $user = $this->getUser();
        $user->setBalance($user->getBalance()+$amount);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute("app_home");
    }
}
