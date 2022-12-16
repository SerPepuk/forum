@extends('layouts.base')

@section('title', 'Создание вопроса')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form action="{{route('question.store')}}" method="POST">
                    <div class="card-header">
                        <h2>Создание вопроса</h2>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div>
                            <label for="title">Название:</label>
                            <input class="form-control" type="text" name="title" placeholder="Название" required>
                        </div>
                        <div>
                            <div class="form-group">
                                <label>Теги</label>
                                <select class="select2" multiple="multiple" name="tags[]" data-placeholder="Выберите теги" style="width: 100%;">
                                    @foreach($tags as $tag)
                                    <option value="{{$tag->id}}">{{$tag->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <button class="btn code" type="button">CODE</button>
                        </div>
                        <textarea class="editor-placeholder form-control" name="description" cols="30" rows="10" required></textarea>
                    </div>
                    <div class="card-footer">
                        <button class="btn" type="submit">Задать вопрос</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script>
    $('.select2').select2()
</script>
<script>
    $(document).ready(function() {
        $('.code').click(function() {
            // console.log($('#description').val);
            $('textarea').val($('textarea').val() + "[code]\n\n[/code]");
        })
    })
</script>
@endsection