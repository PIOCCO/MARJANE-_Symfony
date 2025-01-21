<?php

namespace App\Controller;

use App\Entity\WebUser;
use App\Form\WebUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/web/user')]
final class WebUserController extends AbstractController
{
    #[Route(name: 'app_web_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $webUsers = $entityManager
            ->getRepository(WebUser::class)
            ->findAll();

        return $this->render('web_user/index.html.twig', [
            'web_users' => $webUsers,
        ]);
    }

    #[Route('/new', name: 'app_web_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $webUser = new WebUser();
        $form = $this->createForm(WebUserType::class, $webUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($webUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_web_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web_user/new.html.twig', [
            'web_user' => $webUser,
            'form' => $form,
        ]);
    }

    #[Route('/{loginId}', name: 'app_web_user_show', methods: ['GET'])]
    public function show(WebUser $webUser): Response
    {
        return $this->render('web_user/show.html.twig', [
            'web_user' => $webUser,
        ]);
    }

    #[Route('/{loginId}/edit', name: 'app_web_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebUser $webUser, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WebUserType::class, $webUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_web_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('web_user/edit.html.twig', [
            'web_user' => $webUser,
            'form' => $form,
        ]);
    }

    #[Route('/{loginId}', name: 'app_web_user_delete', methods: ['POST'])]
    public function delete(Request $request, WebUser $webUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webUser->getLoginId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($webUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_web_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
