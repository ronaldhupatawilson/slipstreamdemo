<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;

class ContactController extends Controller
{
    /**
     * Display a listing contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sortField = request("sort_field", 'created_at');
        $sortDirection = request("sort_direction", "desc");
        $query = Contact::query();

 
        $query->leftJoin('customers','customers.id', '=', 'contacts.customer_id')
            ->select ('contacts.*', 'customers.name as customersname');

        if (request('firstName')) {
            $query->where("firstName", "like", "%" . request("firstName") ."%");
        }
        if (request('lastName')) {
            $query->where("lastName", "like", "%" . request("lastName") ."%");
        }

        if (request('customersname')) {
                $query->where("customers.name", "like", "%" . request("customersname") ."%");
        }


        $contacts = $query->orderBy($sortField, $sortDirection)
            ->paginate(20)
            ->onEachSide(1);

        return inertia("Contact/Index", [
            "contacts" => ContactResource::collection($contacts),

            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::query()->orderBy('name', 'asc')->get();


        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));
        
        session()->put('previous_route', [
            'name' => $route->getName(),
            'params' => $route->parameters()
        ]);

        return inertia("Contact/Create", [
            'customers' => CustomerResource::collection($customers)

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        $data = $request->validated();
       
        Contact::create($data);
        
        $previousRoute = session()->pull('previous_route', ['name' => 'contact.index', 'params' => []]);

        return to_route($previousRoute['name'], $previousRoute['params'])
            ->with('success', 'Contact was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {

        $query = Contact::query();

        $query->leftJoin('customers','customers.id', '=', 'contacts.customer_id')
            ->select ('contacts.*', 'customers.name as customernames');
        $query->where('contacts.id', $contact->id);
        $contactData = $query->first();
        
        
        
        
        return inertia("Contact/Show", [
            "contact" => new ContactResource($contactData),

            'queryParams' => request()->query() ?: null
]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        $query = Contact::query();
        $query->leftJoin('customers','customers.id', '=', 'contacts.customer_id')
            ->select ('contacts.*', 'customers.name as customersname');
        $query->where('contacts.id', $contact->id);
        $contactData = $query->first();
        
        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));
        
        session()->put('previous_route', [
            'name' => $route->getName(),
            'params' => $route->parameters()
        ]);

        $customers = Customer::query()->orderBy('name', 'asc')->get();

        return inertia("Contact/Edit", [
            "contact" => new ContactResource($contactData),
           'customers' => CustomerResource::collection($customers),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactRequest $request, Contact $contact)
    {
        $data = $request->validated();
        $contact->update($data);

        $previousRoute = session()->pull('previous_route', ['name' => 'location.index', 'params' => []]);

        return to_route($previousRoute['name'], $previousRoute['params'])
            ->with('success', "Contact \"$contact->First Name\" was updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $name = $contact->name;
        $contact->delete();
        if ($contact->image_path) {
            Storage::disk('public')->deleteDirectory(dirname($contact->image_path));
        }
        $referrer = url()->previous();
        $route = app('router')->getRoutes()->match(request()->create($referrer));

        return to_route($route->getName(), $route->parameters())
            ->with('success', "Contact \"$name\" was deleted");
    }
}
    