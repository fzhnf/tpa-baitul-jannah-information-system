<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * 
 *
 * @property string $id
 * @property string|null $banner_category_id
 * @property int $sort
 * @property bool $is_visible
 * @property string|null $title
 * @property string|null $description
 * @property string|null $image_url
 * @property string|null $click_url
 * @property string|null $click_url_target
 * @property string|null $start_date
 * @property string|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BannerCategory|null $category
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereBannerCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereClickUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereClickUrlTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Banner extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory, HasUlids;

     /**
     * @var array<int, string>
     */
    protected $fillable = [
        'banner_category_id',
        'sort',
        'title',
        'description',
        'image_url',
        'click_url',
        'click_url_target',
        'is_visible',
        'start_date',
        'end_date',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BannerCategory::class, 'banner_category_id');
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banners')
            ->singleFile();
    }

}
