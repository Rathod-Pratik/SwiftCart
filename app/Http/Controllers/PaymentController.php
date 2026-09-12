<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Throwable;

class PaymentController extends Controller
{
    private Api $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function index(Request $request)
    {
        try {
            Gate::authorize('viewAny', Payment::class);
            $payments = Payment::where('user_id', $request->user()->id)
                ->latest()
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Payments fetched successfully',
                'data' => $payments,
            ]);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while fetching payments', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching payments',
            ], 500);
        }
    }

    public function createOrder(Request $request)
    {
        try {
            Gate::authorize('create', Payment::class);
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
            ], [
                'order_id.required' => 'The order_id field is required.',
                'order_id.exists' => 'The specified order does not exist.',
            ]);

            $order = Order::findOrFail($validated['order_id']);

            if ($order->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this order',
                ], 403);
            }

            if ($order->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'This order has already been paid',
                ], 409);
            }

            $amountInPaise = (int) round($order->total_amount * 100);

            $razorpayOrder = $this->razorpay->order->create([
                'receipt' => $order->order_number,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'payment_capture' => 1,
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => $request->user()->id,
                'payment_method' => 'razorpay',
                'payment_status' => 'created',
                'amount' => $order->total_amount,
                'currency' => 'INR',
                'razorpay_order_id' => $razorpayOrder['id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Razorpay order created successfully',
                'data' => [
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'key' => config('services.razorpay.key'),
                    'payment_id' => $payment->id,
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while creating Razorpay order', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while creating payment order',
            ], 500);
        }
    }

    public function verify(Request $request)
    {
        try {
            $validated = $request->validate([
                'razorpay_order_id' => 'required|string',
                'razorpay_payment_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ], [
                'razorpay_order_id.required' => 'The razorpay_order_id field is required.',
                'razorpay_payment_id.required' => 'The razorpay_payment_id field is required.',
                'razorpay_signature.required' => 'The razorpay_signature field is required.',
            ]);

            $payment = Payment::where('razorpay_order_id', $validated['razorpay_order_id'])
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            Gate::authorize('verify', $payment);

            try {
                $this->razorpay->utility->verifyPaymentSignature([
                    'razorpay_order_id' => $validated['razorpay_order_id'],
                    'razorpay_payment_id' => $validated['razorpay_payment_id'],
                    'razorpay_signature' => $validated['razorpay_signature'],
                ]);
            } catch (SignatureVerificationError $e) {
                $payment->update(['payment_status' => 'failed']);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed',
                ], 400);
            }

            DB::transaction(function () use ($payment, $validated) {
                $payment->update([
                    'razorpay_payment_id' => $validated['razorpay_payment_id'],
                    'razorpay_signature' => $validated['razorpay_signature'],
                    'payment_status' => 'captured',
                ]);

                $payment->order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'confirmed',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'data' => $payment->load('order'),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while verifying payment', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while verifying payment',
            ], 500);
        }
    }

    public function show(Payment $payment)
    {
        try {
            Gate::authorize('view', $payment);

            return response()->json([
                'success' => true,
                'message' => 'Payment fetched successfully',
                'data' => $payment->load('order'),
            ]);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while fetching payment', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching payment',
            ], 500);
        }
    }

    public function GetAllUserPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ], [
                'user_id.required' => 'The user_id field is required.',
                'user_id.exists' => 'The specified user does not exist.',
            ]);
            Gate::authorize('viewAny', Payment::class);

            $payments = Payment::where('user_id', $validated['user_id'])
                ->latest()
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Payments fetched successfully',
                'data' => $payments,
            ]);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while fetching payments', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching payments',
            ], 500);
        }
    }

    public function GetAllPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:100',
            ], [
                'page.integer' => 'The page must be an integer.',
                'page.min' => 'The page must be at least 1.',
                'per_page.integer' => 'The per_page must be an integer.',
                'per_page.min' => 'The per_page must be at least 1.',
                'per_page.max' => 'The per_page may not be greater than 100.',
            ]);
            Gate::authorize('viewAny', Payment::class);

            $payments = Payment::latest()->paginate($validated['per_page'] ?? 10);

            return response()->json([
                'success' => true,
                'message' => 'Payments fetched successfully',
                'data' => $payments,
            ]);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Error occurred while fetching payments', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching payments',
            ], 500);
        }
    }
}
