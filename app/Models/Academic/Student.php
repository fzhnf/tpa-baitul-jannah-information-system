<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'address',
        'guardian',
        'entry_date',
        'profile_picture_url',
        'guardian_number',
    ];

    /**
     * @return HasMany<SemesterClass,Student>
     */
    public function semesterClasses(): BelongsToMany
    {
        return $this->belongsToMany(SemesterClass::class);
    }

    /**
     * @return HasMany<Attendance,Student>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * @return HasMany<StudentAchievement,Student>
     */
    public function studentAchievements(): HasMany
    {
        return $this->hasMany(StudentAchievement::class);
    }
}
