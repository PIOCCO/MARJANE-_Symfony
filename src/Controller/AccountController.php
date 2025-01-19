<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface; // Correct import

class AccountController extends AbstractController
{
    private $entityManager;
    private $accountRepository;

    public function __construct(EntityManagerInterface $entityManager, AccountRepository $accountRepository)
    {
        $this->entityManager = $entityManager;
        $this->accountRepository = $accountRepository;
    }


    // Route for viewing all accounts
    #[Route('/account', name: 'account_index')]
    public function index(Request $request, AccountRepository $accountRepository): Response
    {
        // Get search parameter from query string (if present)
        $search = $request->query->get('search', '');

        // Search the Account entity by AccountName
        $accounts = $accountRepository->findBySearchTerm($search);

        // Render the template and pass the accounts and search term to the view
        return $this->render('account/index.html.twig', [
            'accounts' => $accounts,
            'search' => $search,
        ]);
    }
    


// Route for editing an account
#[Route('/account/{id}/edit', name: 'account_edit')]
public function edit(Request $request, int $id): Response
{
    // Retrieve the account to edit
    $account = $this->accountRepository->find($id);

    if (!$account) {
        return $this->json(['error' => 'Account not found'], Response::HTTP_NOT_FOUND);
    }

    // Create the form for editing the account
    $form = $this->createForm(AccountType::class, $account);

    // Handle the form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the changes
        $this->entityManager->flush();

        // Redirect to the updated account view (or another route)
        return $this->redirectToRoute('account_view', ['id' => $account->getId()]);
    }

    // Render the form if it's not submitted or invalid
    return $this->render('account/edit.html.twig', [
        'form' => $form->createView(),
        'account' => $account,  // Pass the current account data to the template
    ]);
}

#[Route('/account/create', name: 'account_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new Account object
        $account = new Account('');

        // Create the form using the AccountType form class
        $form = $this->createForm(AccountType::class, $account);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // Persist the account entity to the database
                $entityManager->persist($account);
                $entityManager->flush();

                // Add a success flash message
                $this->addFlash('success', 'Account successfully created!');

                // Redirect to the account details page
                return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
            } else {
                // Add an error flash message for invalid form submission
                $this->addFlash('error', 'There were errors in your form. Please fix them and try again.');
            }
        }

        // Render the form view if the form is not submitted or is invalid
        return $this->render('account/create.html.twig', [
            'form' => $form->createView(),  // Pass the form view to the template
        ]);
    }

    // Route for viewing an account
    #[Route('/account/{id}', name: 'account_view', methods: ['GET'])]
    public function view(string $id): Response
    {
        $account = $this->accountRepository->find($id);
    
        if (!$account) {
            throw $this->createNotFoundException('Account not found');
        }
    
        // Render the Twig template with account details
        return $this->render('account/view.html.twig', [
            'account' => [
                'id' => $account->getId(),
                'account_name' => $account->getAccountName(),
                'is_closed' => $account->isClosed(),
                'open_date' => $account->getOpenDate()->format('Y-m-d H:i:s'),
                'closed_date' => $account->getClosedDate() ? $account->getClosedDate()->format('Y-m-d H:i:s') : null,
                'is_vip' => $account->isVip(),
            ],
        ]);
    }

    // Route for deleting an account
    #[Route('/{id}/delete', name: 'account_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Account $account): Response
    {
        // If the request is a POST (form submission), delete the account
        if ($request->isMethod('POST')) {
            // Remove the account from the database
            $this->entityManager->remove($account);
            $this->entityManager->flush();
    
            // Add success message
            $this->addFlash('success', 'Account deleted successfully!');
    
            // Redirect to the account index (list)
            return $this->redirectToRoute('account_index');
        }
    
        // If it's a GET request (confirmation), show the delete confirmation page
        return $this->render('account/delete.html.twig', [
            'account' => $account,
        ]);
    }

    // Route for setting VIP status
    #[Route('/account/{id}/vip', name: 'account_set_vip', methods: ['PATCH'])]
    public function setVip(int $id): Response
    {
        $account = $this->accountRepository->find($id);

        if (!$account) {
            return $this->json(['error' => 'Account not found'], Response::HTTP_NOT_FOUND);
        }

        // Set the account as VIP
        $account->setIsVip(true);

        // Persist the changes
        $this->entityManager->flush();

        return $this->json(['message' => 'Account VIP status updated']);
    }
}
