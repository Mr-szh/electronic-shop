<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\TopicReplied;

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $topic = $reply->topic;

        $reply->topic->updateReplyCount();

        // 通知话题作者有新的评论
        $topic->user->topicNotify(new TopicReplied($reply));
    }

    public function saving(Reply $reply)
    {
        // fixme只能@一个用户
        $username = $reply->get_between($reply->content, '@', ' ');
        $uid = User::query()->where('name', $username)->pluck('id')->toArray();
        $replace = "<a style='color:blue;text-decoration:none' href='/users/" . $uid[0] . "' title='" . "$username'>@" . $username . "</a>";
        $reply->content = str_replace('@' . $username, $replace, $reply->content);
    }

    public function deleted(Reply $reply)
    {
        // $reply->topic->reply_count = $reply->topic->replies->count();

        // $reply->topic->save();
        $reply->topic->updateReplyCount();
    }
}