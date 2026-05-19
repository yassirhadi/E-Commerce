<?php
// src/Controller/PanierController.php

namespace App\Controller;

use App\Cart\CartHandler;
use App\Cart\DtO\ArticlePanier;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur du panier.
 *
 * Respecte SRP : ne gère que les actions liées au panier.
 * Utilise CartHandler via injection de dépendances (DIP).
 */
class PanierController extends AbstractController
{
    private const IDENTIFIANT_PANIER = 'panier_principal';

    public function __construct(
        private readonly CartHandler $panierHandler
    ) {}

    #[Route('/panier', name: 'app_panier_recapitulatif', methods: ['GET'])]
    public function recapitulatif(): Response
    {
        $panier      = $this->panierHandler->getPanier(self::IDENTIFIANT_PANIER);
        $sousTotal   = $panier->total();
        $fraisPort   = $sousTotal >= 50 ? 0.0 : 10.0;
        $taxes       = round($sousTotal * 0.082, 2);
        $total       = $sousTotal + $fraisPort + $taxes;

        return $this->render('panier/recapitulatif.html.twig', [
            'panier'     => $panier,
            'sous_total' => $sousTotal,
            'frais_port' => $fraisPort,
            'taxes'      => $taxes,
            'total'      => $total,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter', methods: ['POST'])]
    public function ajouter(
        Product           $produit,
        Request           $request
    ): Response {
        $quantite = (int) $request->request->get('quantite', 1);
        $quantite = max(1, min($quantite, $produit->getStock()));

        $article = new ArticlePanier(
            produitId: $produit->getId(),
            nom:       $produit->getName(),
            prix:      $produit->getPrice(),
            quantite:  $quantite,
            image:     $produit->getImage(),
        );

        $this->panierHandler->ajouterAuPanier($article, self::IDENTIFIANT_PANIER);

        $this->addFlash('success', sprintf(
            '"%s" a été ajouté à votre panier.',
            $produit->getName()
        ));

        return $this->redirectToRoute('app_panier_recapitulatif');
    }

    #[Route('/panier/supprimer/{produitId}', name: 'app_panier_supprimer', methods: ['POST'])]
    public function supprimer(int $produitId, ProductRepository $productRepository): Response
    {
        $produit = $productRepository->find($produitId);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        $article = new ArticlePanier(
            produitId: $produit->getId(),
            nom:       $produit->getName(),
            prix:      $produit->getPrice(),
        );

        $this->panierHandler->supprimerDuPanier($article, self::IDENTIFIANT_PANIER);

        $this->addFlash('info', sprintf(
            '"%s" a été retiré de votre panier.',
            $produit->getName()
        ));

        return $this->redirectToRoute('app_panier_recapitulatif');
    }

    #[Route('/panier/vider', name: 'app_panier_vider', methods: ['POST'])]
    public function vider(): Response
    {
        $this->panierHandler->viderPanier(self::IDENTIFIANT_PANIER);
        $this->addFlash('info', 'Votre panier a été vidé.');

        return $this->redirectToRoute('app_panier_recapitulatif');
    }
}
