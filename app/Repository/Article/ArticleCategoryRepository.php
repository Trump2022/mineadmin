<?php

declare(strict_types=1);

namespace App\Repository\Article;

use App\Model\Article\ArticleCategory;
use App\Repository\IRepository;

final class ArticleCategoryRepository extends IRepository
{
    public function __construct(
        protected readonly ArticleCategory $model
    ) {}
}
