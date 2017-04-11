<?php

use App\Customer;
use App\Repositories\EloquentCustomerRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EloquentCustomerRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    
    protected $repository;

    public function __construct()
    {
        $this->repository = new EloquentCustomerRepository( new Customer );
    }

    /** @test */
    public function it_finds_a_customer()
    {
        // Arrange
        $customer = factory(Customer::class)->create();

        // Act
        $found = $this->repository->find($customer->id);
    
        // Assert
        $this->assertEquals($found->id, $customer->id);
    }

    /** @test */
    public function it_creates_a_customer()
    {
        // Arrange
        $customer = factory(Customer::class)->make();

        // Act
        $created = $this->repository->create([
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'gender' => $customer->gender,
            'last_ip' => $customer->last_ip,
        ]);
    
        // Assert
        $this->assertNotNull($created->id);
        $this->assertEquals($created->first_name, $customer->first_name);
        $this->assertEquals($created->last_name, $customer->last_name);
        $this->assertEquals($created->email, $customer->email);
        $this->assertEquals($created->gender, $customer->gender);
        $this->assertEquals($created->last_ip, $customer->last_ip);
    }

    /** @test */
    public function it_updates_a_customer()
    {
        // Arrange
        $customer = factory(Customer::class)->create();
        $newData = factory(Customer::class)->make();

        // Act
        $this->repository->update($customer->id, [
            'first_name' => $newData->first_name,
            'last_name' => $newData->last_name,
            'email' => $newData->email,
            'gender' => $newData->gender,
            'last_ip' => $newData->last_ip,
        ]);

        // Assert
        $customer = $customer->fresh();
        $this->assertNotNull($customer->id);
        $this->assertEquals($customer->first_name, $newData->first_name);
        $this->assertEquals($customer->last_name, $newData->last_name);
        $this->assertEquals($customer->email, $newData->email);
        $this->assertEquals($customer->gender, $newData->gender);
        $this->assertEquals($customer->last_ip, $newData->last_ip);

        $this->assertEquals(1, Customer::count());
    }

    /** @test */
    public function it_deletes_a_customer()
    {
        // Arrange
        $customer = factory(Customer::class)->create();

        // Act
        $this->repository->delete($customer->id);
    
        // Assert
        $this->assertNull(Customer::first());
        $this->assertEquals(0, Customer::count());
    }

    /** @test */
    public function it_finds_possible_duplicates()
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
        $duplicate1 = factory(Customer::class)->create([
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
        ]);

        $duplicate2 = factory(Customer::class)->create([
            'email' => $customer->email
        ]);

        $duplicate3 = factory(Customer::class)->create([
            'last_ip' => $customer->last_ip
        ]);

        $duplicates = collect([$duplicate1, $duplicate2, $duplicate3]);

        // Act
        $results = $this->repository->findPossibleDuplicates($customer);

        // Assert
        // Query should only return 3 items
        $this->assertEquals( 3, $results->count() );
        $this->assertEquals( $results->pluck('id'), $duplicates->pluck('id') );
    }

     /** @test */
    public function it_returns_130_if_emails_are_equals()
    {
        // Arrange
        $customer = factory(Customer::class)->create([
            'email' => 'test@email.tld',
            'gender' => 'Gender 1'
        ]);

        $duplicate = factory(Customer::class)->create([
            'email' => 'test@email.tld',
            'gender' => 'Gender 2'
        ]);
        
        // Act
        $weight = $this->repository->compare($customer, $duplicate);
        
        // Assert
        $this->assertEquals(130, $weight);
    }

    /** @test */
    public function it_returns_30_if_local_part_of_email_are_equals()
    {
        // Arrange
        $customer = factory(Customer::class)->create([
            'email' => 'test@someemail.tld',
            'gender' => 'Gender 1'
        ]);

        $duplicate = factory(Customer::class)->create([
            'email' => 'test@otheremail.tld',
            'gender' => 'Gender 2'
        ]);
        
        // Act
        $weight = $this->repository->compare($customer, $duplicate);
        
        // Assert
        $this->assertEquals(30, $weight);
    }

    /** @test */
    public function it_returns_20_if_first_names_are_equals()
    {
        // Arrange
        $customer = factory(Customer::class)->create([
            'first_name' => 'Person',
            'gender' => 'Gender 1'
        ]);

        $duplicate = factory(Customer::class)->create([
            'first_name' => 'Person',
            'gender' => 'Gender 2'
        ]);
        
        // Act
        $weight = $this->repository->compare($customer, $duplicate);
        
        // Assert
        $this->assertEquals(20, $weight);
    }

    /** @test */
    public function it_returns_10_if_last_names_are_equals()
    {
        // Arrange
        $customer = factory(Customer::class)->create([
            'last_name' => 'LastName',
            'gender' => 'Gender 1'
        ]);

        $duplicate = factory(Customer::class)->create([
            'last_name' => 'LastName',
            'gender' => 'Gender 2'
        ]);
        
        // Act
        $weight = $this->repository->compare($customer, $duplicate);
        
        // Assert
        $this->assertEquals(10, $weight);
    }

    /** @test */
    public function it_returns_10_if_gender_are_equals()
    {
        // Arrange
        $customer = factory(Customer::class)->create([
            'gender' => 'Gender 1'
        ]);

        $duplicate = factory(Customer::class)->create([
            'gender' => 'Gender 1'
        ]);
        
        // Act
        $weight = $this->repository->compare($customer, $duplicate);
        
        // Assert
        $this->assertEquals(10, $weight);
    }

    /** @test */
    public function it_returns_15_if_last_ip_are_equals()
    {
        // Arrange
        $customer = factory(Customer::class)->create([
            'last_ip' => '1.1.1.1',
            'gender' => 'Gender 1'
        ]);

        $duplicate = factory(Customer::class)->create([
            'last_ip' => '1.1.1.1',
            'gender' => 'Gender 2'
        ]);
        
        // Act
        $weight = $this->repository->compare($customer, $duplicate);
        
        // Assert
        $this->assertEquals(15, $weight);
    }

    /** @test */
    public function it_adds_results_if_multiple_conditions_are_found()
    {
        // Arrange
        $customer = factory(Customer::class)->create([
            'last_ip' => '1.1.1.1',
            'gender' => 'Gender 1',
            'first_name' => 'Person',
            'last_name' => 'Bar',
        ]);

        $duplicate = factory(Customer::class)->create([
            'last_ip' => '1.1.1.1',
            'gender' => 'Gender 1',
            'first_name' => 'Person',
            'last_name' => 'Bar',
        ]);
        
        // Act
        $weight = $this->repository->compare($customer, $duplicate);
        
        // Assert
        $this->assertEquals(55, $weight);
    }
}
