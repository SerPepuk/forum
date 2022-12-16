@extends('layouts.base')

@section('title')
Редактирование тега: {{$tag->title}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Редактирование тега: {{$tag->title}}</h2>
                    <form action="{{ route('tag.destroy', $tag->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Удалить</button>
                    </form>
                </div>
                <form action="{{route('tag.update', $tag->id)}}" method="POST">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <div>
                                    <label for="title">Название:</label>
                                    <input type="text" class="form-control" name="title" placeholder="Название" required value="{{$tag->title}}">
                                </div>
                                <label for="description">Описание:</label>
                                <textarea class="editor-placeholder form-control" name="description" cols="30" rows="10" required>{{$tag->description}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn" type="submit">Изменить тег</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection