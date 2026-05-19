<?php
// src/Cart/Dto/Panier.php

namespace App\Cart\DtO;

/**
 * DTO représentant le panier (sans base de données).
 * Respecte SRP : cette classe ne fait que stocker les données du panier.
 */
class Panier
{
    /** @var ArticlePanier[] */
    private array $articles = [];

    private \DateTimeImmutable $creeLe;

    public function __construct()
    {
        $this->creeLe = new \DateTimeImmutable();
    }

    /**
     * @return ArticlePanier[]
     */
    public function getArticles(): array
    {
        return $this->articles;
    }

    public function setArticles(array $articles): static
    {
        $this->articles = $articles;
        return $this;
    }

    public function ajouterArticle(ArticlePanier $article): static
    {
        $this->articles[] = $article;
        return $this;
    }

    public function total(): float
    {
        return array_reduce(
            $this->articles,
            fn(float $carry, ArticlePanier $article) => $carry + $article->getPrixTotal(),
            0.0
        );
    }

    public function nombreArticles(): int
    {
        return count($this->articles);
    }

    public function getCreeLe(): \DateTimeImmutable
    {
        return $this->creeLe;
    }

    public function setCreeLe(\DateTimeImmutable $creeLe): static
    {
        $this->creeLe = $creeLe;
        return $this;
    }
}
