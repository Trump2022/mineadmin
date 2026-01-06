<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Service\Article;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Article\ArticleCategory;
use App\Repository\Article\ArticleCategoryRepository;
use App\Service\IService;
use Hyperf\DbConnection\Db;

/**
 * @extends IService<ArticleCategory>
 */
class ArticleCategoryService extends IService
{
    public function __construct(
        protected readonly ArticleCategoryRepository $repository
    ) {}

    public function create(array $data): mixed
    {
        return Db::transaction(function () use ($data) {
            // 先创建实体
            $entity = $this->repository->create($data);

            // 再生成 path
            $path = $this->generatePath($entity->id, $data['parent_id'] ?? 0);

            // 更新 path
            $entity->update(['path' => $path]);

            return $entity;
        });
    }

    public function updateById(mixed $id, array $data): mixed
    {
        return Db::transaction(function () use ($id, $data) {
            $entity = $this->repository->findById($id);
            if (empty($entity)) {
                throw new BusinessException(ResultCode::NOT_FOUND);
            }

            // 更新基本字段
            $entity->update($data);

            // 如果 parent_id 变化，需要重新生成 path
            if (isset($data['parent_id'])) {
                $path = $this->generatePath($entity->id, $data['parent_id']);
                $entity->update(['path' => $path]);
            }

            return $entity;
        });
    }

    public function getTree(): array
    {
        // list() = 全部数据（不分页）
        $list = $this->repository->list()->toArray();
        return $this->buildTree($list);
    }

    /**
     * 生成分类路径.
     */
    protected function generatePath(int $id, int $parentId): string
    {
        // 顶级分类
        if ($parentId === 0) {
            return '0,';
        }

        // 找父级
        $parent = $this->repository->findById($parentId);
        if (empty($parent)) {
            throw new BusinessException(ResultCode::NOT_FOUND, '父级分类不存在');
        }

        // 父级 path + 父级 id
        return $parent->path . $parent->id . ',';
    }

    private function buildTree(array $items, int $parentId = 0): array
    {
        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] === $parentId) {
                $item['children'] = $this->buildTree($items, $item['id']);
                $tree[] = $item;
            }
        }
        return $tree;
    }

    public function updateStatus(int $id, int $status): void
    {
        // 更新当前节点
        $this->repository->updateById($id, ['status' => $status]);

        // 更新所有子孙节点
        $this->repository->updateByPathLike($id, $status);
    }

}
