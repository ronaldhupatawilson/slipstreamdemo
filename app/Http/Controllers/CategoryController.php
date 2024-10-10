<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;

class CategoryController extends Controller
{
    /**
     * Display a listing category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sortField = request("sort_field", 'created_at');
        $sortDirection = request("sort_direction", "desc");
        $query = Category::query();

 

        if (request('category')) {
            $query->where("category", "like", "%" . request("category") ."%");
        }


        $categories = $query->orderBy($sortField, $sortDirection)
            ->paginate(20)
            ->onEachSide(1);

        return inertia("Category/Index", [
            "categories" => CategoryResource::collection($categories),

            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));
        
        session()->put('previous_route', [
            'name' => $route->getName(),
            'params' => $route->parameters()
        ]);

        return inertia("Category/Create", [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
       
        Category::create($data);
        
        $previousRoute = session()->pull('previous_route', ['name' => 'category.index', 'params' => []]);

        return to_route($previousRoute['name'], $previousRoute['params'])
            ->with('success', 'Category was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $query = $category->customers();
        $customers_sortField = request("customers_sort_field", 'id');
        $customers_sortDirection = request("customers_sort_direction", "desc");
        $customers = $query->orderBy($customers_sortField, $customers_sortDirection)
            ->paginate(20)
            ->onEachSide(1);
            

        $query = Category::query();
        $query->where('categories.id', $category->id);
        $categoryData = $query->first();
        
        
        
        
        return inertia("Category/Show", [
            "category" => new CategoryResource($categoryData),
            'customers' => CustomerResource::collection($customers),

            'queryParams' => request()->query() ?: null
]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $query = Category::query();
        $query->where('categories.id', $category->id);
        $categoryData = $query->first();
        
        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));
        
        session()->put('previous_route', [
            'name' => $route->getName(),
            'params' => $route->parameters()
        ]);


        return inertia("Category/Edit", [
            "category" => new CategoryResource($categoryData),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);

        $previousRoute = session()->pull('previous_route', ['name' => 'location.index', 'params' => []]);

        return to_route($previousRoute['name'], $previousRoute['params'])
            ->with('success', "Category \"$category->Category\" was updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();
        if ($category->image_path) {
            Storage::disk('public')->deleteDirectory(dirname($category->image_path));
        }
        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));

        return to_route($route->getName(), $route->parameters())
            ->with('success', "Category \"$name\" was deleted");
    }
}
    