<?php

use App\Customer;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DuplicateFinderTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->duplicateFinder = new \App\Finders\DuplicateFinder(
            new \App\Repositories\EloquentCustomerRepository(
                new Customer
            )
        );
    }
    
    /** @test */
    public function it_calculates_weight_and_returns_only_those_greater_than_the_threshold()
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
            'last_name' => $customer->last_name,
            'email' => $customer->email
        ]);

        $customers = $customers->merge( $duplicates );
        
        $customerRepositoryMock = Mockery::mock('App\Repositories\EloquentCustomerRepository[findPossibleDuplicates]', [new Customer]);

        $customerRepositoryMock->shouldReceive('findPossibleDuplicates')
                                ->once()
                                ->andReturn( $customers );

        // Act
        $duplicatesFound =  ( new \App\Finders\DuplicateFinder($customerRepositoryMock) )->findDuplicates($customer);
        
        // Assert
        $this->assertEquals(5, $duplicatesFound->count());
    }
}
