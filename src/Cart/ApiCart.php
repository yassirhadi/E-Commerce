<?php
// src/Cart/ApiCart.php

namespace App\Cart;

use App\Cart\DtO\ArticlePanier;
use App\Cart\DtO\Panier;

/**
 * Implémentation de CartInterface simulant une API externe.
 *
 * Cette classe démontre que le code respecte SOLID :
 * on peut ajouter une nouvelle stratégie sans modifier CartHandler.
 *
 * Respecte OCP : ouvert à l'extension, fermé à la modification.
 * Les méthodes utilisent dd() pour simuler les appels API (comme demandé).
 */
class ApiCart implements CartInterface
{
    public function ajouter(ArticlePanier $article, string $identifiant): Panier
    {
        // Simulation d'un appel API POST /cart/{identifiant}/items
        dd([
            'action'      => 'API: ajouter article',
            'identifiant' => $identifiant,
            'produit_id'  => $article->getProduitId(),
            'nom'         => $article->getNom(),
            'quantite'    => $article->getQuantite(),
            'prix'        => $article->getPrix(),
        ]);
    }

    public function supprimer(ArticlePanier $article, string $identifiant): Panier
    {
        // Simulation d'un appel API DELETE /cart/{identifiant}/items/{produitId}
        dd([
            'action'      => 'API: supprimer article',
            'identifiant' => $identifiant,
            'produit_id'  => $article->getProduitId(),
        ]);
    }

    public function getPanier(string $identifiant): Panier
    {
        // Simulation d'un appel API GET /cart/{identifiant}
        dd([
            'action'      => 'API: récupérer panier',
            'identifiant' => $identifiant,
        ]);
    }

    public function viderPanier(string $identifiant): void
    {
        // Simulation d'un appel API DELETE /cart/{identifiant}
        dd([
            'action'      => 'API: vider panier',
            'identifiant' => $identifiant,
        ]);
    }
}
