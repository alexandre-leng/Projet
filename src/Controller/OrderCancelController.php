<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
    public function index($stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        // Sécurité : si $order n'existe pas OU $order->getUser est différent de User connecté
        // Redirection au Home page
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // Envoyer un email au client pour lui indiquer l'échec de paiement
        
        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}