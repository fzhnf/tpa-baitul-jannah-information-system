<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $school_year
 * @property string $semester_enum
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SemesterClass> $semesterClasses
 * @property-read int|null $semester_classes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereSchoolYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereSemesterEnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
