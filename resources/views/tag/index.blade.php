@extends('layouts.base')

@section('title', 'Теги')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h2 class="col-md-10">
                            Теги{{isset($_GET['search']) && $_GET['search'] != '' ? ': '.$_GET['search'] : ''}}
                        </h2>
                        @can('edit-tags')
                        <h2 class="col-md-2">
                            <span><a href="{{ route('tag.create')}}" class="btn btn-primary">Создать тег</a></span>
                        </h2>
                        @endcan
                    </div>
                    <form action="{{route('tag.index')}}" class="row" method="get">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-11">
                                    <input autocomplete="off" type="text" name="search" class="form-control" placeholder="Поиск..." value="{{isset($_GET['search']) ? $_GET['search'] : null}}">
                                </div>
                                @if(isset($_GET['search']) || isset($_GET['sort']))
                                <div class="col-md-1">
                                    <a href="{{ route('tag.index')}}" class="btn btn-outline-danger">Х</a>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="sort">Сортировка</label>
                                    <select name="sort" class="form-control form-control" id="sort">
                                        <option {{isset($_GET['sort']) && $_GET['sort'] == 'new' ? 'selected' : null}} value="old">Сначала старые</option>
                                        <option {{isset($_GET['sort']) && $_GET['sort'] == 'new' ? 'selected' : null}} value="new">Сначала новые</option>
                                        <option {{isset($_GET['sort']) && $_GET['sort'] == 'name' ? 'selected' : null}} value="name">По имени</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-info">Найти</button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if($tags->count() == 0)
                    <div class="col-md-12">
                        <p>
                            Нет тегов
                        </p>
                    </div>
                    @else
                    <div class="tags-grid">
                        @foreach($tags as $tag)
                        <div class="tag-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="/question?tags%5B%5D={{$tag->id}}" class="tag-item-name" title="">{{$tag->title}}</a>
                                </div>
                                <div class="col-md-6">
                                    @can('edit-tags')
                                    <form action="{{ route('tag.edit', $tag->id)}}" method="get">
                                        <button type="submit" class="btn btn-outline-info">Изменить</button>
                                    </form>
                                    <form action="{{ route('tag.destroy', $tag->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Удалить</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                            <div class="tag-item-description">
                                {{$tag->description}}
                            </div>
                            <div class="stats">
                                <div>
                                    <p>Вопросов по тегу:</p>
                                    <a href="/question?tags%5B%5D={{$tag->id}}" title="">За всё время: {{$tag->qty}}</a>
                                    <p><a href="/question?time=today&tags%5B%5D={{$tag->id}}" title=""> Сегодня: {{$tag->qty_today}}</a></p>
                                </div>
                                <div>
                                    Создан : {{\Carbon\Carbon::parse($tag->created_at)->format('d/m/Y')}}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    {{$tags->withQueryString()->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection