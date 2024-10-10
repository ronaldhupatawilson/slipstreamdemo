<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerController;
use App\Models\Customer;
use App\Requests\CustomerRequest;
use App\Resources\CustomerResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class CustomerControllerTest extends TestCase
{

 use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new CustomerController();
    }

    public function testIndex()
    {
        // Create some test customers
        Customer::factory()->count(5)->create();

        $request = Request::create('/customers', 'GET');
        $response = $this->controller->index($request);

        $this->assertEquals('Customer/Index', $response->getName());
        $this->assertArrayHasKey('customers', $response->getData());
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $response->getData()['customers']);
    }


    public function testStore()
    {
        $customerData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ];

        $request = new CustomerRequest($customerData);

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('customers', $customerData);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('Customer was created', session('success'));
    }
    
    
   public function testShow()
    {
        $customer = Customer::factory()->create();

        $response = $this->controller->show($customer);

        $this->assertEquals('Customer/Show', $response->getName());
        $this->assertArrayHasKey('customer', $response->getData());
        $this->assertInstanceOf(CustomerResource::class, $response->getData()['customer']);
    }

    public function testUpdate()
    {
        $customer = Customer::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9876543210',
        ];

        $request = new CustomerRequest($updatedData);

        $response = $this->controller->update($request, $customer);

        $this->assertDatabaseHas('customers', $updatedData);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals("Customer "Updated Name" was updated", session('success'));
    }

    public function testDestroy()
    {
        $customer = Customer::factory()->create([
            'name' => 'To Be Deleted',
            'image_path' => 'customers/to_be_deleted.jpg'
        ]);

        Storage::fake('public');

        $response = $this->controller->destroy($customer);

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals("Customer "To Be Deleted" was deleted", session('success'));
        Storage::disk('public')->assertDirectoryEmpty('customers');
    }
}
    