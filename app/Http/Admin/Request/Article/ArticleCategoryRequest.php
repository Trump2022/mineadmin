<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Article;

use Hyperf\Validation\Request\FormRequest;

class ArticleCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'parent_id' => 'required|integer|min:0',
            'sort' => 'integer|min:0',
            'status' => 'required|in:0,1',
            'remark' => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '分类名称',
            'parent_id' => '父级ID',
            'sort' => '排序',
            'status' => '状态', 'remark' => '备注',
        ];
    }
}
