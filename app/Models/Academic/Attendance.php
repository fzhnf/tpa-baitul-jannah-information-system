<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_session_id',
        'student_id',
        'status',
        'remarks',
    ];

    /**
     * @return BelongsTo<ClassSession,Attendance>
     */
    public function classSession(): BelongsTo
    {
        return $this->belongsTo(ClassSession::class);
    }
    /**
     * @return BelongsTo<Student,Attendance>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
