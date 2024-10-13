<?php

namespace App\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    private $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    #[Route('/create-payment-intent', name: 'app_create_payment_intent')]
    public function createPaymentIntent(): JsonResponse
    {
        $paymentIntent = $this->stripeService->createPaymentIntent(1000); // Amount in cents

        return $this->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    #[Route('/payment', name: 'app_payment')]
    public function paymentForm(): Response
    {
        return $this->render('payment/index.html.twig', [
            'stripe_public_key' => $this->getParameter('app.stripe_public_key'),
        ]);
    }
}
