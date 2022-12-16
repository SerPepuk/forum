@extends('layouts.base')

@section('title', 'Пользователи')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h2 class="col-md-12">
                            Пользователи{{isset($_GET['search']) && $_GET['search'] != '' ? ': '.$_GET['search'] : ''}}
                        </h2>
                    </div>
                    <form action="{{ route('user.index')}}" class="row" method="get">
                        <div class="col-md-12 row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-11">
                                        <input autocomplete="off" type="text" name="search" class="form-control" placeholder="Поиск..." value="{{isset($_GET['search']) ? $_GET['search'] : null}}">
                                    </div>
                                    @if(isset($_GET['search']))
                                    <div class="col-md-1">
                                        <a href="{{ route('user.index')}}" class="btn btn-outline-danger">Х</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-info">Найти</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if($users->count() == 0)
                    <div class="col-md-12">
                        <p>
                            Нет пользователей
                        </p>
                    </div>
                    @else
                    @foreach($users as $user)
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
                                <a href="/user/{{$user->id}}" title="">{{$user->name}} ({{$user->role}})</a>
                            </div>
                            <div class="stats">
                                <div>
                                    <span>Аккаунт создан:</span>
                                    <time itemprop="dateCreated" datetime="{{$user->created_at}}">{{\Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}}</time>
                                </div>
                                <div>
                                    @if($user->last_reply)
                                    <span class="">Последний ответ:</span>
                                    <span>{{\Carbon\Carbon::parse($user->last_reply)->format('d/m/Y')}}</span>
                                    @else
                                    <span class="">Пока нет ответов</span>
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
                    @endforeach
                    @endif
                </div>
                <div class="card-footer">
                    {{$users->withQueryString()->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection