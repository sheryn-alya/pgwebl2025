<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonModel extends Model
{
    protected $table = 'polygon';
    protected $guarded = ['id'];

    public function geojson_polygons()
    {
        $polygon = DB::table($this->table)
            ->selectRaw("
                ST_AsGeoJSON(geom) AS geom,
                name,
                description,
                ST_Area(geom, true) AS area_m2,
                ST_Area(geom, true) / 1000000 AS area_km2, -- Perbaikan di sini
                ST_Area(geom, true) / 10000 AS area_ha,
                created_at,
                updated_at
            ")
            ->get();

        return [
            'type' => 'FeatureCollection',
            'features' => collect($polygon)->map(function ($polygon) {
                return [
                    'type' => 'Feature',
                    'geometry' => json_decode($polygon->geom),
                    'properties' => [
                        'name' => $polygon->name,
                        'description' => $polygon->description,
                        'area_m2' => $polygon->area_m2,
                        'area_km2' => $polygon->area_km2, // Perbaikan disini
                        'area_ha' => $polygon->area_ha, // Konversi ke hektar
                        'created_at' => $polygon->created_at,
                        'updated_at' => $polygon->updated_at
                    ],
                ];
            })->toArray(),
        ];
    }

    protected $fillable = [
        'geom',
        'name',
        'description',
    ];
}
