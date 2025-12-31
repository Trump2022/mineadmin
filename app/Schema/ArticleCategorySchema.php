<?php

declare(strict_types=1);

namespace App\Schema;

use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;

#[Schema(title: 'ArticleCategorySchema', description: '文章分类模型')]
class ArticleCategorySchema implements \JsonSerializable
{
    #[Property(property: 'id', title: '主键', type: 'int')]
    public ?int $id;

    #[Property(property: 'parent_id', title: '父级ID', type: 'int')]
    public ?int $parentId;

    #[Property(property: 'name', title: '分类名称', type: 'string')]
    public ?string $name;

    #[Property(property: 'sort', title: '排序', type: 'int')]
    public ?int $sort;

    #[Property(property: 'path', title: '路径', type: 'string')]
    public ?string $path;

    #[Property(property: 'status', title: '状态:0-停用,1-启用', type: 'int')]
    public ?int $status;

    #[Property(property: 'remark', title: '备注', type: 'string')]
    public ?string $remark;

    #[Property(property: 'created_at', title: '创建时间', type: 'string')]
    public mixed $createdAt;

    #[Property(property: 'updated_at', title: '更新时间', type: 'string')]
    public mixed $updatedAt;

    #[Property(property: 'deleted_at', title: '删除时间', type: 'string')]
    public mixed $deletedAt;

    public function jsonSerialize(): mixed
    {
        return [];
    }
}
