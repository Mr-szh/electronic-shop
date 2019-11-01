<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Reply $reply) {
        $content = clean($request->input('content'), 'user_topic_body');
        
        if (empty($content)) {
            return redirect()->back()->with('danger', '回复内容错误！');
        }

        // $reply->content = $request->content;
        $reply->content = $reply->parse($request->content);
        $reply->mention_ids = $reply->mentionUserIds();
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;

        $reply->save();

        // return redirect()->route('topics.show', [$request->topic_id])->with('success', '评论创建成功！');
        return redirect()->to($reply->topic->link())->with('success', '评论创建成功！');
    }

    public function destroy(Reply $reply) {
        $this->authorize('destroy', $reply);
        $reply->delete();

        return redirect()->to($reply->topic->link())->with('success', '评论删除成功！');
    }

    public function atwho(Request $request)
    {
        $name = $request->input('q', '');
        //fixme 只能 at话题作者或者是自己的粉丝
        $users = User::query()->where('name', 'like', $name . '%')->pluck('name');
        // laravel集合可以当做数组用，laravel默认返回的是json响应
        return $users;
    }
}