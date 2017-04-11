<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Contracts\CustomerRepository;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomersController extends Controller
{

    /**
     * @var CustomerRepository $customers
     */
    private $customers;

    /**
     * Constructs a CustomersController instance
     * @param CustomerRepository $customers
     */
    public function __construct(CustomerRepository $customers)
    {
        $this->customers = $customers;
    }

    /**
     * Lists all customers in the Database
     * @return view 
     */
    public function index()
    {
        $customers = $this->customers->paginate(20);

        return view('customers.index', compact('customers'));
    }

    /**
     * Renders the Form to create a new Customer
     * @return view
     */
    public function new()
    {
        return view('customers.new')
                ->withCustomer(new Customer);
    }

    /**
     * Stores a Customer
     * @param  CreateCustomerRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(CreateCustomerRequest $request)
    {
        $this->customers->create(
            $request->only('first_name', 'last_name', 'email', 'last_ip', 'gender')
        );

        return redirect()
                ->route('customers.index')
                ->withMessage([ 'text' => 'Customer Created! :)' ]);
    }

    /**
     * Renders a Form to edit a customer
     * @param  int $customerId 
     * @return view
     */
    public function edit($customerId)
    {
        $customer = $this->customers->find($customerId);

        return view('customers.edit')
                ->withCustomer($customer);
    }

    /**
     * Updates a customer with the provided information
     * @param  UpdateCustomerRequest  $request  
     * @param  int $customerId 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCustomerRequest $request, $customerId)
    {
        $this->customers->update(
            $customerId, 
            $request->only('first_name', 'last_name', 'email', 'last_ip', 'gender')
        );

        return redirect()
                ->route('customers.index')
                ->withMessage([ 'text' => 'Customer Updated! :)' ]);
    }

    /**
     * Deletes a Customer from the platform
     * @param  int $customerId 
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete($customerId)
    {
        $this->customers->delete($customerId);

        return redirect()
                ->route('customers.index')
                ->withMessage([ 'text' => 'Customer Deleted!' ]);
    }
}
