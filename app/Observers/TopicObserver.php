<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;
use App\Models\TimeOuts;

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // XSS 过滤
        $topic->body = clean($topic->body, 'user_topic_body');

        // 生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if (!$topic->slug) {
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);;
        }
    }

    public function saved(Topic $topic)
    {
        // 添加话题时间间隔
        $key = 'topic_create_' . \Auth::id();
        if (!app(TimeOuts::class)->get($key)) {
            TimeOuts::put($key, Topic::TTL);
        }

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if (!$topic->slug || $topic->isDirty()) {
            // 推送任务到队列
            dispatch(new TranslateSlug($topic));

            if (trim($topic->slug) === 'edit') {
                $topic->slug = 'edit-slug';
            }
        }
    }

    public function deleted(Topic $topic)
    {
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}