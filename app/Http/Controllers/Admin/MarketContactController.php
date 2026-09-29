<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketPlace;
use App\Services\Admin\MarketContactService;
use Illuminate\Http\Request;

class MarketContactController extends Controller
{
    protected $service;

    public function __construct(MarketContactService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Marketplace Contacts';
        $contacts = $this->service->list();
        return view('admin.marketplace.contact.index', compact('pageTitle', 'contacts'));
    }

    public function create()
    {
        $pageTitle = 'Add Inquiry';
        $talents = MarketPlace::active()->get();
        return view('admin.marketplace.contact.create', compact('pageTitle', 'talents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'subject'        => 'required|string|max:255',
            'message'        => 'required|string',
        ]);

        $this->service->create($request->all());

        $notify[] = ['success', 'Inquiry logged successfully.'];
        return redirect()->route('admin.marketplace.contacts.index')->withNotify($notify);
    }

    public function show($id)
    {
        $contact = $this->service->find($id);
        $pageTitle = 'Inquiry Details: ' . $contact->subject;
        return view('admin.marketplace.contact.show', compact('contact', 'pageTitle'));
    }

    public function edit($id)
    {
        $contact = $this->service->find($id);
        $pageTitle = 'Edit Inquiry: ' . $contact->subject;
        $talents = MarketPlace::active()->get();
        return view('admin.marketplace.contact.edit', compact('contact', 'pageTitle', 'talents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'marketplace_id' => 'required|exists:market_places,id',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'subject'        => 'required|string|max:255',
            'message'        => 'required|string',
        ]);

        $this->service->update($id, $request->all());

        $notify[] = ['success', 'Inquiry revised successfully.'];
        return redirect()->route('admin.marketplace.contacts.index')->withNotify($notify);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        $notify[] = ['success', 'Inquiry deleted successfully.'];
        return back()->withNotify($notify);
    }
}
