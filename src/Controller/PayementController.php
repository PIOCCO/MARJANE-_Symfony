<?php

namespace App\Controller;

use App\Entity\Payement;
use App\Form\PayementType;
use App\Repository\PayementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/payement')]
final class PayementController extends AbstractController
{
    #[Route(name: 'app_payement_index', methods: ['GET'])]
    public function index(PayementRepository $payementRepository): Response
    {
        return $this->render('payement/index.html.twig', [
            'payements' => $payementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_payement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $payement = new Payement(0.0);
        $form = $this->createForm(PayementType::class, $payement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payement);
            $entityManager->flush();

            return $this->redirectToRoute('app_payement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payement/new.html.twig', [
            'payement' => $payement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payement_show', methods: ['GET'])]
    public function show(Payement $payement): Response
    {
        return $this->render('payement/show.html.twig', [
            'payement' => $payement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payement $payement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PayementType::class, $payement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_payement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payement/edit.html.twig', [
            'payement' => $payement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payement_delete', methods: ['POST'])]
    public function delete(Request $request, Payement $payement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($payement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_payement_index', [], Response::HTTP_SEE_OTHER);
    }
}
