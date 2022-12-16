<?php

namespace App\Filters;

use App\Filters\QueryFilter;
use App\Models\Question;
use App\Models\Reply;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QuestionFilter extends QueryFilter
{
    public function sort($sort)
    {
        switch ($sort) {
            case 'new':
                return $this->builder->orderByDesc('created_at');
            case 'old':
                return $this->builder->orderBy('created_at');
        }
    }

    public function time($time)
    {
        switch ($time) {
            case 'today':
                return $this->builder->where('created_at', '>', Carbon::today())->orderByDesc('created_at');
            case 'week':
                return $this->builder->where('created_at', '>', Carbon::today()->subWeeks(1))->orderByDesc('created_at');
            case 'month':
                return $this->builder->where('created_at', '>', Carbon::today()->subMonths(1))->orderByDesc('created_at');
            case 'all':
                return $this;
        }
    }

    public function answer($answer)
    {
        // dd($answer);
        switch ($answer) {
            case 'unanswered':
                $questions = Question::all();
                $replies = Reply::all();
                foreach ($questions as $question) {
                    // dd($replies->where('question_id', $question->id)->where('status', 'Ответ')->count());
                    if ($replies->where('question_id', $question->id)->where('status', 'Ответ')->count() > 0) {
                        $question->status = 'Решено';
                    } else {
                        $question->status = '';
                    }
                }
                $questions = $questions->where('status', '');
                // dd(array_column($questions->toArray(), 'id'));
                return $this->builder->whereIn('id', array_column($questions->toArray(), 'id'));
            case 'answered':
                $questions = Question::all();
                $replies = Reply::all();
                foreach ($questions as $question) {
                    // dd($replies->where('question_id', $question->id)->where('status', 'Ответ')->count());
                    if ($replies->where('question_id', $question->id)->where('status', 'Ответ')->count() > 0) {
                        $question->status = 'Решено';
                    } else {
                        $question->status = '';
                    }
                }
                $questions = $questions->where('status', 'Решено');
                // dd(array_column($questions->toArray(), 'id'));
                return $this->builder->whereIn('id', array_column($questions->toArray(), 'id'));
            case 'all':
                return $this;
        }
    }

    public function tags($tags)
    {
        // dd($tags);
        $questions = Question::all();
        $question_tags = DB::table('question_has_tags')->get();
        foreach ($questions as $question) {
            $qty = $question_tags->where('question_id', $question->id)->whereIn('tag_id', $tags)->count();
            // dd($question_tags->where('question_id', $question->id)->whereIn('tag_id', $tags));
            if ($qty > 0) {
                $question->status = 'Имеет тег';
            } else {
                $question->status = '';
            }
            // dd($question->status);
        }
        $questions = $questions->where('status', 'Имеет тег');
        return $this->builder->whereIn('id', array_column($questions->toArray(), 'id'));
    }

    public function search($search = '')
    {
        return $this->builder->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }
}
