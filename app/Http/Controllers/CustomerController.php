<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Contact;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerSql = "select customers.id as id, name, reference, category, (select count(*) from contacts where contacts.customer_id = customers.id) as contactscount
        from customers
        left join categories on categories.id = customers.category_id
        ";
        $customers = DB::select($customerSql);

        $categories = Category::query()->orderBy('category', 'asc')->get();


        return inertia("Customers", [
            "customers" => $customers,
            "categories" => CategoryResource::collection($categories)
        ]);
    }

    /**
     * Search for customers based on the provided search text.
     *
     * @param string $searchText
     * @return \Illuminate\Http\Response
     */
    public function search($searchText)
    {
        $customerSql = "select customers.id as id, name, reference, category, category_id, startDate, description, (select count(*) from contacts where contacts.customer_id = customers.id) as contactscount
        from customers
        left join categories on categories.id = customers.category_id
        where name like '%?%' or reference like '%?%' or category like '%?%' or startDate like '%?%'
        ";
        $customers = DB::select($customerSql,[$searchText, $searchText, $searchText, $searchText]);
        return inertia("Customers", [
            "customers" => $customers,
            "searchText" => $searchText
        ]);
    }

    /**
     * Retrieve contacts for a specific customer.
     *
     * @param int $customerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerContacts($customerId)
    {
        $contacts = Contact::where('customer_id', $customerId)->get();
        return response()->json($contacts);
    }



    /**
     * Store a newly created customer in storage.
     *
     * @param \App\Http\Requests\CustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $data = $request->validated();
       
        Customer::create($data);
        
        $previousRoute = session()->pull('previous_route', ['name' => 'customer.index', 'params' => []]);

        return to_route($previousRoute['name'], $previousRoute['params'])
            ->with('success', 'Customer was created');
    }


    /**
     * Update the specified customer in storage.
     *
     * @param \App\Http\Requests\CustomerRequest $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($customerId)
    {
        $data = $request->validated();
        $customer->update($data);

        $previousRoute = session()->pull('previous_route', ['name' => 'location.index', 'params' => []]);

        return to_route($previousRoute['name'], $previousRoute['params'])
            ->with('success', "Customer \"$customer->Name\" was updated");
    }

    /**
     * Remove the specified customer from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        $customerId = request()->input('id');
        $customer = Customer::find($customerId);
        try {
            $customer->delete();
            return response()->json(['success' => true, 'message' => 'Customer deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete customer'], 500);
        }
    }
}
