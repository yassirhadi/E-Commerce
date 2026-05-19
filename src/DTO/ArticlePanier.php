<?php
// src/Cart/DtO/ArticlePanier.php

namespace App\Cart\Dto;

/**
 * DTO représentant un article dans le panier.
 * Respecte SRP : ne fait que stocker les données d'un article.
 */
class ArticlePanier
{
    public function __construct(
        private int    $produitId,
        private string $nom,
        private float  $prix,
        private int    $quantite = 1,
        private ?string $image = null,
    ) {}

    public function getProduitId(): int
    {
        return $this->produitId;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getPrixTotal(): float
    {
        return $this->prix * $this->quantite;
    }

    public function incrementerQuantite(int $quantite = 1): static
    {
        $this->quantite += $quantite;
        return $this;
    }
}
