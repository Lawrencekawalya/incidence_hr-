<?php

namespace App\Http\Controllers;

use App\Models\HrRecord;
use Illuminate\Http\Request; // ✅ Use regular Request instead of custom FormRequest
// (You can re-enable your Store/Update requests later if you prefer)

class HrRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all HR records sorted by date (latest first)
        $records = HrRecord::orderBy('date', 'desc')->get();

        // Calculate cumulative total work hours
        $cumulativeHours = $records->sum('total_work_hours');

        return view('hr.index', compact('records', 'cumulativeHours'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'number_of_employees' => 'required|integer|min:0',
            'total_work_hours' => 'required|numeric|min:0',
        ]);

        HrRecord::create($validated);

        // ✅ redirect to hr.index instead of index
        return redirect()->route('hr.index')->with('success', 'Record added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HrRecord $hr)
    {
        return view('hr.edit', compact('hr'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HrRecord $hr)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'number_of_employees' => 'required|integer|min:0',
            'total_work_hours' => 'required|numeric|min:0',
        ]);

        $hr->update($validated);

        // ✅ redirect to hr.index instead of index
        return redirect()->route('hr.index')->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HrRecord $hr)
    {
        $hr->delete();

        // ✅ redirect to hr.index instead of index
        return redirect()->route('hr.index')->with('success', 'Record deleted successfully!');
    }
}
