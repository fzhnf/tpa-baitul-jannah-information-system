<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_class_id',
        'student_id',
        'grade_aspects',
    ];

    protected $casts = [
        'grade_aspects' => 'array',
    ];
    /**
     * @return BelongsTo<SemesterClass,ClassNote>
     */
    public function semesterClass(): BelongsTo
    {
        return $this->belongsTo(SemesterClass::class);
    }
    /**
     * @return BelongsTo<Student,ClassNote>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
