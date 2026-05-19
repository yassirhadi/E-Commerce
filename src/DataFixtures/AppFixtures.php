<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Création des catégories
        $categoriesData = [
            [
                'name' => 'Électronique',
                'description' => 'Appareils high-tech et gadgets',
                'badgeColor' => 'primary',
                'products' => [
                    ['name' => 'Casque sans fil', 'price' => 79.99, 'sku' => 'WH-001', 'stock' => 15, 'image' => 'casque-audio.png'],
                    ['name' => 'Enceinte Bluetooth', 'price' => 59.99, 'sku' => 'SP-002', 'stock' => 20, 'image' => 'enceinte-sans-fil.png'],
                    ['name' => 'Souris sans fil', 'price' => 29.99, 'sku' => 'MS-003', 'stock' => 30, 'image' => 'souris-sans-fil.png'],
                    ['name' => 'Clavier mécanique', 'price' => 89.99, 'sku' => 'KB-004', 'stock' => 10, 'image' => 'clavier-mecanique.png'],
                    ['name' => 'Webcam HD 1080p', 'price' => 49.99, 'sku' => 'WC-005', 'stock' => 12, 'image' => 'webcam-hd.png'],
                    ['name' => 'Batterie externe', 'price' => 39.99, 'sku' => 'PB-006', 'stock' => 25, 'image' => 'batterie-externe.png'],
                    ['name' => 'Montre connectée Pro', 'price' => 199.99, 'sku' => 'SW-007', 'stock' => 8, 'image' => 'montre-connectee.png'],
                ]
            ],
            [
                'name' => 'Mode',
                'description' => 'Vêtements et accessoires tendance',
                'badgeColor' => 'warning',
                'products' => [
                    ['name' => 'Veste en cuir classique', 'price' => 149.99, 'sku' => 'JK-101', 'stock' => 5, 'image' => 'veste-cuir.png'],
                ]
            ],
            [
                'name' => 'Maison et jardin',
                'description' => 'Articles pour la maison et le jardinage',
                'badgeColor' => 'success',
                'products' => [
                    ['name' => 'Capteur intelligent pour plantes', 'price' => 34.99, 'sku' => 'PL-201', 'stock' => 18, 'image' => 'capteur-plante.png'],
                ]
            ],
            [
                'name' => 'Sport',
                'description' => 'Équipements et accessoires de sport',
                'badgeColor' => 'info',
                'products' => [
                    ['name' => 'Tapis de yoga premium', 'price' => 29.99, 'sku' => 'YG-301', 'stock' => 22, 'image' => 'tapis-sport.png'],
                ]
            ],
            [
                'name' => 'Livres',
                'description' => 'Livres et guides éducatifs',
                'badgeColor' => 'danger',
                'products' => [
                    ['name' => 'Guide de développement web', 'price' => 24.99, 'sku' => 'BK-401', 'stock' => 14, 'image' => 'livre-programmation.png'],
                ]
            ],
            [
                'name' => 'Jouets et jeux',
                'description' => 'Divertissement pour les enfants et les familles',
                'badgeColor' => 'primary',
                'products' => []
            ],
            [
                'name' => 'Automobile',
                'description' => 'Accessoires automobiles et outils d\'entretien',
                'badgeColor' => 'dark',
                'products' => []
            ],
            [
                'name' => 'Fournitures pour animaux',
                'description' => 'Nourriture, jouets et accessoires pour animaux',
                'badgeColor' => 'warning',
                'products' => []
            ],
        ];

        foreach ($categoriesData as $catData) {
            $category = new Category();
            $category->setName($catData['name']);
            $category->setDescription($catData['description']);
            $category->setSlug($this->slugger->slug($catData['name'])->lower());
            $category->setBadgeColor($catData['badgeColor']);

            $manager->persist($category);

            // Création des produits pour cette catégorie
            foreach ($catData['products'] as $prodData) {
                $product = new Product();
                $product->setName($prodData['name']);
                $product->setDescription("Description détaillée du produit " . $prodData['name']);
                $product->setPrice($prodData['price']);
                $product->setSku($prodData['sku']);
                $product->setStock($prodData['stock']);
                $product->setImage($prodData['image']);
                $product->setCategory($category);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
