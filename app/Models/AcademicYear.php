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
        $currentYear = (int)date('Y');
        
        $years = [
            ($currentYear - 1) . ' - ' . $currentYear,
            $currentYear . ' - ' . ($currentYear + 1)
        ];

        foreach ($years as $yearName) {
            static::firstOrCreate(['name' => $yearName]);
        }

        return static::orderBy('name', 'desc')->get();
    }
}
