<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\TopicReplied;
use App\Notifications\TopicReplyMention;

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $topic = $reply->topic;

        $reply->topic->updateReplyCount();

        // 通知话题作者有新的评论
        // $topic->user->topicNotify(new TopicReplied($reply));
        $topic->user->topicNotify(new TopicReplied($reply, 'reply'));

        // 通知回复中 @ 到的用户
        $mentionIds = explode(',', $reply->mention_ids);
        $mentionUsers = User::whereIn('id', $mentionIds)->get();

        foreach ($mentionUsers as $mentionUser) {
            // 过滤 @ 原作者
            if ($mentionUser->id != $topic->user->id) {
                // $mentionUser->notify(new TopicReplied($reply));
                $mentionUser->topicNotify(new TopicReplied($reply, 'mention'));
            }
        }
    }

    // public function saving(Reply $reply)
    // {
    //     // fixme只能@一个用户
    //     $username = $reply->get_between($reply->content, '@', ' ');
    //     $uid = User::query()->where('name', $username)->pluck('id')->toArray();
    //     $replace = "<a style='color:blue;text-decoration:none' href='/users/" . $uid[0] . "' title='" . "$username'>@" . $username . "</a>";
    //     $reply->content = str_replace('@' . $username, $replace, $reply->content);
    // }

    public function deleted(Reply $reply)
    {
        // $reply->topic->reply_count = $reply->topic->replies->count();

        // $reply->topic->save();
        $reply->topic->updateReplyCount();
    }
}