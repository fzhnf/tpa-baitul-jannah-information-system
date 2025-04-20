<?php

namespace App\Models;

use App\Events\ContactUsCreated;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property string $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone
 * @property string $company
 * @property string $employees
 * @property string $title
 * @property string $message
 * @property string $status
 * @property string|null $reply_title
 * @property string|null $reply_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereReplyMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereReplyTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactUs withoutTrashed()
 * @mixin \Eloquent
 */
class ContactUs extends Model
{
    use HasFactory, HasUlids;
    use SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'company',
        'employees',
        'title',
        'message',
        'status',
        'reply_title',
        'reply_message',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [

    ];

    protected $dispatchesEvents = [
        'created' => ContactUsCreated::class,
    ];

    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

}
