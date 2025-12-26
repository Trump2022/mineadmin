<?php

declare(strict_types=1);

namespace App\Repository\Article;

use App\Model\Article\ArticleCategory;
use App\Repository\IRepository;

final class ArticleCategoryRepository extends IRepository
{
    public function __construct(ArticleCategory $model)
    {
        $this->model = $model;
    }

    /**
     * 获取树形结构
     */
    public function getTree(): array
    {
        $list = $this->model
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->toArray();

        return $this->buildTree($list);
    }

    /**
     * 构建树
     */
    private function buildTree(array $items, int $parentId = 0): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $item['children'] = $this->buildTree($items, $item['id']);
                $tree[] = $item;
            }
        }

        return $tree;
    }

    /**
     * 获取某节点的直接子节点
     */
    public function getChildren(int $id): array
    {
        return $this->model
            ->where('parent_id', $id)
            ->orderBy('sort')
            ->get()
            ->toArray();
    }

    /**
     * 判断是否有子节点
     */
    public function hasChildren(int $id): bool
    {
        return $this->model
            ->where('parent_id', $id)
            ->exists();
    }

    /**
     * 获取单条记录（用于 Service）
     */
    public function get(int $id): ?ArticleCategory
    {
        return $this->model->find($id);
    }
}
