<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        return view('admin.payments.index');
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        return view('admin.payments.create');
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        // Payment creation logic
        return redirect()->route('admin.payments.index')->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        return view('admin.payments.show', compact('id'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit($id)
    {
        return view('admin.payments.edit', compact('id'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, $id)
    {
        // Payment update logic
        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy($id)
    {
        // Payment deletion logic
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
    }
}
