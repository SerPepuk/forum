@extends('layouts.base')

@section('title')
{{$user->name}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h2 class="col-md-12">
                            {{$user->name}}
                        </h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row question">
                        <div class="col-md-1">
                            <p class="stats-question">{{$user->question_qty}}</p>
                            <p class="stats-reply">{{$user->replies_qty}}</p>
                            <p class="stats-favorite">{{$user->likes_qty}}</p>
                            @if($user->answer_qty > 0)
                            <p class="stats-answer">{{$user->answer_qty}}</p>
                            @endif
                        </div>
                        <div class="col-md-11">
                            <div>
                                <p>{{$user->name}} ({{$user->role}})</p>
                            </div>
                            <div class="stats">
                                <div>
                                    <span>Аккаунт создан:</span>
                                    <time itemprop="dateCreated" datetime="{{$user->created_at}}">{{\Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}}</time>
                                </div>
                                <div>
                                    @if($user->last_reply)
                                    <span class="">Последний комментарий:</span>
                                    <span>{{\Carbon\Carbon::parse($user->last_reply)->format('d/m/Y')}}</span>
                                    @else
                                    <span class="">Пока не оставлял комментариев</span>
                                    @endif
                                </div>
                                <div>
                                    @if($user->last_question)
                                    <span class="">Последний вопрос:</span>
                                    <span>{{\Carbon\Carbon::parse($user->last_question)->format('d/m/Y')}}</span>
                                    @else
                                    <span class="">Пока нет вопросов</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container row">
                        <div class="col-md-6 ">
                            <h5>Вопросы пользователя</h5>
                            @foreach($user_questions as $question)
                            <div class="question row">
                                <div class="col-md-2">
                                    <p class="stats-reply">{{$question->reply_qty}}</p>
                                    <p class="stats-favorite">{{$question->likes_qty}}</p>
                                    @if($question->answer_qty > 0)
                                    <p class="stats-answer">{{$question->answer_qty}}</p>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div>
                                        <form action="{{ route('question.edit', $question->slug)}}" class="row" method="get">
                                            <a class="col-md-8" href="{{route('question.show', $question->slug)}}">{{$question->title}}</a>
                                            <!-- <div class="col-md-4">
                                                <button type="submit" class="btn btn-outline-info">Изменить</button>
                                            </div> -->
                                        </form>
                                        <div>
                                            @foreach($question->tags as $tag)
                                            <a href="/question?tags%5B%5D={{$tag->tag_id}}" class="tag-item-name" title="">{{$tag->tag_title}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="stats">
                                        <div>
                                            <span>Создан:</span>
                                            <time itemprop="dateCreated" datetime="{{$question->created_at}}">{{\Carbon\Carbon::parse($question->created_at)->format('d/m/Y')}}</time>
                                        </div>
                                        <div>
                                            <span class="">Последний ответ:</span>
                                            <span title="{{$question->last_reply}}">{{\Carbon\Carbon::parse($question->last_reply)->format('d/m/Y')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <h5>Комментарии пользователя</h5>
                            @foreach($user_replies as $reply)
                            <div class="">
                                <a class="col-md-9" href="{{route('question.show', $reply->question->slug)}}">{{$reply->question->title}}</a>
                                <div class=" col-md-12 {{$reply->status == 'Ответ' ? 'answer' : 'replies-content'}}">
                                    <div class="replies-text">
                                        <p>{{strlen(str_replace(["[code], [/code]"],"", $reply->description)) > 400 ? 
                                            mb_substr(str_replace(["[code], [/code]"],"", $reply->description), 0 , 400).'...' : 
                                            str_replace(["[code]", "[/code]"],["",""], $reply->description)}}</p>
                                    </div>
                                    <div class="stats">
                                        <div>
                                            <p class="stats-favorite">{{$reply->likes_qty}}</p>
                                        </div>
                                        <div>
                                            <span>{{\Carbon\Carbon::parse($reply->created_at)->format('d/m/Y')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
@endsection