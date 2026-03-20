<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['name'];

    /**
     * Get or create relevant academic years based on the current calendar year.
     * Usually (Year-1)-Year and Year-(Year+1).
     */
    public static function getRelevantYears()
    {
        $startYear = 2025;
        $numberOfYears = 8;
        
        $years = [];
        for ($i = 0; $i < $numberOfYears; $i++) {
            $years[] = ($startYear + $i) . ' - ' . ($startYear + $i + 1);
        }

        foreach ($years as $yearName) {
            static::firstOrCreate(['name' => $yearName]);
        }

        return static::orderBy('name', 'asc')->get();
    }
}
