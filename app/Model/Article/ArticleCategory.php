<?php

declare(strict_types=1);

namespace App\Model\Article;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

class ArticleCategory extends Model
{
    protected ?string $table = 'article_categories';

    protected array $fillable = [
        'parent_id',
        'name',
        'sort',
        'path',
    ];
}
