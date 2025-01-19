<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Form\AccountType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'customer_index', methods: ['GET'])]
public function index(Request $request, CustomerRepository $customerRepository): Response
{
    // Get the search query from the request
    $search = $request->query->get('search', ''); // Default to an empty string if not provided

    if ($search) {
        // Search customers based on name, email, or phone
        $customers = $customerRepository->createQueryBuilder('c')
            ->where('c.Name LIKE :search')
            ->orWhere('c.email LIKE :search')
            ->orWhere('c.phone LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    } else {
        // Fetch all customers if no search query is provided
        $customers = $customerRepository->findAll();
    }

    return $this->render('customer/index.html.twig', [
        'customers' => $customers,
        'search' => $search, // Pass the search term to the view
    ]);
}


                //-----------------------Create------------------------------

                #[Route('/customer/create', name: 'customer_create', methods: ['GET', 'POST'])]
                public function create(Request $request, EntityManagerInterface $entityManager): Response
                {
                    // Create a new customer entity
                    $customer = new Customer();
            
                    // Create the form using the CustomerType form class
                    $form = $this->createForm(CustomerType::class, $customer);
            
                    // Handle the form submission (POST request)
                    $form->handleRequest($request);
            
                    if ($form->isSubmitted() && $form->isValid()) {
                        // Persist the customer to the database
                        $entityManager->persist($customer);
                        $entityManager->flush();
            
                        // Add a success flash message
                        $this->addFlash('success', 'Customer created successfully!');
            
                        // Redirect to the customer list or another page after successful form submission
                        return $this->redirectToRoute('customer_index');
                    }
            
                    // Render the form if the request is GET or the form is not submitted or invalid
                    return $this->render('customer/create.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
                //-----------------------------------------------------

    #[Route('/{id}', name: 'customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'customer_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Customer $customer): Response
{
    // When the form is submitted, update the customer data
    if ($request->isMethod('POST')) {
        $address = $request->request->get('address');
        $phone = $request->request->get('phone');
        $email = $request->request->get('email');

        // Update the customer entity
        $customer->setName($address);
        $customer->setPhone($phone);
        $customer->setEmail($email);

        // Persist the changes to the database
        $this->entityManager->flush();

        // Add success message
        $this->addFlash('success', 'Customer updated successfully!');

        // Redirect back to the customer list
        return $this->redirectToRoute('customer_index');
    }

    return $this->render('customer/edit.html.twig', [
        'customer' => $customer,
    ]);
}

#[Route('/{id}/delete', name: 'customer_delete', methods: ['GET', 'POST'])]
public function delete(Request $request, Customer $customer): Response
{
    // If the request is a POST (form submission), delete the customer
    if ($request->isMethod('POST')) {
        // Remove the customer from the database
        $this->entityManager->remove($customer);
        $this->entityManager->flush();

        // Add success message
        $this->addFlash('success', 'Customer deleted successfully!');

        // Redirect to the customer index (list)
        return $this->redirectToRoute('customer_index');
    }

    // If it's a GET request (confirmation), show the delete confirmation page
    return $this->render('customer/delete.html.twig', [
        'customer' => $customer,
    ]);
}

#[Route('/customer/{id}/create_account', name: 'account_create', methods: ['GET', 'POST'])]
public function createAccount(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
{
    // Create a new account instance
    $account = new Account(''); // Or set a default name here

    // Associate the account with the customer
    $account->setCustomer($customer);

    // Create the form for the account
    $form = $this->createForm(AccountType::class, $account);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the account
        $entityManager->persist($account);
        $entityManager->flush();

        // Add a success message
        $this->addFlash('success', 'Account created successfully!');

        // Redirect to the customer page or accounts page
        return $this->redirectToRoute('customer_show', ['id' => $customer->getId()]);
    }

    return $this->render('account/create.html.twig', [
        'form' => $form->createView(),
        'customer' => $customer,
    ]);
}
}
