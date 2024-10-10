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
    