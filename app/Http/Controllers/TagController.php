<?php

namespace App\Http\Controllers;

use App\Filters\TagFilter;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class TagController extends Controller
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
    public function index(TagFilter $request)
    {
        $tags = Tag::filter($request)
            ->paginate(8);;
        foreach ($tags as $tag) {
            $tag->qty = DB::table('question_has_tags')->where('tag_id', $tag->id)->count();
            $tag->qty_today = DB::table('question_has_tags')->where('tag_id', $tag->id)
                ->join('questions', 'questions.id', '=', 'question_has_tags.question_id')
                ->select(
                    'questions.id as question_id',
                    'questions.created_at as created_at',
                )->whereDate('created_at', Carbon::today())->count();
        }
        // dd($tags);
        return view('tag.index', [
            'tags' => $tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('edit-tags');
        $tag = new Tag();
        $tag->title = $request->title;
        $tag->description = $request->description;
        $tag->slug = Str::slug($request->title, '-');
        $tag->save();
        return redirect()->route('tag.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        Gate::authorize('edit-tags');
        // dd($tag);
        return view('tag.edit', ['tag' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        Gate::authorize('edit-tags');
        // dd($tag);
        $tag->title = $request->title;
        $tag->description = $request->description;
        $tag->slug = Str::slug($request->title, '-');
        $tag->save();
        return redirect()->route('tag.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        Gate::authorize('edit-tags');
        $tag->delete();
        return redirect()->route('tag.index');
    }
}
