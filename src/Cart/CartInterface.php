<?php
// src/Cart/CartInterface.php

namespace App\Cart;

use App\Cart\DtO\ArticlePanier;
use App\Cart\Dto\Panier;

/**
 * Interface définissant le contrat de gestion du panier.
 *
 * Respecte le principe ISP (Interface Segregation Principle) :
 * chaque méthode a une responsabilité claire.
 *
 * Respecte le principe OCP (Open/Closed Principle) :
 * on peut ajouter de nouvelles stratégies sans modifier le code existant.
 */
interface CartInterface
{
    /**
     * Ajouter un article au panier identifié par $identifiant.
     */
    public function ajouter(ArticlePanier $article, string $identifiant): Panier;

    /**
     * Supprimer un article du panier identifié par $identifiant.
     */
    public function supprimer(ArticlePanier $article, string $identifiant): Panier;

    /**
     * Récupérer le panier identifié par $identifiant.
     */
    public function getPanier(string $identifiant): Panier;

    /**
     * Vider le panier identifié par $identifiant.
     */
    public function viderPanier(string $identifiant): void;
}
