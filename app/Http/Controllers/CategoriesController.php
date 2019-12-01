<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\TopicsCategory;
use App\Models\User;

class CategoriesController extends Controller
{
    public function show(TopicsCategory $category, Request $request, Topic $topic, User $user)
    {
        $topics = $topic->withOrder($request->order)->where('category_id', $category->id)->paginate(20);

        $active_users = $user->getActiveUsers();

        return view('topics.index', compact('topics', 'category', 'active_users'));
    }
}