<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SemesterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_id',
        'nama_semester_class',
    ];

    /**
     * Get the semester that owns the SemesterClass
     *
     * @return BelongsTo<Semester,SemesterClass>
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the teachers for the SemesterClass
     *
     * @return BelongsToMany<User,SemesterClass>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'semester_class_teacher');
    }

    /**
     * Get the students for the SemesterClass
     *
     * @return BelongsToMany<Student,SemesterClass>
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'semester_class_student');
    }

    /**
     * Get the class sessions for the SemesterClass
     *
     * @return HasMany<ClassSession,SemesterClass>
     */
    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class);
    }
}
