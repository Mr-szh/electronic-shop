<?php
namespace App\Services;

use App\Models\Category;

class CategoryService
{
    // $parentId 代表要获取子类目的父类目 ID，null 代表获取所有根类目
    // $allCategories 代表数据库中所有的类目，如果是 null 代表需要从数据库中查询
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
                
                $data['children'] = $this->getCategoryTree($category->id, $allCategories);

                return $data;
            });
    }
}