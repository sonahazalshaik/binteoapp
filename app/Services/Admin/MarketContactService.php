<?php

namespace App\Services\Admin;

use App\Models\MarketContact;

class MarketContactService
{
    public function list()
    {
        return MarketContact::with('marketplace')
            ->searchable(['name', 'email', 'phone', 'subject', 'marketplace:name'])
            ->latest()
            ->paginate(10);
    }

    public function create(array $data)
    {
        return MarketContact::create($data);
    }

    public function find($id)
    {
        return MarketContact::with('marketplace')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $contact = MarketContact::findOrFail($id);
        $contact->update($data);

        return $contact;
    }

    public function delete($id)
    {
        $contact = MarketContact::findOrFail($id);
        $contact->delete();

        return $contact;
    }
}
