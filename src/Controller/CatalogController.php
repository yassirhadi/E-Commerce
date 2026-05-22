<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Author;
use App\DTO\Category;
use App\DTO\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/catalog', name: 'app_catalog_')]
class CatalogController extends AbstractController
{
    #[Route(path: '/{slug}', name: 'view')]
    public function show(string $slug): Response
    {
        $course = $this->loadCourse($slug); // simulate loading this course from the storge (from API or Database)

        if (null === $course) {
            throw $this->createNotFoundException('La page que vous demandez est introuvable.');
        }

        return $this->render('catalog/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route(path: '/all', name: 'all', priority: 1)]
    public function all(): Response
    {
        $courses = $this->findAll(); // simulate loading this course from the storge (from API or Database)

        return $this->render('catalog/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route(path: '/similar/{limit}', name: 'similar', defaults: ['limit' => 2])]
    public function similarCourses(int $limit = 2): Response
    {
        $similarCourses = $this->findSimilarCourses($limit);

        return $this->render('catalog/similar_courses.html.twig', [
            'courses' => $similarCourses,
        ]);
    }

    /**
     * This function simulates **Querying course from a storage - e.h database**
     *
     * @param string $slug
     *
     * @return ?Course
     */
    private function loadCourse(string $slug): Course|null
    {
        $course = $this->findAll();

        return $course[$slug] ?? null;
    }

    private function findAll(): array
    {
        return [
            'introduction-a-la-programmation' => new Course(
                name: 'Introduction à la programmation',
                price: 49.99,
                synopsis: 'Apprenez les bases de la programmation avec Python.',
                description: 'Ce cours couvre les fondamentaux de la programmation, y compris les variables, les boucles, les fonctions et les structures de données.',
                author: new Author('Alice Dupont'),
                category: new Category('Informatique')
            ),
            'analyse-financiere' => new Course(
                name: 'Analyse financière',
                price: 79.00,
                synopsis: 'Comprendre les états financiers et les indicateurs clés.',
                description: 'Ce cours vous guide à travers l’analyse des bilans, des comptes de résultat et des flux de trésorerie.',
                author: new Author('Jean Martin'),
                category: new Category('Finance')
            ),
            'photographie-numerique' => new Course(
                name: 'Photographie numérique',
                price: 59.50,
                synopsis: 'Maîtrisez votre appareil photo et composez des images percutantes.',
                description: 'Apprenez les techniques de prise de vue, de composition, et de retouche photo avec des outils professionnels.',
                author: new Author('Sophie Bernard'),
                category: new Category('Arts visuels')
            )
        ];
    }

    private function findSimilarCourses(int $limit): array
    {
        $courses = $this->findAll();

        $keys = \array_flip(\array_rand($courses, $limit));

        return \array_intersect_ukey($courses, $keys, function (string $a, $b) {
            return $a <=> $b;
        });
    }
}
