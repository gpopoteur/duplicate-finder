<?php

namespace App\Finders;

use App\Customer;
use App\Contracts\CustomerRepository;
use App\Contracts\DuplicateFinder as DuplicateFinderContract;

class DuplicateFinder implements DuplicateFinderContract {

    /**
     * @var \App\Contracts\CustomerRepository
     */
    private $customers;

    /**
     * Threshold to what is defined as a possible duplicate
     */
    const THRESHOLD = 50;

    /**
     * Constructs the DuplicateFinder
     * @param CustomerRepository $customers
     */
    public function __construct(CustomerRepository $customers)
    {
        $this->customers = $customers;
    }

    /**
     * Finds possible duplicates for a customer
     * @param  Customer $customer Customer instance to look for duplicates 
     * @return Illuminate\Support\Collection
     */
    public function findDuplicates(Customer $customer) {
        // Find possible duplicates in the Database
        $possibleDuplicates = $this->customers->findPossibleDuplicates($customer);

        // Compare the possible duplicates with the current customer, 
        // if weight greater than or equals the THRESHOLD, 
        // we might have a duplicate!
        $possibleDuplicates = $possibleDuplicates->filter(function ($customerDuplicate) use ($customer) {
            return $this->customers->compare($customer, $customerDuplicate) >= DuplicateFinder::THRESHOLD;
        });
        
        return $possibleDuplicates;
    }

}