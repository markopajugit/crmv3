<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\PersonTaxResidency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxResidencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new tax residency for a person
     */
    public function store(Request $request, $personId)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|string|max:255',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'is_primary' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $person = Person::findOrFail($personId);

        // If this is set as primary, unset other primary tax residencies
        if ($request->is_primary) {
            $person->taxResidencies()->update(['is_primary' => false]);
        }

        $taxResidency = PersonTaxResidency::create([
            'person_id' => $personId,
            'country' => $request->country,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'is_primary' => $request->is_primary ?? false,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tax residency added successfully',
            'data' => $taxResidency
        ]);
    }

    /**
     * Update an existing tax residency
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|string|max:255',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'is_primary' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $taxResidency = PersonTaxResidency::findOrFail($id);

        // If this is set as primary, unset other primary tax residencies for this person
        if ($request->is_primary) {
            PersonTaxResidency::where('person_id', $taxResidency->person_id)
                              ->where('id', '!=', $id)
                              ->update(['is_primary' => false]);
        }

        $taxResidency->update([
            'country' => $request->country,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'is_primary' => $request->is_primary ?? false,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tax residency updated successfully',
            'data' => $taxResidency
        ]);
    }

    /**
     * Delete a tax residency
     */
    public function destroy($id)
    {
        $taxResidency = PersonTaxResidency::findOrFail($id);
        $taxResidency->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tax residency deleted successfully'
        ]);
    }

    /**
     * Get tax residencies for a person
     */
    public function getByPerson($personId)
    {
        $person = Person::findOrFail($personId);
        $taxResidencies = $person->taxResidencies()->orderBy('is_primary', 'desc')->orderBy('valid_from', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $taxResidencies
        ]);
    }
} 