<?php

declare(strict_types=1);

namespace App\Model\Article;

use Carbon\Carbon;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property string $path
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property null|Carbon $deleted_at
 */
class ArticleCategory extends Model
{
    use SoftDeletes;

    protected ?string $table = 'article_categories';

    /**
     * @var array|string[]
     */
    protected array $fillable = [
        'id',
        'name',
        'parent_id',
        'path',
        'status',
        'remark',
        'sort',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array|string[]
     */
    protected array $casts = [
        'id' => 'integer',
        'parent_id' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
