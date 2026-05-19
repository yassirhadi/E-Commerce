<?php
// src/Cart/SessionCart.php

namespace App\Cart;

use App\Cart\Dto\ArticlePanier;
use App\Cart\Dto\Panier;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Implémentation de CartInterface utilisant la Session Symfony.
 *
 * Respecte SRP : ne gère que la persistance du panier en session.
 * Respecte LSP : peut remplacer CartInterface partout.
 */
class SessionCart implements CartInterface
{
    private const CLE_SESSION = 'panier';

    public function __construct(
        private readonly RequestStack $requestStack
    ) {}

    public function ajouter(ArticlePanier $article, string $identifiant): Panier
    {
        $panier = $this->getPanier($identifiant);

        // Chercher si le produit existe déjà dans le panier
        foreach ($panier->getArticles() as $articleExistant) {
            if ($articleExistant->getProduitId() === $article->getProduitId()) {
                $articleExistant->incrementerQuantite($article->getQuantite());
                $this->sauvegarderPanier($identifiant, $panier);
                return $panier;
            }
        }

        $panier->ajouterArticle($article);
        $this->sauvegarderPanier($identifiant, $panier);

        return $panier;
    }

    public function supprimer(ArticlePanier $article, string $identifiant): Panier
    {
        $panier = $this->getPanier($identifiant);

        $articlesFiltres = array_filter(
            $panier->getArticles(),
            fn(ArticlePanier $a) => $a->getProduitId() !== $article->getProduitId()
        );

        $panier->setArticles(array_values($articlesFiltres));
        $this->sauvegarderPanier($identifiant, $panier);

        return $panier;
    }

    public function getPanier(string $identifiant): Panier
    {
        $session  = $this->requestStack->getSession();
        $cle      = $this->construireCle($identifiant);
        $donnees  = $session->get($cle);

        if ($donnees instanceof Panier) {
            return $donnees;
        }

        return new Panier();
    }

    public function viderPanier(string $identifiant): void
    {
        $session = $this->requestStack->getSession();
        $session->remove($this->construireCle($identifiant));
    }

    private function sauvegarderPanier(string $identifiant, Panier $panier): void
    {
        $session = $this->requestStack->getSession();
        $session->set($this->construireCle($identifiant), $panier);
    }

    private function construireCle(string $identifiant): string
    {
        return self::CLE_SESSION . '_' . $identifiant;
    }
}
