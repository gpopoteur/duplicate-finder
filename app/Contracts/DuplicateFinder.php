<?php

namespace App\Contracts;

use App\Customer;

interface DuplicateFinder {

    /**
     * Finds possible duplicates for a customer
     * @param  Customer $customer Customer instance to look for duplicates 
     * @return array
     */
    public function findDuplicates(Customer $customer);
    
}