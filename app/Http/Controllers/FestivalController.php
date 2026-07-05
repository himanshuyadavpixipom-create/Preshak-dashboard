<?php

namespace App\Http\Controllers;

use App\Models\Festival;
use Illuminate\Http\Request;

class FestivalController extends Controller
{
    public function index()
    {
        $festivals = Festival::orderBy('festival_date', 'asc')->paginate(15);
        return view('festivals.index', compact('festivals'));
    }

    public function create()
    {
        return view('festivals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'festival_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        Festival::create($validated);

        return redirect()->route('festivals.index')->with('status', 'Festival created successfully.');
    }

    public function edit(Festival $festival)
    {
        return view('festivals.edit', compact('festival'));
    }

    public function update(Request $request, Festival $festival)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'festival_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        $festival->update($validated);

        return redirect()->route('festivals.index')->with('status', 'Festival updated successfully.');
    }

    public function destroy(Festival $festival)
    {
        $festival->delete();
        return redirect()->route('festivals.index')->with('status', 'Festival deleted successfully.');
    }
}
