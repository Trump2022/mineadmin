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

namespace App\Repository\Article;

use App\Model\Article\ArticleCategory;
use App\Repository\IRepository;
use Hyperf\Collection\Arr;
use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Builder;

final class ArticleCategoryRepository extends IRepository
{
    public function __construct(
        protected readonly ArticleCategory $model
    ) {}

    public function page(array $params = [], ?int $page = null, ?int $pageSize = null): array
    {
        // \Hyperf\Context\ApplicationContext::getContainer()
        //     ->get(\Hyperf\Logger\LoggerFactory::class)
        //     ->get('debug')
        //     ->info('调试信息', ['data' => 333]);

        $query = $this->getQuery()
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'asc');

        $query = $this->perQuery($query, $params);

        $result = $query->paginate(
            perPage: $pageSize,
            pageName: self::PER_PAGE_PARAM_NAME,
            page: $page,
        );
        return $this->handlePage($result);
    }

    public function list(array $params = []): Collection
    {
        $query = $this->getQuery()
            ->orderBy('sort', 'asc')
            ->orderBy('id', 'asc');   // ⭐ 排序提前

        return $this->handleItems(
            $this->perQuery($query, $params)->get()
        );
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query
            // ⭐ ID 精确匹配
            ->when(isset($params['id']), static function (Builder $query) use ($params) {
                $query->whereIn('id', Arr::wrap($params['id']));
            })

            // ⭐ 分类名称模糊搜索
            ->when(isset($params['name']) && $params['name'] !== '', static function (Builder $query) use ($params) {
                $query->where('name', 'like', '%' . $params['name'] . '%');
            })

            // ⭐ 层级过滤（核心）
            ->when(isset($params['parent_id']), static function (Builder $query) use ($params) {
                $query->where('parent_id', (int) $params['parent_id']);
            })

            // ⭐ 状态筛选（你刚要求的）
            ->when(isset($params['status']) && $params['status'] !== '', static function (Builder $query) use ($params) {
                $query->where('status', (int) $params['status']);
            })

            // ⭐ 创建时间范围
            ->when(isset($params['created_at']), static function (Builder $query) use ($params) {
                $query->whereBetween('created_at', $params['created_at']);
            })

            // ⭐ 更新时间范围
            ->when(isset($params['updated_at']), static function (Builder $query) use ($params) {
                $query->whereBetween('updated_at', $params['updated_at']);
            });
    }

    public function updateByPathLike(int $id, int $status): int
    {
        return $this->model::where('path', 'like', "%,{$id},%")->update(['status' => $status]);
    }
}
