<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'achievement_name',
        'module',
        'category',
    ];

    /**
     * @return HasMany<StudentAchievement,Achievement>
     */
    public function studentAchievements(): HasMany
    {
        return $this->hasMany(StudentAchievement::class);
    }
}
