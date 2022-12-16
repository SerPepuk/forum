@extends('layouts.base')

@section('title', 'Создание тега')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form action="{{route('tag.store')}}" method="POST">
                    <div class="card-header">
                        <h2>Создание тега: </h2>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div>
                                    <label for="title">Название:</label>
                                    <input type="text" class="form-control" name="title" placeholder="Название" required>
                                </div>
                                <label for="description">Описание:</label>
                                <textarea class="editor-placeholder form-control" name="description" cols="30" rows="10" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn" type="submit">Создать тег</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection