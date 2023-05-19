@extends('layouts.base')

@section('title', 'Вопросы')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h2 class="col-md-10">
                                Вопросы{{ isset($_GET['search']) && $_GET['search'] != '' ? ': ' . $_GET['search'] : '' }}
                            </h2>
                            <h2 class="col-md-2">
                                <span><a href="{{ route('question.create') }}" class="btn btn-primary">Задать
                                        вопрос</a></span>
                            </h2>
                        </div>
                        <form action="{{ route('question.index') }}" class="row" method="get">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-11">
                                        <input autocomplete="off" type="text" name="search" class="form-control"
                                            placeholder="Поиск..."
                                            value="{{ isset($_GET['search']) ? $_GET['search'] : null }}">
                                    </div>
                                    @if (isset($_GET['search']) ||
                                            isset($_GET['tags']) ||
                                            isset($_GET['time']) ||
                                            isset($_GET['sort']) ||
                                            isset($_GET['answer']))
                                        <div class="col-md-1">
                                            <a href="{{ route('question.index') }}" class="btn btn-outline-danger">X</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="tags[]">Теги</label>
                                        <select class="select2" multiple="multiple" name="tags[]"
                                            data-placeholder="Выберите теги" style="width: 100%;">
                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    {{ isset($_GET['tags']) && in_array($tag->id, $_GET['tags']) ? 'selected' : '' }}>
                                                    {{ $tag->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="time">За какой период</label>
                                                <select name="time" class="form-control" id="time">
                                                    <option
                                                        {{ isset($_GET['time']) && $_GET['time'] == 'all' ? 'selected' : null }}
                                                        value="all">Всё время</option>
                                                    <option
                                                        {{ isset($_GET['time']) && $_GET['time'] == 'today' ? 'selected' : null }}
                                                        value="today">День</option>
                                                    <option
                                                        {{ isset($_GET['time']) && $_GET['time'] == 'week' ? 'selected' : null }}
                                                        value="week">Неделя</option>
                                                    <option
                                                        {{ isset($_GET['time']) && $_GET['time'] == 'month' ? 'selected' : null }}
                                                        value="month">Месяц</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="sort">Сортировка</label>
                                                <select name="sort" class="form-control" id="sort">
                                                    <option
                                                        {{ isset($_GET['sort']) && $_GET['sort'] == 'old' ? 'selected' : null }}
                                                        value="old">Сначала старые</option>
                                                    <option
                                                        {{ isset($_GET['sort']) && $_GET['sort'] == 'new' ? 'selected' : null }}
                                                        value="new">Сначала новые</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="answer">Ответ</label>
                                                <select name="answer" class="form-control" id="answer">
                                                    <option
                                                        {{ isset($_GET['answer']) && $_GET['answer'] == 'all' ? 'selected' : null }}
                                                        value="all">Любые</option>
                                                    <option
                                                        {{ isset($_GET['answer']) && $_GET['answer'] == 'unanswered' ? 'selected' : null }}
                                                        value="unanswered">Без ответа</option>
                                                    <option
                                                        {{ isset($_GET['answer']) && $_GET['answer'] == 'answered' ? 'selected' : null }}
                                                        value="answered">С ответом</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-info">Найти</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        @if ($questions->count() == 0)
                            <div class="col-md-12">
                                <p>
                                    Нет записей
                                </p>
                            </div>
                        @else
                            @foreach ($questions as $question)
                                <div class="row question">
                                    <div class="col-md-1">
                                        <p class="stats-reply">{{ $question->reply_qty }}</p>
                                        <p class="stats-favorite">{{ $question->likes_qty }}</p>
                                        @if ($question->answer_qty > 0)
                                            <p class="stats-answer">{{ $question->answer_qty }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <a
                                                    href="{{ route('question.show', $question->slug) }}">{{ $question->title }}</a>
                                                <div>
                                                    <p>{{ strlen(str_replace(['[code], [/code]'], '', $question->description)) > 400
                                                        ? mb_substr(str_replace(['[code], [/code]'], '', $question->description), 0, 400) . '...'
                                                        : str_replace(['[code]', '[/code]'], ['', ''], $question->description) }}
                                                    </p>
                                                </div>
                                                <div>
                                                    @foreach ($question->tags as $tag)
                                                        <a href="/question?tags%5B%5D={{ $tag->tag_id }}"
                                                            class="tag-item-name" title="">{{ $tag->tag_title }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @can('edit-questions', $question->user_id)
                                                <div class="col-md-2">
                                                    <form action="{{ route('question.edit', $question->slug) }}"
                                                        method="get">
                                                        <button type="submit" class="btn btn-outline-info">Изменить</button>
                                                    </form>
                                                    <form action="{{ route('question.destroy', $question->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger">Удалить</button>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                        <div class="stats">
                                            <div>
                                                <span>Создан:</span>
                                                <time itemprop="dateCreated"
                                                    datetime="{{ $question->created_at }}">{{ \Carbon\Carbon::parse($question->created_at)->format('h:m d/m/Y') }}</time>
                                            </div>
                                            <div>
                                                @if ($question->last_reply)
                                                    <span class="">Последний ответ:</span>
                                                    <span
                                                        title="{{ $question->last_reply }}">{{ \Carbon\Carbon::parse($question->last_reply)->format('h:m d/m/Y') }}</span>
                                                @else
                                                    <span class="">Пока нет ответов</span>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="">Вопрос задал:</span>
                                                <a
                                                    href="{{ route('user.show', $question->user->id) }}">{{ $question->user->name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="card-footer">
                        {{ $questions->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $('.select2').select2()
    </script>
@endsection
