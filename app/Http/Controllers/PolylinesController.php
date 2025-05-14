<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolylinesModel;

class PolylinesController extends Controller
{
    public function __construct()
    {
        $this->polylines = new PolylinesModel();
    }


    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //Validation request
        $request->validate(
            [
                'name' => 'required|unique:polylines,name',
                'description' => 'required',
                'geom_polyline' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:4048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',

                'description.required' => 'Description is required',
                'geom_polyline.required' => 'Location is required',
            ]
        );

        // Create image directory if not exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        // Get data from bootstrap form
        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        //dd($data); //ini cuma ngecek dlm bentuk teks data geojson

        // Create data to database
        if (!$this->polylines->create($data)) {
            return redirect()->route('map')->with('error', 'Failed to add polyline');
        }
        // Redirect to map
        return redirect()->route('map')->with('success', 'Polyline has been added');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Polyline',
            'id' => $id,
        ];

        return view('edit_polyline', $data);
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        $imagefile = $this->polylines->find($id)->image;

        if (!$this->polylines->destroy($id)) {
            return redirect()->route('map')->with('error', 'Failed to delete polyline');
        }

        // Delete image file if exists
        if ($imagefile != null) {
            if (file_exists('storage/images/' . $imagefile)) {
                unlink('storage/images/' . $imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Polyline has been deleted');
    }
}
