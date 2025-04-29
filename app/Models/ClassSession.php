<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $semester_class_id
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $topic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
 * @property-read int|null $attendances_count
 * @property-read \App\Models\SemesterClass $semesterClass
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAchievement> $studentAchievements
 * @property-read int|null $student_achievements_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereSemesterClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
