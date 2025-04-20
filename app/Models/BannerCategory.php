<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property string $id
 * @property string|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Banner> $banners
 * @property-read int|null $banners_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BannerCategory> $children
 * @property-read int|null $children_count
 * @property-read BannerCategory|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BannerCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BannerCategory extends Model
{
    use HasFactory, HasUlids;

    /**
     * @var string
     */
    protected $table = 'banner_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'parent_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(BannerCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BannerCategory::class, 'parent_id');
    }

    public function banners()
    {
        return $this->hasMany(Banner::class, 'banner_category_id');
    }
}
