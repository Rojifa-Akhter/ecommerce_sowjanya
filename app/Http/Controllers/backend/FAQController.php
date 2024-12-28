<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FAQController extends Controller
{
    public function faqAdd(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $validated->errors()], 422);
        }

        $faq = FAQ::updateOrCreate([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'FAQ added successfully', 'faq' => $faq], 200);
    }


    public function faqDelete($id)
    {
        $faq = FAQ::findOrFail($id);
        if (!$faq) {return response()->json(['status' => 'error', 'message' => 'FAQ Not Found'], 200);}
        $faq->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'FAQ deleted successfully'], 200);
    }
}
