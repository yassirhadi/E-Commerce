<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CourseController extends AbstractController
{
    private string $projectDir;
    private string $uploadsDirectory;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
        // Chemin ABSOLU pour Docker
        $this->uploadsDirectory = '/var/www/html/public/uploads/courses';

        // Créer le répertoire s'il n'existe pas
        if (!is_dir($this->uploadsDirectory)) {
            mkdir($this->uploadsDirectory, 0777, true);
        }
    }

    #[Route('/course/new', name: 'course_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier uploadé
            $file = $form->get('file')->getData();

            if ($file) {
                // Validation du type de fichier
                $allowedTypes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'image/jpeg',
                    'image/png',
                    'image/gif'
                ];

                $fileType = $file->getMimeType();
                $fileSize = $file->getSize();

                // Vérifier le type de fichier
                if (!in_array($fileType, $allowedTypes)) {
                    $this->addFlash('error', 'Type de fichier non autorisé. Formats acceptés: PDF, Word, PowerPoint, Images.');
                    return $this->redirectToRoute('course_new');
                }

                // Vérifier la taille (10 Mo maximum)
                $maxSize = 10 * 1024 * 1024; // 10 Mo en octets
                if ($fileSize > $maxSize) {
                    $this->addFlash('error', 'Le fichier est trop volumineux. Taille maximum: 10 Mo.');
                    return $this->redirectToRoute('course_new');
                }

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    // Déplacer le fichier vers le répertoire d'uploads
                    $file->move(
                        $this->uploadsDirectory,
                        $newFilename
                    );

                    // Enregistrer les informations du fichier dans l'entité
                    $course->setFilePath('uploads/courses/' . $newFilename);
                    $course->setFileType($fileType);
                    $course->setFileSize($fileSize);
                    $course->setOriginalFilename($file->getClientOriginalName());

                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'upload du fichier.');
                    return $this->redirectToRoute('course_new');
                }
            }

            // Sauvegarder en base de données
            $entityManager->persist($course);
            $entityManager->flush();

            $this->addFlash('success', 'Le cours a été créé avec succès !');
            return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
        }

        return $this->render('course/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courses', name: 'course_index')]
    public function index(CourseRepository $courseRepository, Request $request): Response
    {
        $search = $request->query->get('search', '');

        if ($search) {
            $courses = $courseRepository->search($search);
        } else {
            $courses = $courseRepository->findAllOrderedByDate();
        }

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'search' => $search,
        ]);
    }

    #[Route('/course/{id}', name: 'course_show')]
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/course/{id}/download', name: 'course_download')]
    public function download(Course $course): BinaryFileResponse
    {
        $filePath = $this->projectDir . '/public/' . $course->getFilePath();

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Le fichier n\'existe pas.');
        }

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $course->getOriginalFilename() ?: basename($filePath)
        );

        return $response;
    }
}
