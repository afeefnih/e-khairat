<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use Illuminate\Http\Request;
use Toyyibpay;

class InfaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {




        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // Create a ToyyibPay bill
        $code = config('toyyibpay.category_codes.infaq_khairat'); // Get category code from config

        $amount = $request->input('amount') * 100; // Convert amount to cents
        session(['infaq_amount' => $amount]);


        $billData = [
            'billName' => 'Infaq to Masjid Taman Sutera',
            'billDescription' => 'Infaq of RM ' . $request->input('amount'),
            'billPriceSetting' => 1, // Fixed amount
            'billPayorInfo' => 0,
            'billAmount' => $amount,
            'billReturnUrl' => route('infaq.callback'), // Redirect after payment
            'billExternalReferenceNo' => uniqid(), // Unique reference
            'billTo' => '', // Allow the user to enter their name
            'billEmail' => '', // Allow the user to enter their email
            'billPhone' => '', // Allow the user to enter their phone number
            'billContentEmail'=>'Terima Kasih kerana telah berinfaq kepada Masjid Taman Sutera. Jazakallahu Khairan.',

        ];

        $data = Toyyibpay::createBill($code, (object)$billData);





        $bill_code = $data[0]->BillCode;


        if (isset($data[0]->BillCode)) {
            $bill_code = $data[0]->BillCode;
            $paymentUrl = Toyyibpay::billPaymentLink($bill_code);
            return redirect()->away($paymentUrl);
        } else {
            return redirect()->back()->with('error', 'Failed to create payment bill.');
        }
    }

    public function handlePaymentCallback(Request $request)
    {
        // Retrieve payment details from the request
        $status = $request->input('status_id'); // 1 = success, 0 = failure

        // Check if payment was successful
        if ($status === '1') {
            // Redirect to a success page
            return redirect()->route('infaq')->with('success', 'Terima kasih atas sumbangan anda!');
        } else {
            // Payment failed
            return redirect()->route('infaq')->with('error', 'Sumbangan gagal. Sila cuba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Infaq $infaq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Infaq $infaq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Infaq $infaq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Infaq $infaq)
    {
        //
    }
}
