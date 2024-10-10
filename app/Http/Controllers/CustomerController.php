<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;

class CustomerController extends Controller
{
    /**
     * Display a listing customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sortField = request("sort_field", 'created_at');
        $sortDirection = request("sort_direction", "desc");
        $query = Customer::query();

 
        $query->leftJoin('categories','categories.id', '=', 'customers.category_id')
            ->select ('customers.*', 'categories.category as categoriescategory');

        if (request('name')) {
            $query->where("name", "like", "%" . request("name") ."%");
        }
        if (request('reference')) {
            $query->where("reference", "like", "%" . request("reference") ."%");
        }
        if (request('startDate')) {
            $query->where("startDate", request("startDate"));
        }
        if (request('description')) {
            $query->where("description", "like", "%" . request("description") ."%");
        }

        if (request('categoriescategory')) {
                $query->where("categories.category", "like", "%" . request("categoriescategory") ."%");
        }


        $customers = $query->orderBy($sortField, $sortDirection)
            ->paginate(20)
            ->onEachSide(1);

        return inertia("Customer/Index", [
            "customers" => CustomerResource::collection($customers),

            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::query()->orderBy('category', 'asc')->get();


        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));
        
        session()->put('previous_route', [
            'name' => $route->getName(),
            'params' => $route->parameters()
        ]);

        return inertia("Customer/Create", [
            'categories' => CategoryResource::collection($categories)

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->validated();
       
        Customer::create($data);
        
        $previousRoute = session()->pull('previous_route', ['name' => 'customer.index', 'params' => []]);

        return to_route($previousRoute['name'], $previousRoute['params'])
            ->with('success', 'Customer was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $query = $customer->contacts();
        $contacts_sortField = request("contacts_sort_field", 'id');
        $contacts_sortDirection = request("contacts_sort_direction", "desc");
        $contacts = $query->orderBy($contacts_sortField, $contacts_sortDirection)
            ->paginate(20)
            ->onEachSide(1);
            

        $query = Customer::query();

        $query->leftJoin('categories','categories.id', '=', 'customers.category_id')
            ->select ('customers.*', 'categories.category as categorycategories');
        $query->where('customers.id', $customer->id);
        $customerData = $query->first();
        
        
        
        
        return inertia("Customer/Show", [
            "customer" => new CustomerResource($customerData),
            'contacts' => ContactResource::collection($contacts),

            'queryParams' => request()->query() ?: null
]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $query = Customer::query();
        $query->leftJoin('categories','categories.id', '=', 'customers.category_id')
            ->select ('customers.*', 'categories.category as categoriescategory');
        $query->where('customers.id', $customer->id);
        $customerData = $query->first();
        
        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));
        
        session()->put('previous_route', [
            'name' => $route->getName(),
            'params' => $route->parameters()
        ]);

        $categories = Category::query()->orderBy('category', 'asc')->get();

        return inertia("Customer/Edit", [
            "customer" => new CustomerResource($customerData),
           'categories' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();
        $customer->update($data);

        $previousRoute = session()->pull('previous_route', ['name' => 'location.index', 'params' => []]);

        return to_route($previousRoute['name'], $previousRoute['params'])
            ->with('success', "Customer \"$customer->Name\" was updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $name = $customer->name;
        $customer->delete();
        if ($customer->image_path) {
            Storage::disk('public')->deleteDirectory(dirname($customer->image_path));
        }
        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));

        return to_route($route->getName(), $route->parameters())
            ->with('success', "Customer \"$name\" was deleted");
    }
}
    