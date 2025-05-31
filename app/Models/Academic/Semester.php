<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year',
        'semester_enum',
    ];

    public function semesterClasses(): HasMany
    {
        return $this->hasMany(SemesterClass::class);
    }
}
