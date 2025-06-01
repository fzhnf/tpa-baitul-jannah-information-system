<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_session_id',
        'achievement_id',
        'tanggal',
        'keterangan',
        'makruj',
        'mad',
        'tajwid',
        'kelancaran',
        'fashohah',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * @return BelongsTo<Student,StudentAchievement>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo<ClassSession,StudentAchievement>
     */
    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }

    /**
     * @return BelongsTo<Achievement,StudentAchievement>
     */
    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }
}
