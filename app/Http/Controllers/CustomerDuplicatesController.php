<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Contracts\DuplicateFinder;
use App\Contracts\CustomerRepository;

class CustomerDuplicatesController extends Controller
{
    private $duplicateFinder;
    private $customerRepository;

    /**
     * Constructs the CustomerDuplicatesController
     * @param DuplicateFinder    $duplicateFinder    
     * @param CustomerRepository $customerRepository 
     */
    public function __construct(DuplicateFinder $duplicateFinder, CustomerRepository $customerRepository)
    {
        $this->duplicateFinder = $duplicateFinder;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Checks for possible duplicates and renders them in
     * an HTML view.
     * @param  int $customerId      ID of the customer for whom we will check for duplicates
     * @return view
     */
    public function show($customerId)
    {
        // Find the customer
        $customer = $this->customerRepository->find($customerId);

        // Find possible duplicates
        $possibleDuplicates = $this->duplicateFinder->findDuplicates($customer);

        // Send them to the view with the customer
        return view('customers.possible_duplicates')
                    ->with([
                        'customer' => $customer,
                        'duplicates' => $possibleDuplicates
                    ]);
    }
}
