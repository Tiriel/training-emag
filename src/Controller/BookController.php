<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(BookRepository $repository): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        return $this->render('book/index.html.twig', [
            'books' => $repository->findAll(),
        ]);
    }

    #[Route('/{!id<\d+>?1}', name: 'app_book_show', methods: ['GET'])]
    // #[Route('/{id<\d+>?1}', name: 'app_book_show', requirements: ['id' => '\d+'], defaults: ['id' => 1])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/new', name: 'app_book_new', methods: ['GET'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        if ($form->isSubmitted() && $form->isValid()) {
            //if (($user = $this->getUser()) instanceof User) {
            //    $book->setCreatedBy($user);
            //}
            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }

    //#[IsGranted('book.title', 'book')]
    #[Route('/{title}', name: 'app_book_title', methods: ['GET'])]
    public function title(BookRepository $repository, #[MapEntity] ?Book $book): Response
    {
        $this->denyAccessUnlessGranted('book.title', $book);

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}
