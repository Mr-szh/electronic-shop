<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\TopicsCategory;
use Illuminate\Support\Facades\Auth;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Schema;

class TopicsController extends Controller
{
    public function index(Request $request, Topic $topic, User $user)
    {
        $topics = $topic->withOrder($request->order)->paginate(20);

        // return view('topics.index', compact('topics'));
        $active_users = $user->getActiveUsers();
        
        return view('topics.index', compact('topics', 'active_users'));
    }

    public function create(Topic $topic)
    {
        $categories = TopicsCategory::all();

        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::user()->id;

        $id = DB::table('topics')->max('id') + 1;
        $url = $request->url().'/'.$id; 
        $topic->url = $url;

        $topic->save();
        
        // return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功！');
        return redirect()->to($topic->link())->with('success', '帖子创建成功！');
    }

    public function show(Request $request, Topic $topic)
    {
        // URL 矫正
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        $data = [
            'success' => false,
            'msg' => '上传失败!',
            'file_path' => ''
        ];

        // 判断是否有上传图片，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功后
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg'] = '上传成功!';
                $data['success'] = true;
            }
        }

        return $data;
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = TopicsCategory::all();

        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        // return redirect()->route('topics.show', $topic->id)->with('success', '更新成功！');
        return redirect()->to($topic->link())->with('success', '帖子更新成功！');
    }
    
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('success', '成功删除！');
    }
}
