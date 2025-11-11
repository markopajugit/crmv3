<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kyc;
use App\Models\Company;
use App\Models\Person;
use App\Models\User;

class KycController extends Controller
{
    /**
     * Store a newly created KYC record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kycable_type' => 'required|string',
            'kycable_id' => 'required|integer',
            'responsible_user_id' => 'required|exists:users,id',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'comments' => 'nullable|string',
            'risk' => 'nullable|string',
            'documents' => 'nullable|string',
        ]);

        Kyc::create([
            'kycable_type' => $request->kycable_type,
            'kycable_id' => $request->kycable_id,
            'responsible_user_id' => $request->responsible_user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'comments' => $request->comments,
            'risk' => $request->risk,
            'documents' => $request->documents,
        ]);

        return response()->json(['success' => 'KYC record added successfully']);
    }

    /**
     * Update the specified KYC record.
     */
    public function update(Request $request, $id)
    {
        $kyc = Kyc::findOrFail($id);

        $request->validate([
            'responsible_user_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'comments' => 'nullable|string',
            'risk' => 'nullable|string',
            'documents' => 'nullable|string',
        ]);

        $kyc->update($request->only([
            'responsible_user_id',
            'start_date',
            'end_date',
            'comments',
            'risk',
            'documents'
        ]));

        return response()->json(['success' => 'KYC record updated successfully']);
    }

    /**
     * Remove the specified KYC record.
     */
    public function destroy($id)
    {
        $kyc = Kyc::findOrFail($id);
        $kyc->delete();

        return response()->json(['success' => 'KYC record deleted successfully']);
    }
} 