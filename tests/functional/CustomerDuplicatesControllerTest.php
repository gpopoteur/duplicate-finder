<?php

use App\Customer;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerDuplicatesControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function it_lists_possible_duplicated_customers()
    {
        // Arrange
        $customer = factory(Customer::class)->create([
            'first_name' => 'Person',
            'last_name' => 'LastName',
            'email' => 'person@lastname.tld'
        ]);

        // Not duplicates
        $customers = factory(Customer::class, 5)->create();

        // Duplicates
        $duplicates = factory(Customer::class, 5)->create([
            'first_name' => $customer->first_name,
            'email' => $customer->email,
            'gender' => $customer->gender,
        ]);

        // Act
        $this->visit("/customers/{$customer->id}/duplicates")
            ->seeStatusCode(200);

        // Assert
        // Make sure that all the new last names are rendered
        foreach ($duplicates as $customer) {
            $this->see($customer->last_name);
        }
    }
}
