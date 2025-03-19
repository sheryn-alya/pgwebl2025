<?php

namespace App\Http\Controllers;

use App\Models\PolylineModel;
use Illuminate\Http\Request;

class PolylineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->polylines = new PolylineModel();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Map',
            'polylines' => $this->polylines->all(),
        ];
        return view('map', $data);
    }

    public function create()
    {
        return view('polylines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required| unique:polylines,name',
                'description' => 'required',
                'geom_polyline' => 'required',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polyline.required' => 'Geometry polyline is required',
            ]
        );

        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description
        ];

        if (!$this->polylines->create($data)) {
            return redirect()->route('map')->with('error', 'Failed to add polyline');
        }

        return redirect()->route('map')->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
