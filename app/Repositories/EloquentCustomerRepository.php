<?php

namespace App\Repositories;

use App\Customer;
use App\Contracts\CustomerRepository;

class EloquentCustomerRepository implements CustomerRepository {

    /**
     * @var App\Customer
     */
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Paginates customers results
     * @param  integer $qty Quantity of results to return
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($qty = 25){
        return $this->customer->paginate($qty);
    }

    /**
     * Finds a Customer by ID
     * @param  int  $id     Customer ID 
     * @return mixed
     */
    public function find(int $id){
        return $this->customer->findOrFail($id);
    }

    /**
     * Creates a customer
     * @param  array  $data 
     * @return mixed
     */
    public function create(array $data){
        return $this->customer->create($data);
    }

    /**
     * Updates a customer information
     * @param  int $id   Customer ID
     * @param  array  $data 
     * @return mixed
     */
    public function update($id, array $data){
        $customer = $this->find($id);
        $customer->fill($data);
        return $customer->save();
    }

    /**
     * Deletes a customer
     * @param  int $id  Customer ID
     * @return mixed
     */
    public function delete($id){
        $customer = $this->find($id);
        return $customer->delete();
    }

    /**
     * Finds possible duplicates of a Customer
     * @param  Customer $customer
     * @return mixed
     */
    public function findPossibleDuplicates(Customer $customer)
    {
        // Duplicates will almost ever have the same first name
        // and the email might start with the same string, so, 
        // Let's find all customers that the email start the same
        // as this customer's OR al customers with the same first 
        // name and last name as this customer OR customers that 
        // used the same IP Address, AND is not this customer.
        
        $bindings = [
            'id' => $customer->id,
            'email' => $customer->emailLocalPart . '%',
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'last_ip' => $customer->last_ip,
        ];

        return $this->findDuplicatesQuery($bindings);
    }

    /**
     * Runs a query to fetch possible duplicates 
     * from the Database
     * @param  array $bindings 
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function findDuplicatesQuery(array $bindings)
    {
        $result = \DB::select('
            select * from customers 
            where id <> :id 
            and ( 
                email like :email 
                or ( first_name = :first_name 
                    and last_name = :last_name ) 
                or ( last_ip = :last_ip ) 
            )', $bindings);

        return Customer::hydrate($result);
    }

    /**
     * Compares a customer with another one following 
     * a set of defined rules, returns a weight
     *
     * Rules:
     * - Weight starts at 0.
     * - If emails are the same or really similar, we found a duplicate, add 100 to weight
     * - If local-part of the email are equals or really similar, add 30 to weight
     * - If First names are equals or really similar, add 20 to weight
     * - If Last names are equals or really similar, add 10 to weight
     * - If Gender are equals or really similar, add 10 to weight
     * - If Last IPs are equals or really similar, add 10 to weight
     * 
     * @param  Customer $customer          Customer to compare against
     * @param  Customer $customerToCompare Customer to compare against
     * @return int             Comparison's weight
     */
    public function compare(Customer $customer, Customer $customerToCompare)
    {
        $totalWeight = 0;

        $weightMap = [
            'email'             => 100,
            'email_local_part'  => 30,
            'first_name'        => 20,
            'last_name'         => 10,
            'gender'            => 10,
            'last_ip'           => 15,
        ];

        foreach ($weightMap as $key => $value) {
            // Check similarity between the two texts
            similar_text($customer->{$key}, $customerToCompare->{$key}, $similarityPercent);
            
            $totalWeight += $similarityPercent > 90 ? $weightMap[$key] : 0;
        }

        return $totalWeight;
    }

}