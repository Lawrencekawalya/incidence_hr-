<?php

namespace App\Http\Controllers;

use App\Models\HrRecord;
use App\Http\Requests\StoreHrRecordRequest;
use App\Http\Requests\UpdateHrRecordRequest;

class HrRecordController_copy extends Controller
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHrRecordRequest $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'number_of_employees' => 'required|integer|min:0',
            'total_work_hours' => 'required|numeric|min:0',
        ]);

        HrRecord::create($validated);

        return redirect()->route('index')->with('success', 'Record added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(HrRecord $hrRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HrRecord $hrRecord)
    {
        return view('hr.edit', compact('hr'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHrRecordRequest $request, HrRecord $hrRecord)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'number_of_employees' => 'required|integer|min:0',
            'total_work_hours' => 'required|numeric|min:0',
        ]);

        $hrRecord->update($validated);

        return redirect()->route('index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HrRecord $hrRecord)
    {
        $hrRecord->delete();

        return redirect()->route('index')
            ->with('success', 'Record deleted successfully!');
    }
}
