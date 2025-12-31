<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Article;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Article\ArticleCategoryRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Http\CurrentUser;
use App\Schema\ArticleCategorySchema;
use App\Service\Article\ArticleCategoryService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\JsonContent;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;
use Hyperf\Swagger\Annotation\RequestBody;
use Mine\Access\Attribute\Permission;
use Mine\Swagger\Attributes\PageResponse;
use Mine\Swagger\Attributes\ResultResponse;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
class ArticleCategoryController extends AbstractController
{
    public function __construct(
        protected readonly CurrentUser $currentUser,
        protected readonly ArticleCategoryService $service
    ) {}

    #[Get(
        path: '/admin/article/category/list',
        operationId: 'articleCategoryList',
        summary: '文章分类列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['文章分类'],
    )]
    #[PageResponse(instance: ArticleCategorySchema::class)]
    #[Permission(code: 'article:category:index')]
    public function pageList(): Result
    {
        return $this->success([
            'list' => $this->service->getList($this->getRequestData()),
        ]);
    }

    #[Post(
        path: '/admin/article/category',
        operationId: 'articleCategoryCreate',
        summary: '创建文章分类',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['文章分类'],
    )]
    #[RequestBody(
        content: new JsonContent(ref: ArticleCategoryRequest::class)
    )]
    #[Permission(code: 'article:category:save')]
    #[ResultResponse(instance: new Result())]
    public function create(ArticleCategoryRequest $request): Result
    {
        $this->service->create(array_merge($request->validated(), [
            'created_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Put(
        path: '/admin/article/category/{id}',
        operationId: 'articleCategorySave',
        summary: '保存文章分类',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['文章分类'],
    )]
    #[RequestBody(
        content: new JsonContent(ref: ArticleCategoryRequest::class)
    )]
    #[Permission(code: 'article:category:update')]
    #[ResultResponse(instance: new Result())]
    public function save(int $id, ArticleCategoryRequest $request): Result
    {
        $this->service->updateById($id, array_merge($request->validated(), [
            'updated_by' => $this->currentUser->id(),
        ]));
        return $this->success();
    }

    #[Delete(
        path: '/admin/article/category',
        operationId: 'articleCategoryDelete',
        summary: '删除文章分类',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['文章分类'],
    )]
    #[ResultResponse(instance: new Result())]
    #[Permission(code: 'article:category:delete')]
    public function delete(): Result
    {
        $this->service->deleteById($this->getRequestData());
        return $this->success();
    }

    #[Get(path: '/admin/article/category/tree')]
    #[Permission(code: 'article:category:index')]
    public function tree(): Result
    {
        return $this->success($this->service->getTree());
    }
}
