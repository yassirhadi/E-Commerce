<?php
// src/Controller/ProduitController.php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit/{id}', name: 'app_produit_details')]
    public function details(Product $produit): Response
    {
        return $this->render('produit/details.html.twig', [
            'produit' => $produit
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_produit_liste')]
    public function liste(
        string             $slug,
        CategoryRepository $categorieRepository,
        ProductRepository  $produitRepository
    ): Response {
        $categorie = $categorieRepository->findBySlug($slug);

        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée.');
        }

        $produits = $produitRepository->findByCategorySlug($slug);

        return $this->render('produit/liste.html.twig', [
            'produits'  => $produits,
            'categorie' => $categorie,
        ]);
    }
}
