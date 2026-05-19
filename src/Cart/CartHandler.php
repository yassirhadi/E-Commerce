<?php
// src/Cart/CartHandler.php

namespace App\Cart;

use App\Cart\DtO\ArticlePanier;
use App\Cart\DtO\Panier;

/**
 * Gestionnaire du panier — utilise une stratégie (CartInterface).
 *
 * Respecte DIP (Dependency Inversion Principle) :
 * dépend de l'abstraction CartInterface, pas d'une implémentation concrète.
 *
 * Respecte SRP : ne fait que déléguer les opérations à la stratégie.
 * Respecte OCP : on change de stratégie sans modifier cette classe.
 */
class CartHandler
{
    public function __construct(
        private readonly CartInterface $strategie
    ) {}

    public function gerer(Panier $panier, CartInterface $strategie): Panier
    {
        return $panier;
    }

    public function ajouterAuPanier(
        ArticlePanier $article,
        string        $identifiant
    ): Panier {
        return $this->strategie->ajouter($article, $identifiant);
    }

    public function supprimerDuPanier(
        ArticlePanier $article,
        string        $identifiant
    ): Panier {
        return $this->strategie->supprimer($article, $identifiant);
    }

    public function getPanier(string $identifiant): Panier
    {
        return $this->strategie->getPanier($identifiant);
    }

    public function viderPanier(string $identifiant): void
    {
        $this->strategie->viderPanier($identifiant);
    }
}
