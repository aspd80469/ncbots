<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;

class NewsController extends Controller
{

    //[管理][公告]
    public function index(Request $request)
    {

        //[主頁]

        //搜尋條件
        $news = News::query();
        $s_title = htmlspecialchars($request->input('s_title'), ENT_QUOTES);
        $s_content = htmlspecialchars($request->input('s_content'), ENT_QUOTES);

        $request->flash();

        if ($s_title != '') {
            $news->where('title', 'LIKE', '%' . $s_title . '%');
        }

        if ($s_content != '') {
            $news->where('content', 'LIKE', '%' . $s_content . '%');
        }

        $news = $news->orderBy('created_at', 'DESC')->paginate(20);

        return view('mge/mge_news', [
            'news' => $news,
        ]);
    }

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'nullable',
            'created_at' => 'nullable',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $news = new News;
        $news->title = htmlspecialchars($request->input('title'), ENT_QUOTES);
        $news->content = htmlspecialchars($request->input('content'), ENT_QUOTES);
        $news->created_at = htmlspecialchars($request->input('created_at'), ENT_QUOTES);
        $news->display = htmlspecialchars($request->input('display'), ENT_QUOTES);

        if ($news->save()) {
            Session::flash('alert-success', '建立公告成功');
        } else {
            Session::flash('alert-danger', '建立公告失敗');
        }

        return redirect('/mge/news');
    }

    public function edit($id)
    {

        //[編輯]
        $news = News::find($id);

        return view('mge/mge_news_single', [
            'news' => $news,
        ]);
    }

    public function update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'nullable',
            'created_at' => 'nullable',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $news = News::findOrFail($id);
        $news->title = htmlspecialchars($request->input('title'), ENT_QUOTES);
        $news->content = htmlspecialchars($request->input('content'), ENT_QUOTES);
        $news->created_at = htmlspecialchars($request->input('created_at'), ENT_QUOTES);
        $news->display = htmlspecialchars($request->input('display'), ENT_QUOTES);

        if ($news->save()) {
            Session::flash('alert-success', '公告更新成功');
        } else {
            Session::flash('alert-danger', '公告更新失敗');
        }
        return redirect('/mge/news/');
    }

    public function destroy($id)
    {

        //[刪除]

        $news = News::findOrFail($id);
        News::destroy($id);
        Session::flash('alert-danger', '已成功刪除公告：' . $news->title );

        return redirect('/mge/news');
    }
}
