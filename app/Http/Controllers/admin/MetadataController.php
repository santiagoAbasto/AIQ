<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Metadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metadata = Metadata::all();

        return view('admin.metadatos.index', compact('metadata'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Metadata $metadata)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $metadata = Metadata::findOrFail($id);
        return view('admin.metadatos.edit', compact('metadata'));
    }

    public function update(Request $request, $id)
    {
        $metadata = Metadata::findOrFail($id);
        $metadata->update([
            'keyword' => $request->input('keyword'),
            'description' => $request->input('description'),
            'section' => $request->input('section')
        ]);

        return redirect()->route('admin.metadatos.index')->with('success', 'Metadata updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Metadata $metadata)
    {
        //
    }
}
