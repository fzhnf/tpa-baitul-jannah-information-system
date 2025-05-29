<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $achievement_name
 * @property string $category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAchievement> $studentAchievements
 * @property-read int|null $student_achievements_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement whereAchievementName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $module
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement whereModule($value)
 */
	class Achievement extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $class_session_id
 * @property int $student_id
 * @property string $status
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClassSession $classSession
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereClassSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\AttendanceFactory factory($count = null, $state = [])
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models{
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
	class Banner extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
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
	class BannerCategory extends \Eloquent {}
}

namespace App\Models\Blog{
/**
 * 
 *
 * @property string $id
 * @property string|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $is_active
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Blog\Post> $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Category extends \Eloquent {}
}

namespace App\Models\Blog{
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
	class Post extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $semester_class_id
 * @property int $student_id
 * @property array<array-key, mixed> $grade_aspects
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SemesterClass $semesterClass
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote whereGradeAspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote whereSemesterClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassNote whereUpdatedAt($value)
 */
	class ClassNote extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereSemesterClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $description
 * @method static \Database\Factories\ClassSessionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassSession whereDescription($value)
 */
	class ClassSession extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $school_year
 * @property string $semester_enum
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SemesterClass> $semesterClasses
 * @property-read int|null $semester_classes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereSchoolYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereSemesterEnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Semester whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\SemesterFactory factory($count = null, $state = [])
 */
	class Semester extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $semester_id
 * @property string $nama_semester_class
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassSession> $classSessions
 * @property-read int|null $class_sessions_count
 * @property-read \App\Models\Semester $semester
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\SemesterClassFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SemesterClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SemesterClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SemesterClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SemesterClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SemesterClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SemesterClass whereNamaSemesterClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SemesterClass whereSemesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SemesterClass whereUpdatedAt($value)
 */
	class SemesterClass extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $student_name
 * @property string|null $address
 * @property string|null $guardian
 * @property string|null $entry_date
 * @property string|null $profile_picture_url
 * @property string|null $guardian_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
 * @property-read int|null $attendances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SemesterClass> $semesterClasses
 * @property-read int|null $semester_classes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentAchievement> $studentAchievements
 * @property-read int|null $student_achievements_count
 * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereEntryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGuardian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGuardianNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereProfilePictureUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereStudentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUpdatedAt($value)
 */
	class Student extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $student_id
 * @property int $class_session_id
 * @property int $achievement_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string|null $keterangan
 * @property string|null $catatan
 * @property string|null $makruj
 * @property string|null $mad
 * @property string|null $tajwid
 * @property string|null $kelancaran
 * @property string|null $fashohah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Achievement $achievement
 * @property-read \App\Models\ClassSession $classSession
 * @property-read \App\Models\Student $student
 * @method static \Database\Factories\StudentAchievementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereAchievementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereClassSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereFashohah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereKelancaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereMad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereMakruj($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereTajwid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAchievement whereUpdatedAt($value)
 */
	class StudentAchievement extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string $username
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $name
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SemesterClass> $semesterClasses
 * @property-read int|null $semester_classes_count
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Filament\Models\Contracts\HasAvatar, \Spatie\MediaLibrary\HasMedia, \Filament\Models\Contracts\HasName, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

