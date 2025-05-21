<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PointsController extends Controller
{
    protected $points;

    public function __construct()
    {
        $this->points = new PointsModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Map'
        ];
        return view('map', $data);
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
        // Validasi input
        $request->validate(
            [
                'name' => 'required|unique:points,name',
                'description' => 'required',
                'geom_point' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:51200', // 50 KB = 51200 bytes
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_point.required' => 'Geometry is required',
            ]
        );

        // Buat direktori penyimpanan jika belum ada
        $imageDirectory = public_path('storage/images');
        if (!File::exists($imageDirectory)) {
            File::makeDirectory($imageDirectory, 0777, true);
        }

        // Proses file gambar jika tersedia
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move($imageDirectory, $name_image);
        } else {
            $name_image = null;
        }

        // Siapkan data untuk disimpan
        $data = [
            'geom' => $request->geom_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan data ke database
        if (!$this->points->create($data)) {
            return redirect()->route('map')->with('error', 'Point failed to add');
        }

        // Redirect ke halaman peta dengan pesan sukses
        return redirect()->route('map')->with('success', 'Point has been added');
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
        $data = [
            'title' => 'Edit Point',
            'id' => $id
        ];

        return view('edit_point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Ambil data lama
        $point = $this->points->find($id);
        if (!$point) {
            return redirect()->route('map')->with('error', 'Point not found');
        }

        // Validasi input
        $request->validate(
            [
                'name' => 'required|unique:points,name,' . $id, // abaikan nama saat ini dari validasi unique
                'description' => 'required',
                'geom_point' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:51200',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_point.required' => 'Geometry is required',
            ]
        );

        // Proses gambar jika ada
        if ($request->hasFile('image')) {
            $imageDirectory = public_path('storage/images');
            if (!File::exists($imageDirectory)) {
                File::makeDirectory($imageDirectory, 0777, true);
            }


            // Ambil gambar lama
            $old_image = $point->image;

            // Hapus gambar lama
            if ($old_image != null) {
                if (file_exists('../storage/images/' . $old_image)) {
                    unlink('../storage/images/' . $old_image);
                }
            }else{
                $name_image = $old_image;
            }

            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move($imageDirectory, $name_image);
        } else {
            $name_image = $point->image; // gunakan gambar lama jika tidak diganti
        }

        // Siapkan data update
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom_point,
            'image' => $name_image,
        ];

        // Update ke database
        if (!$this->points->where('id', $id)->update($data)) {
            return redirect()->route('map')->with('error', 'Failed to update point');
        }

        return redirect()->route('map')->with('success', 'Point has been updated');
    }

    public function destroy(string $id)
    {
        $imagefile = $this->points->find($id)->image;

        if (!$this->points->destroy($id)) {
            return redirect()->route('map')->with('error', 'Failed to delete point');
        }

        // Delete image file if exists
        if ($imagefile != null) {
            if (file_exists('storage/images/' . $imagefile)) {
                unlink('storage/images/' . $imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'point has been deleted');
    }
}
