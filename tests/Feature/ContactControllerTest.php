<?php

namespace Tests\Feature;

use App\Http\Controllers\ContactController;
use App\Models\Contact;
use App\Requests\ContactRequest;
use App\Resources\ContactResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class ContactControllerTest extends TestCase
{

 use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ContactController();
    }

    public function testIndex()
    {
        // Create some test contacts
        Contact::factory()->count(5)->create();

        $request = Request::create('/contacts', 'GET');
        $response = $this->controller->index($request);

        $this->assertEquals('Contact/Index', $response->getName());
        $this->assertArrayHasKey('contacts', $response->getData());
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $response->getData()['contacts']);
    }


    public function testStore()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ];

        $request = new ContactRequest($contactData);

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('contacts', $contactData);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('Contact was created', session('success'));
    }
    
    
   public function testShow()
    {
        $contact = Contact::factory()->create();

        $response = $this->controller->show($contact);

        $this->assertEquals('Contact/Show', $response->getName());
        $this->assertArrayHasKey('contact', $response->getData());
        $this->assertInstanceOf(ContactResource::class, $response->getData()['contact']);
    }

    public function testUpdate()
    {
        $contact = Contact::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9876543210',
        ];

        $request = new ContactRequest($updatedData);

        $response = $this->controller->update($request, $contact);

        $this->assertDatabaseHas('contacts', $updatedData);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals("Contact "Updated Name" was updated", session('success'));
    }

    public function testDestroy()
    {
        $contact = Contact::factory()->create([
            'name' => 'To Be Deleted',
            'image_path' => 'contacts/to_be_deleted.jpg'
        ]);

        Storage::fake('public');

        $response = $this->controller->destroy($contact);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals("Contact "To Be Deleted" was deleted", session('success'));
        Storage::disk('public')->assertDirectoryEmpty('contacts');
    }
}
    