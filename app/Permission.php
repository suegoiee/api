<?php

namespace App;

use App\UanalyzeModel;

final class Permission extends UanalyzeModel
{
    const TABLE = 'permission';
    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'user_id',
        'permission',
        'vip',
        'category_id',
    ];

    public function user()
    {
        return $this->BelongsTo(User::class, 'user_id');
    }
}
