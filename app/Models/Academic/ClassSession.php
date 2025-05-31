<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_class_id',
        'date',
        'description',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * @return BelongsTo<SemesterClass,ClassSession>
     */
    public function semesterClass(): BelongsTo
    {
        return $this->belongsTo(SemesterClass::class);
    }

    /**
     * @return HasMany<Attendance,ClassSession>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * @return HasMany<StudentAchievement,ClassSession>
     */
    public function studentAchievements(): HasMany
    {
        return $this->hasMany(StudentAchievement::class);
    }
}
