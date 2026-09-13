<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function index()
    {
        try {
            $validatedData = request()->validate([
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
            ], [
                'page.integer' => 'The page must be an integer.',
                'page.min' => 'The page must be at least 1.',
                'per_page.integer' => 'The per_page must be an integer.',
                'per_page.min' => 'The per_page must be at least 1.',
                'per_page.max' => 'The per_page may not be greater than 100.',
            ]);

            $coupons = Coupon::paginate($validatedData['per_page'] ?? 15, ['*'], 'page', $validatedData['page'] ?? 1);

            return response()->json([
                'success' => true,
                'message' => 'Coupons fetched successfully',
                'data' => $coupons,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while fetching coupons',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|unique:coupons,code',
                'discount_amount' => 'required|numeric|min:0',
                'discount_type' => 'required|in:fixed,percentage',
                'expires_at' => 'nullable|date',
                'is_active' => 'boolean',
                'number_of_coupons' => 'nullable|integer|min:1',
            ], [
                'code.required' => 'The coupon code is required.',
                'code.unique' => 'The coupon code must be unique.',
                'discount_amount.required' => 'The discount amount is required.',
                'discount_amount.numeric' => 'The discount amount must be a number.',
                'discount_type.required' => 'The discount type is required.',
                'discount_type.in' => 'The discount type must be either "fixed" or "percentage".',
                'expires_at.date' => 'The expiration date must be a valid date.',
                'is_active.boolean' => 'The is_active field must be true or false.',
                'number_of_coupons.integer' => 'The number of coupons must be an integer.',
                'number_of_coupons.min' => 'The number of coupons must be at least 1.',
            ]);

            $coupon = Coupon::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Coupon created successfully',
                'data' => $coupon,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while creating coupon',
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|exists:coupons,id',
                'is_active' => 'required|boolean',
                'number_of_coupons' => 'nullable|integer|min:0',
                'code' => 'nullable|string|unique:coupons,code,'.$request->id,
                'discount_amount' => 'nullable|numeric|min:0',
            ], [
                'id.required' => 'The coupon ID is required.',
                'id.exists' => 'The specified coupon does not exist.',
                'is_active.required' => 'The is_active field is required.',
                'is_active.boolean' => 'The is_active field must be true or false.',
                'number_of_coupons.integer' => 'The number of coupons must be an integer.',
                'number_of_coupons.min' => 'The number of coupons must be at least 0.',
                'code.string' => 'The code must be a string.',
                'code.unique' => 'The code must be unique.',
                'discount_amount.numeric' => 'The discount amount must be a number.',
                'discount_amount.min' => 'The discount amount must be at least 0.',
            ]);

            $coupon = Coupon::findOrFail($validatedData['id']);

            if ($coupon->empty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon not found',
                ], 404);
            }

            $coupon->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Coupon status updated successfully',
                'data' => $coupon,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while updating coupon status',
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|exists:coupons,id',
            ], [
                'id.required' => 'The coupon ID is required.',
                'id.exists' => 'The specified coupon does not exist.',
            ]);

            $coupon = Coupon::findOrFail($validatedData['id']);

            if ($coupon->empty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon not found',
                ], 404);
            }

            $coupon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Coupon deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while deleting coupon',
            ], 500);
        }
    }

    public function VerifyCoupon(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|exists:coupons,code',
            ], [
                'code.required' => 'The coupon code is required.',
                'code.exists' => 'The specified coupon code does not exist.',
            ]);

            $coupon = Coupon::where('code', $validatedData['code'])->first();

            if (! $coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon not found',
                ], 404);
            }

            if (! $coupon || ! $coupon->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon is not valid or inactive',
                ], 404);
            }
            if ($coupon->expires_at && now()->greaterThan($coupon->expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon has expired',
                ], 404);
            }
            if ($coupon->number_of_coupons !== null && $coupon->number_of_coupons <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon has reached its usage limit',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'discount_amount' => $coupon->discount_amount,
                'discount_type' => $coupon->discount_type,
                'message' => 'Coupon is valid',
                'data' => $coupon,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while verifying coupon',
            ], 500);
        }
    }
}
