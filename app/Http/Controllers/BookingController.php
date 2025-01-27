<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\BookingExtra;
use App\Models\Customer;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer as StripeCustomer;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function create()
    {
        return Inertia::render('Booking/Create');
    }

    public function store(Request $request)
    {


        $paymentIntent = PaymentIntent::create([
            'amount' => $request->input('total_amount') * 100, // Amount in cents
            'currency' => 'usd',
          //  'customer' => $stripeCustomerId,
            'payment_method' => $request->input('payment_method_id'),
            'off_session' => true, // Allow payments even if the user is not present
            'confirm' => true, // Automatically confirm the payment
            'description' => 'Car Rental Booking',
            'metadata' => [
                'booking_id' => $booking->id,
            ],
        ]);

        echo "<pre>";
        print_r($paymentIntent);
        die('test');

        $request->validate([
            'customer.first_name' => 'required',
            'customer.last_name' => 'required',
            'customer.email' => 'required|email',
            'customer.phone' => 'nullable',
            'customer.additional_info' => 'nullable',
            'pickup_date' => 'required|date',
            'return_date' => 'required|date',
            'pickup_location' => 'required',
            'return_location' => 'required',
            'total_days' => 'required|integer',
            'base_price' => 'required|numeric',
            'extra_charges' => 'nullable|numeric',
            'tax_amount' => 'nullable|numeric',
            'total_amount' => 'required|numeric',
            'extras' => 'nullable|array',
            'payment_method_id' => 'required', // Payment method ID from Stripe
        ]);

        // Check if the customer already exists
        $customer = Customer::where('email', $request->input('customer.email'))->first();

        if ($customer) {
            // Update existing customer (except email)
            $customer->update([
                'first_name' => $request->input('customer.first_name'),
                'last_name' => $request->input('customer.last_name'),
                'phone' => $request->input('customer.phone'),
                'additional_info' => $request->input('customer.additional_info'),
            ]);
        } else {
            // Create new customer
            $customer = Customer::create([
                'user_id' => auth()->id(), // Assuming the user is authenticated
                'first_name' => $request->input('customer.first_name'),
                'last_name' => $request->input('customer.last_name'),
                'email' => $request->input('customer.email'),
                'phone' => $request->input('customer.phone'),
                'additional_info' => $request->input('customer.additional_info'),
            ]);
        }

        // Save booking details
        $booking = Booking::create([
            'booking_number' => uniqid('BOOK-'), // Generate a unique booking number
            'customer_id' => $customer->id,
            'vehicle_id' => $request->input('vehicle_id'), // Assuming vehicle_id is passed in the request
            'pickup_date' => $request->input('pickup_date'),
            'return_date' => $request->input('return_date'),
            'pickup_location' => $request->input('pickup_location'),
            'return_location' => $request->input('return_location'),
            'total_days' => $request->input('total_days'),
            'base_price' => $request->input('base_price'),
            'extra_charges' => $request->input('extra_charges', 0),
            'tax_amount' => $request->input('tax_amount', 0),
            'total_amount' => $request->input('total_amount'),
            'payment_status' => 'pending',
            'booking_status' => 'pending',
        ]);

        // Save booking extras
        if ($request->has('extras')) {
            foreach ($request->input('extras') as $extra) {
                BookingExtra::create([
                    'booking_id' => $booking->id,
                    'extra_name' => $extra['name'],
                    'price' => $extra['price'],
                    'description' => $extra['description'] ?? null,
                ]);
            }
        }

        // Initialize Stripe
        Stripe::setApiKey(config('stripe.secret'));

        // Check if the customer already has a Stripe customer ID
        if ($customer->stripe_customer_id) {
            $stripeCustomerId = $customer->stripe_customer_id;
        } else {
            // Create a new Stripe customer
            $stripeCustomer = StripeCustomer::create([
                'name' => $customer->first_name . ' ' . $customer->last_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'payment_method' => $request->input('payment_method_id'),
                'invoice_settings' => [
                    'default_payment_method' => $request->input('payment_method_id'),
                ],
            ]);

            // Save the Stripe customer ID
            $customer->update(['stripe_customer_id' => $stripeCustomer->id]);
            $stripeCustomerId = $stripeCustomer->id;
        }

        // Create a payment intent with Stripe
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->input('total_amount') * 100, // Amount in cents
                'currency' => 'usd',
                'customer' => $stripeCustomerId,
                'payment_method' => $request->input('payment_method_id'),
                'off_session' => true, // Allow payments even if the user is not present
                'confirm' => true, // Automatically confirm the payment
                'description' => 'Car Rental Booking',
                'metadata' => [
                    'booking_id' => $booking->id,
                ],
            ]);

            // Save payment intent
            BookingPayment::create([
                'booking_id' => $booking->id,
                'payment_method' => 'Stripe',
                'transaction_id' => $paymentIntent->id,
                'amount' => $request->input('total_amount'),
                'payment_status' => $paymentIntent->status,
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            // Handle payment failure
            return response()->json([
                'error' => $e->getMessage(),
                'booking' => $booking, // Return booking details for further processing
            ], 400);
        }
    }

    public function success(Request $request)
    {
        $paymentIntentId = $request->input('payment_intent');
        $payment = BookingPayment::where('transaction_id', $paymentIntentId)->first();

        if ($payment) {
            $payment->update([
                'payment_status' => 'successful',
                'payment_date' => now(),
            ]);

            $payment->booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
            ]);
        }

        return Inertia::render('Booking/Success');
    }
}