<?php

use App\Customer;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomersControllerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function it_lists_customers()
    {
        // Arrange
        $customers = factory(Customer::class, 10)->create();

        // Act
        $this->visit('/customers')
            ->seeStatusCode(200);

        // Assert
        foreach ($customers as $customer) {
            $this->see($customer->first_name)
                ->see($customer->last_name)
                ->see($customer->email)
                ->see($customer->gender);
        }
    }

    /** @test */
    public function it_creates_customers()
    {
        // Act
        $this->visit('/customers/create')
            ->type('John', 'first_name')
            ->type('Doe', 'last_name')
            ->type('john@doe.tld', 'email')
            ->select('Gender 1', 'gender')
            ->type('1.1.1.1', 'last_ip')
            ->press('submit')
            ->seeStatusCode(200);
    
        // Assert
        $this->seeInDatabase('customers', [
            'first_name' => 'John', 
            'last_name' => 'Doe', 
            'email' => 'john@doe.tld', 
            'gender' => 'Gender 1', 
            'last_ip' => '1.1.1.1'
        ]);
    }

    /** @test */
    public function it_validates_inputs_when_creating_customer()
    {
        // Act
        $this->visit('/customers/create')
            ->select('Gender 1', 'gender')
            ->type('1.1.1.1', 'last_ip')
            ->press('submit')
            ->seeStatusCode(200)
            ->see('Oops!');

        // Assert
        $this->dontSeeInDatabase('customers', [
            'first_name' => 'John', 
            'last_name' => 'Doe', 
            'email' => 'john@doe.tld'
        ]);
    }

    /** @test */
    public function it_updates_a_user()
    {
        // Arrange
        $customer = factory(Customer::class)->create();

        // Act
        $this->visit("/customers/{$customer->id}/edit")
            ->type('John', 'first_name')
            ->type('Doe', 'last_name')
            ->type('john@doe.tld', 'email')
            ->select('Gender 1', 'gender')
            ->type('1.1.1.1', 'last_ip')
            ->press('submit')
            ->seeStatusCode(200);
    
        // Assert
        $this->seeInDatabase('customers', [
            'first_name' => 'John', 
            'last_name' => 'Doe', 
            'email' => 'john@doe.tld', 
            'gender' => 'Gender 1', 
            'last_ip' => '1.1.1.1'
        ]);

        $this->assertEquals(1, Customer::count());
    }

    /** @test */
    public function it_deletes_a_user()
    {
        // Arrange
        $customer = factory(Customer::class)->create();

        // Act
        $this->delete("/customers/{$customer->id}")
            ->assertRedirectedToRoute('customers.index');
            
        // Assert
        $this->assertEquals(0, Customer::count());
    }
}
