<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\TermsCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class termConditionController extends Controller
{
    public function createTerm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }
        $terms = TermsCondition::updateOrCreate(
            ['type' => $request->type],
            ['description' => $request->description]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Terms and Condition updated successfully',
            'terms' => $terms,
        ], 200);
    }
    public function termList()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated.'], 401);
        }

        // Fetch all About records
        $terms = TermsCondition::all();

        return response()->json([
            'status' => 'success',
            'terms' => $terms,
        ], 200);

    }
}
