<?php

namespace App\Controller;

use DateTime;
use App\Entity\Todo;
use App\Enum\Statut;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todo_liste', methods: ['GET'])]
    public function liste(TodoRepository $todoRepository, Request $request)
    {
        $filtre = $request->query->get('filtre');

        if ($filtre === 'termine') {
            $todos = $todoRepository->findBy(['statut' => Statut::TERMINE]);
        } elseif ($filtre === 'a_faire') {
            $todos = $todoRepository->findBy(['statut' => Statut::A_FAIRE]);
        } elseif ($filtre === 'en_cours') {
            $todos = $todoRepository->findBy(['statut' => Statut::EN_COURS]);
        } else {
            $todos = $todoRepository->findAll();
        }

        return $this->render('todo/liste.html.twig', [
            'todos' => $todos,
            'filtre' => $filtre,
        ]);
    }

    #[Route('/todo/nouveau', name: 'todo_nouveau', methods: ['POST'])]
    public function nouveau(Request $request, EntityManagerInterface $entityManager)
    {
        $titre = $request->request->get('titre');
        $dateFin = $request->request->get('dateFin');

        if ($titre === '') {
            $this->addFlash('erreur', 'Le titre est obligatoire');
            return $this->redirectToRoute('todo_liste');
        }

        $todo = new Todo();
        $todo->setTitre($titre);
        $todo->setStatut(Statut::A_FAIRE);

        if (!empty($dateFin)) {
            $todo->setDateFin(DateTime::createFromFormat('Y-m-d', $dateFin));
        }
        
        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->redirectToRoute('todo_liste');
    }

    #[Route('/todo/modifier/{id}', name: 'todo_modifier', methods: ['GET','POST'])]
    public function modifier(int $id, Request $request, TodoRepository $todoRepository, EntityManagerInterface $entityManager)
    {
        $todo = $todoRepository->find($id);

        if ($request->isMethod('POST')) {
            $titre = $request->request->get('newTitre');

            if ($titre === '') {
                return $this->render('todo/modifier.html.twig', [
                    'erreur' => 'Le titre est obligatoire',
                    'todo' => $todo,
                ]);
            }

            $todo->setTitre($titre);
            $entityManager->flush();

            return $this->redirectToRoute('todo_liste');
        }

        return $this->render('todo/modifier.html.twig', [
            'todo' => $todo,
        ]);
    }

    #[Route('/todo/supprimer/{id}', name: 'todo_supprimer', methods: ['POST'])]
    public function supprimer(int $id, TodoRepository $todoRepository, EntityManagerInterface $entityManager)
    {
        $todo = $todoRepository->find($id);

        $entityManager->remove($todo);
        $entityManager->flush();

        return $this->redirectToRoute('todo_liste');
    }

    #[Route('/todo/statut/{id}/{statut}', name: 'todo_statut', methods: ['POST'])]
    public function changerStatut(int $id, string $statut, TodoRepository $todoRepository, EntityManagerInterface $entityManager)
    {
        $todo = $todoRepository->find($id);

        $todo->setStatut(Statut::from($statut));

        $entityManager->flush();

        return $this->redirectToRoute('todo_liste');
    }
}