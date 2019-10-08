<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    // $parentId 代表要获取父类目 ID，null 代表获取所有根类目
    // $allCategories 代表所有的类目，需要从数据库中查询
    public function getCategoryTree($parentId = null, $allCategories = null)
    {
        if (is_null($allCategories)) {
            $allCategories = Category::all();
        }

        return $allCategories
            ->where('parent_id', $parentId)
            // 遍历这些类目，并用返回值构建一个新的集合
            ->map(function (Category $category) use ($allCategories) {
                $data = ['id' => $category->id, 'name' => $category->name];
                // 当前类目不是父类目则直接返回
                if (!$category->is_directory) {
                    return $data;
                }
                
                // 递归调用本方法，将返回值放入 children 字段中
                $data['children'] = $this->getCategoryTree($category->id, $allCategories);

                return $data;
            });
    }
}