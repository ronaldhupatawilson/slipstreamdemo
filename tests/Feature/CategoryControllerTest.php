<?php

namespace Tests\Feature;

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Requests\CategoryRequest;
use App\Resources\CategoryResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class CategoryControllerTest extends TestCase
{

 use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new CategoryController();
    }

    public function testIndex()
    {
        // Create some test categories
        Category::factory()->count(5)->create();

        $request = Request::create('/categories', 'GET');
        $response = $this->controller->index($request);

        $this->assertEquals('Category/Index', $response->getName());
        $this->assertArrayHasKey('categories', $response->getData());
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $response->getData()['categories']);
    }


    public function testStore()
    {
        $categoryData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ];

        $request = new CategoryRequest($categoryData);

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('categories', $categoryData);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('Category was created', session('success'));
    }
    
    
   public function testShow()
    {
        $category = Category::factory()->create();

        $response = $this->controller->show($category);

        $this->assertEquals('Category/Show', $response->getName());
        $this->assertArrayHasKey('category', $response->getData());
        $this->assertInstanceOf(CategoryResource::class, $response->getData()['category']);
    }

    public function testUpdate()
    {
        $category = Category::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9876543210',
        ];

        $request = new CategoryRequest($updatedData);

        $response = $this->controller->update($request, $category);

        $this->assertDatabaseHas('categories', $updatedData);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals("Category "Updated Name" was updated", session('success'));
    }

    public function testDestroy()
    {
        $category = Category::factory()->create([
            'name' => 'To Be Deleted',
            'image_path' => 'categories/to_be_deleted.jpg'
        ]);

        Storage::fake('public');

        $response = $this->controller->destroy($category);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertTrue($response->isRedirect());
        $this->assertEquals("Category "To Be Deleted" was deleted", session('success'));
        Storage::disk('public')->assertDirectoryEmpty('categories');
    }
}
    