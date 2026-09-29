<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MarketTransactionService;
use Illuminate\Http\Request;

class MarketTransactionController extends Controller
{
    protected $service;

    public function __construct(MarketTransactionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle = 'Marketplace Transactions';
        $transactions = $this->service->list();
        return view('admin.marketplace.transaction.index', compact('pageTitle', 'transactions'));
    }
}
