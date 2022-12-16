@extends('layouts.base')

@section('title')
Редактирование вопроса: {{$question->title}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="qwer">Редактирование вопроса: {{$question->title}}</h2>
                    <form action="{{ route('question.destroy', $question->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Удалить</button>
                    </form>
                </div>
                <form action="{{route('question.update', $question->id)}}" method="POST">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="title">Название:</label>
                            <input class="form-control" type="text" name="title" placeholder="Название" required value="{{$question->title}}">
                        </div>
                        <div>
                            <div class="form-group">
                                <label>Теги</label>
                                <select class="select2" multiple="multiple" name="tags[]" data-placeholder="Выберите теги" style="width: 100%;">
                                    @foreach($tags as $tag)
                                    <option value="{{$tag->id}}" {{$question_tags->where('id', $tag->id)->count() == 1 ? 'selected' : ''}}>{{$tag->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <button class="btn code" type="button">CODE</button>
                        </div>
                        <textarea class="editor-placeholder form-control" name="description" cols="30" rows="10" required>{{$question->description}}</textarea>
                    </div>
                    <div class="card-footer">
                        <button class="btn" type="submit">Подтвердить изменения</button>
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
        $('.qwer').text(timezone.name())
        $('.code').click(function() {
            // console.log($('#description').val);
            $('textarea').val($('textarea').val() + "[code]\n\n[/code]");
        })
    })
</script>
@endsection