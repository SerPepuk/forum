<?php

namespace App\Http\Controllers;

use App\Filters\UserFilter;
use App\Models\Like;
use App\Models\Question;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserFilter $request)
    {
        $users = User::filter($request)->paginate(5);
        $replies = Reply::all();
        $questions = Question::all();
        foreach ($users as $user) {
            $user->replies_qty = $replies->where('user_id', $user->id)->count();
            // $user->likes_qty = Like::where('user_id', $user->id)->count();
            $user->answer_qty = $replies->where('user_id', $user->id)->where('status', 'Ответ')->count();
            $user->question_qty = $questions->where('user_id', $user->id)->count();
            $user->last_question = $questions->where('user_id', $user->id)->max('created_at');
            $user->last_reply = $replies->where('user_id', $user->id)->max('created_at');
            // dd($user->last_question);
            $user->role = DB::table('model_has_roles')->where('model_id', $user->id)->first()->role_id == 1 ? 'пользователь' : 'администратор';
        }
        // dd($users);
        return view('user.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $replies = Reply::all();
        $questions = Question::all();
        $user->question_qty = $questions->where('user_id', $user->id)->count();
        // $user->likes_qty = Like::where('user_id', $user->id)->count();
        $user->replies_qty = $replies->where('user_id', $user->id)->count();
        $user->answer_qty = $replies->where('user_id', $user->id)->where('status', 'Ответ')->count();
        $user->last_question = $questions->where('user_id', $user->id)->max('created_at');
        $user->last_reply = $replies->where('user_id', $user->id)->max('created_at');
        $user->role = DB::table('model_has_roles')->where('model_id', $user->id)->first()->role_id == 1 ? 'пользователь' : 'администратор';
        // dd($user->last_question);

        $user_questions = $questions->where('user_id', $user->id);
        foreach ($user_questions as $question) {
            $question->tags = DB::table('question_has_tags')
                ->where('question_id', $question->id)
                ->join('tags', 'tags.id', '=', 'question_has_tags.tag_id')
                ->select(
                    'tags.id as tag_id',
                    'tags.title as tag_title',
                    'tags.slug as tag_slug',
                )->get();
            $question->reply_qty = $replies->where('question_id', $question->id)->count();
            $question->answer_qty = $replies->where('question_id', $question->id)->where('status', 'Ответ')->count();
            $question->likes_qty = Like::whereIn('reply_id', array_column($replies->where('question_id', $question->id)->toArray(), 'id'))->count();
        }
        // dd(Reply::where('user_id', $user->id));
        $user_replies = Reply::where('user_id', $user->id)->get();
        // ->join('users', 'users.id', '=', 'replies.user_id')->get();
        foreach ($user_replies as $reply) {
            $reply->likes_qty = Like::where('reply_id', $reply->id)->count();
            $reply->question = $questions->where('id', $reply->question_id)->first();
            // dd($reply);
        }
        // dd($user_replies);
        foreach ($user_replies as $question) {
            $question->tags = DB::table('question_has_tags')
                ->where('question_id', $question->id)
                ->join('tags', 'tags.id', '=', 'question_has_tags.tag_id')
                ->select(
                    'tags.id as tag_id',
                    'tags.title as tag_title',
                    'tags.slug as tag_slug',
                )->get();
        }
        // dd($users);
        return view('user.show', [
            'user' => $user,
            'user_questions' => $user_questions,
            'user_replies' => $user_replies,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
