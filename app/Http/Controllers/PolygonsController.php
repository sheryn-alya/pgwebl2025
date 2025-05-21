<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonsModel;
use Illuminate\Support\Facades\File;


class PolygonsController extends Controller
{
    public function __construct()
    {
        $this->polygons = new PolygonsModel();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi request
        $request->validate(
            [
                'name' => 'required|unique:polygons,name',
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exist',
                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Geometry is required',
            ]
        );


        //CREATE  IMAGE DIRECTOR IF NOT EXIST -PGWEBL 7
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //GET IMAGE FILE - PGWEBL 7
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygons." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
            //$image->storeAs('public/images', $name_image);

        } else {
            $name_image = null;
        }



        // Simpan data
        $data = [
            'geom' => $request->geom_polygon, // Perbaikan dari geom_point ke geom_polygon
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan ke database
        if (!$this->polygons->create($data)) { // Perbaikan dari $this->points ke $this->polygons
            return redirect()->route('map')->with('error', 'Polygon failed to add');
        }

        // Redirect ke halaman peta
        return redirect()->route('map')->with('success', 'Polygon has been added');
    }

    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Polygon',
            'id' => $id
        ];

        return view('edit_polygon', $data);
    }

    public function update(Request $request, string $id)
    {
        // Ambil data lama
        $polygon = $this->polygons->find($id);
        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        // Validasi input
        $request->validate(
            [
                'name' => 'required|unique:polygons,name,' . $id,
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:51200',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Geometry is required',
            ]
        );

        $imageDirectory = public_path('storage/images');
        $oldImage = $polygon->image;
        $nameImage = $oldImage; // Default: pakai gambar lama

        // Jika upload gambar baru
        if ($request->hasFile('image')) {
            // Buat direktori jika belum ada
            if (!File::exists($imageDirectory)) {
                File::makeDirectory($imageDirectory, 0777, true);
            }

            // Hapus gambar lama
            if ($oldImage && file_exists($imageDirectory . '/' . $oldImage)) {
                unlink($imageDirectory . '/' . $oldImage);
            }

            // Simpan gambar baru
            $image = $request->file('image');
            $nameImage = time() . "_polygons." . strtolower($image->getClientOriginalExtension());
            $image->move($imageDirectory, $nameImage);
        }

        // Siapkan data untuk update
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom_polygon,
            'image' => $nameImage,
        ];

        // Proses update
        if (!$this->polygons->where('id', $id)->update($data)) {
            return redirect()->route('map')->with('error', 'Failed to update Polygons');
        }

        return redirect()->route('map')->with('success', 'Polygons has been updated');
    }

    public function destroy(string $id)
    {
        $imagefile = $this->polygons->find($id)->image;

        if (!$this->polygons->destroy($id)) {
            return redirect()->route('map')->with('error', 'Failed to delete polygon');
        }

        // Delete image file if exists
        if ($imagefile != null) {
            if (file_exists('storage/images/' . $imagefile)) {
                unlink('storage/images/' . $imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'polygon has been deleted');
    }
}
