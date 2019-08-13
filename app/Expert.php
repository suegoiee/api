<?php

namespace App;

use App\Tag;
use App\OnlineCourse;
use App\PhysicalCourse;
use App\UanalyzeModel;
use App\Helpers\ModelHelpers;
use Illuminate\Database\Eloquent\Relations\BeLongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

final class Expert extends UanalyzeModel
{
    const TABLE = 'experts';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'user_id',
        'expert_name',
        'introduction',
        'investment_style',
        'investment_period',
        'experience',
        'book',
        'interview',
    ];

    public static function findByExpertId(int $expert_id): self
    {
        return static::where('id', $expert_id)->firstOrFail();
    }

    public function OnlineCourseRelation()
    {        
        $onlineCourse = $this->morphedByMany(OnlineCourse::class, 'expertable');
        return $onlineCourse;
    }

    public function PhysicalCourseRelation()
    {
        $physicalCourse = $this->morphedByMany(PhysicalCourse::class, 'expertable');
        return $physicalCourse;
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
