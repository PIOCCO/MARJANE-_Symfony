<?php

namespace App\Controller;

use App\Entity\LineItem;
use App\Form\LineItemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/line/item')]
final class LineItemController extends AbstractController
{
    #[Route(name: 'app_line_item_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $lineItems = $entityManager
            ->getRepository(LineItem::class)
            ->findAll();

        return $this->render('line_item/index.html.twig', [
            'line_items' => $lineItems,
        ]);
    }

    #[Route('/new', name: 'app_line_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lineItem = new LineItem();
        $form = $this->createForm(LineItemType::class, $lineItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lineItem);
            $entityManager->flush();

            return $this->redirectToRoute('app_line_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('line_item/new.html.twig', [
            'line_item' => $lineItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_line_item_show', methods: ['GET'])]
    public function show(LineItem $lineItem): Response
    {
        return $this->render('line_item/show.html.twig', [
            'line_item' => $lineItem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_line_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LineItem $lineItem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LineItemType::class, $lineItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_line_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('line_item/edit.html.twig', [
            'line_item' => $lineItem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_line_item_delete', methods: ['POST'])]
    public function delete(Request $request, LineItem $lineItem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lineItem->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lineItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_line_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
