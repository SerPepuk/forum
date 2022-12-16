<?php

namespace App\Http\Controllers;

use App\Filters\QuestionFilter;
use App\Models\Like;
use App\Models\Question;
use App\Models\Reply;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuestionFilter $request)
    {
        $questions = Question::filter($request)
            ->paginate(5);
        $replies = Reply::all();
        // dd($request);
        foreach ($questions as $question) {
            $question->user = User::where('id', $question->user_id)->first();
            // dd($question->user);
            $question->reply_qty = $replies->where('question_id', $question->id)->count();
            $question->answer_qty = $replies->where('question_id', $question->id)->where('status', 'Ответ')->count();
            $question->likes_qty = Like::whereIn('reply_id', array_column($replies->where('question_id', $question->id)->toArray(), 'id'))->count();
            $question->last_reply = $replies->where('question_id', $question->id)->max('created_at');
            // dd($question->last_reply);
            $question->tags = DB::table('question_has_tags')
                ->where('question_id', $question->id)
                ->join('tags', 'tags.id', '=', 'question_has_tags.tag_id')
                ->select(
                    'tags.id as tag_id',
                    'tags.title as tag_title',
                    'tags.slug as tag_slug',
                )
                ->get();
        }
        // dd($questions);
        return view('question.index', [
            'questions' => $questions,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('question.create', [
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->tags);
        $quested_tags = $request->tags;
        unset($request->tags);
        $question = new Question();
        $question->title = $request->title;
        $question->description = $request->description;
        $question->user_id = auth()->user()->id;
        $question->slug = Str::slug($request->title, '-');
        $question->save();
        $question->slug = Str::slug($question->id . '-' . $request->title, '-');
        $question->save();
        if ($quested_tags) {
            foreach ($quested_tags as $tag) {
                DB::table('question_has_tags')
                    ->insert([
                        'tag_id' => $tag,
                        'question_id' => $question->id,
                    ]);
            }
        }
        return redirect()->route('question.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question->user = User::where('id', $question->user_id)->first();
        $question->tags = DB::table('question_has_tags')
            ->where('question_id', $question->id)
            ->join('tags', 'tags.id', '=', 'question_has_tags.tag_id')
            ->select(
                'tags.id as tag_id',
                'tags.title as tag_title',
                'tags.slug as tag_slug',
            )
            ->get();
        // dd($question->user_id);
        $replies = Reply::where('question_id', '=', $question->id)
            ->orderByDesc('created_at')
            ->join('users', 'users.id', '=', 'replies.user_id')
            ->select(
                'replies.id as id',
                'replies.user_id as user_id',
                'users.name as name',
                'replies.description as description',
                'replies.created_at as created_at',
                'replies.status as status',
            )
            ->paginate(5);
        foreach ($replies as $reply) {
            $reply->likes_qty = Like::where('reply_id', $reply->id)->count();
        }
        return view('question.show', [
            'question' => $question,
            'replies' => $replies,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        Gate::authorize('edit-questions', $question->user_id);
        $question_tags = DB::table('question_has_tags')
            ->where('question_id', $question->id)
            ->join('tags', 'tags.id', '=', 'question_has_tags.tag_id')
            ->select(
                'tags.id as id',
                'tags.title as title',
            )
            ->get();
        // dd($question_tags->where('id', 2));
        return view('question.edit', [
            'tags' => Tag::all(),
            'question' => $question,
            'question_tags' => $question_tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        Gate::authorize('edit-questions', $question->user_id);
        // dd($question->user_id);
        $quested_tags = $request->tags;
        unset($request->tags);
        DB::table('question_has_tags')->where('question_id', $question->id)->delete();
        $question->title = $request->title;
        $question->description = $request->description;
        $question->slug = Str::slug($question->id . '-' . $request->title, '-');
        $question->save();
        if ($quested_tags) {
            foreach ($quested_tags as $tag) {
                DB::table('question_has_tags')
                    ->insert([
                        'tag_id' => $tag,
                        'question_id' => $question->id,
                    ]);
            }
        }
        return redirect()->route('question.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        Gate::authorize('edit-questions', $question->user_id);
        $question->delete();
        return redirect()->route('question.index');
    }
}
