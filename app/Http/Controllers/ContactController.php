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
        $contact = Contact::create($data);
        return response()->json($contact);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contactId = request()->input('id');
        $contact = Contact::find($contactId);
        try {
            $contact->delete();
            return response()->json(['success' => true, 'message' => 'Customer deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete contact'], 500);
        }
    }
}
    