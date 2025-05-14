<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonsModel extends Model
{
    protected $table = 'polygons';
    protected $guarded = ['id'];
    protected $fillable = [
        'geom',
        'name',
        'description',
        'image',
    ];


    public function geojson_polygons()
    {
        $polygons = DB::table($this->table)
            ->selectRaw("
                ST_AsGeoJSON(geom) AS geom,
                name,
                id,
                description, image,
                ST_Area(geom, true) AS area_m2,
                ST_Area(geom, true) / 10000 AS area_ha,
                created_at,
                updated_at
            ")
            ->get();

        return [
            'type' => 'FeatureCollection',
            'features' => collect($polygons)->map(function ($polygon) {
                return [
                    'type' => 'Feature',
                    'geometry' => json_decode($polygon->geom),
                    'properties' => [
                        'name' => $polygon->name,
                        'id' => $polygon->id,
                        'description' => $polygon->description,
                        'image' => $polygon->image,
                        'area_m2' => $polygon->area_m2,
                        'area_ha' => $polygon->area_ha, // Konversi ke hektar
                        'created_at' => $polygon->created_at,
                        'updated_at' => $polygon->updated_at
                    ],
                ];
            })->toArray(),
        ];
    }

    public function geojson_polygon($id)
    {
        $polygons = DB::table($this->table)
            ->selectRaw("
                ST_AsGeoJSON(geom) AS geom,
                name,
                id,
                description, image,
                ST_Area(geom, true) AS area_m2,
                ST_Area(geom, true) / 10000 AS area_ha,
                created_at,
                updated_at
            ")
            ->where('id', $id)
            ->get();

        return [
            'type' => 'FeatureCollection',
            'features' => collect($polygons)->map(function ($polygon) {
                return [
                    'type' => 'Feature',
                    'geometry' => json_decode($polygon->geom),
                    'properties' => [
                        'name' => $polygon->name,
                        'id' => $polygon->id,
                        'description' => $polygon->description,
                        'image' => $polygon->image,
                        'area_m2' => $polygon->area_m2,
                        'area_ha' => $polygon->area_ha, // Konversi ke hektar
                        'created_at' => $polygon->created_at,
                        'updated_at' => $polygon->updated_at
                    ],
                ];
            })->toArray(),
        ];
    }


}
