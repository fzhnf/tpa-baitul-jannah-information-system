<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

/**
 * 
 *
 * @property string $id
 * @property string $blog_author_id
 * @property string|null $blog_category_id
 * @property bool $is_featured
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string|null $content_overview
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $author
 * @property-read \App\Models\Blog\Category|null $category
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereBlogAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereBlogCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereContentOverview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withAnyTagsOfType(array|string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
 * @mixin \Eloquent
 */
class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia, HasTags, HasUlids;

    /**
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_author_id',
        'blog_category_id',
        'title',
        'slug',
        'content',
        'content_overview',
        'published_at',
        'seo_title',
        'seo_description',
        'is_featured'
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'date',
        'is_featured' => 'boolean'
    ];

    /** @return BelongsTo<User,self> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blog_author_id');
    }

    /** @return BelongsTo<Category,self> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'blog_category_id');
    }
}
