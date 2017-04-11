<?php

namespace App\Contracts;

use App\Customer;

interface CustomerRepository {

    /**
     * Paginates customers results
     * @param  integer $qty Quantity of results to return
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($qty = 25);

    /**
     * Finds a Customer by ID
     * @param  int  $id     Customer ID 
     * @return mixed
     */
    public function find(int $id);

    /**
     * Creates a customer
     * @param  array  $data 
     * @return mixed
     */
    public function create(array $data);

    /**
     * Updates a customer information
     * @param  int $id   Customer ID
     * @param  array  $data 
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Deletes a customer
     * @param  int $id  Customer ID
     * @return mixed
     */
    public function delete($id);

    /**
     * Finds possible duplicates of a Customer
     * @param  Customer $customer
     * @return mixed
     */
    public function findPossibleDuplicates(Customer $customer);

    /**
     * Compares a customer with another one following 
     * a set of defined rules, returns a weight
     *
     * @param  Customer $customer          Customer to compare against
     * @param  Customer $customerToCompare Customer to compare against
     * @return int             Comparison's weight
     */
    public function compare(Customer $customer, Customer $customerToCompare);
    
}