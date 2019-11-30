<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getCategoryTree($parentId = null, $allCategories = null)
    {
        if (is_null($allCategories)) {
            $allCategories = Category::all();
        }

        return $allCategories
            ->where('parent_id', $parentId)
            // 遍历后用返回值构建一个新的集合
            ->map(function (Category $category) use ($allCategories) {
                $data = ['id' => $category->id, 'name' => $category->name];
                // 不是父类目则直接返回
                if (!$category->is_directory) {
                    return $data;
                }
                
                // 递归调用本方法
                $data['children'] = $this->getCategoryTree($category->id, $allCategories);

                return $data;
            });
    }
}