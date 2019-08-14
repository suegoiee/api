<?php

namespace App;

use App\Tag;
use App\Expert;
use App\UanalyzeModel;

final class PhysicalCourse extends UanalyzeModel
{

    const TABLE = 'physical_course';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'date',
        'end_date',
        'quota',
        'location',
        'introduction',
        'seo',
        'electric_ticket',
        'status',
        'image',
        'suitable',
        'host',
    ];

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function experts()
    {
        return $this->morphToMany(Expert::class, 'expertable');
    }
}
