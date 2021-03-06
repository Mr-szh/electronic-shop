<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;

class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;
    public $type;

    public function __construct(Reply $reply, $type)
    {
        $this->reply = $reply;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        // return ['database'];
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $topic = $this->reply->topic;
        $link =  $topic->link(['#reply' . $this->reply->id]);

        return [
            // 'type' => 'reply',
            'type' => $this->type,
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }

    public function toMail($notifiable)
    {
        $url = $this->reply->topic->link(['#reply'.$this->reply->id]);
        
        return (new MailMessage)
            ->subject('社区用户：'.$this->reply->user->name.'评论了您的文章')
            ->line('社区用户：'.$this->reply->user->name."评论了您的文章:")
            ->action('查看回复', $url)
            ->line("评论内容如下: ")
            ->line($this->reply->body);
    }
}
