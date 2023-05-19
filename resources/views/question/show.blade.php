@extends('layouts.base')

@section('title')
    {{ $question->title }}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h2>{{ $question->title }}</h2>
                            </div>
                            @can('edit-questions', $question->user_id)
                                <div class="col-md-2">
                                    <form action="{{ route('question.edit', $question->slug) }}" method="get">
                                        <button type="submit" class="btn btn-outline-info">Изменить</button>
                                    </form>
                                    <form action="{{ route('question.destroy', $question->id) }}" method="post">
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
                                    datetime="{{ $question->created_at }}">{{ \Carbon\Carbon::parse($question->created_at)->format('d/m/Y') }}</time>
                            </div>
                            <div>
                                @if ($replies->count() > 0)
                                    <span class="">Последний ответ:</span>
                                    <span
                                        title="{{ $replies->max('created_at') }}">{{ \Carbon\Carbon::parse($replies->max('created_at'))->format('d/m/Y') }}</span>
                                @else
                                    <span class="">Пока нет ответов</span>
                                @endif
                            </div>
                            <div>
                                <span class="">Вопрос задал:</span>
                                <a href="{{ route('user.show', $question->user->id) }}">{{ $question->user->name }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="thread-description">
                            <?php
                            echo '<p class=paragraph>' . str_replace(["\n", '[code]', '[/code]'], ['<p class=paragraph>', '<pre>', '</pre>'], htmlspecialchars($question->description));
                            ?>
                            <div>
                                @foreach ($question->tags as $tag)
                                    <a href="/question?tags%5B%5D={{ $tag->tag_id }}" class="tag-item-name"
                                        title="">{{ $tag->tag_title }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer card-header">
                        <h5>Ответы:</h5>
                    </div>
                    <div class="card-body">
                        @if ($replies->count() == 0)
                            <p>На этот вопрос ещё нет ответов</p>
                        @endif
                        @foreach ($replies as $reply)
                            <section class="replies-item" id="reply-{{ $reply->id }}">
                                <div class="col-md-10 {{ $reply->status == 'Ответ' ? 'answer' : 'replies-content' }}">
                                    <div class="replies-text">
                                        <p>
                                            <?php
                                            echo '<p class=paragraph>' . str_replace(["\n", '[code]', '[/code]'], ['<p class=paragraph>', '<pre>', '</pre>'], htmlspecialchars($reply->description));
                                            ?>
                                        </p>
                                    </div>
                                    <div class="stats">
                                        <div>
                                            <form action="{{ route('reply.like.store', $reply->id) }}" class=""
                                                method="POST">
                                                @csrf
                                                <button class="favorite" type="submit">{{ $reply->likes_qty }}</button>
                                            </form>
                                        </div>
                                        <div class="stats-edit">
                                            <span>Ответил</span>
                                            <a href="{{ route('user.show', $reply->user_id) }}">: {{ $reply->name }}</a>
                                        </div>
                                        <div>
                                            <span>{{ \Carbon\Carbon::parse($reply->created_at)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="replies-act col-md-2">
                                    @can('delete-reply')
                                        <div>
                                            <form action="{{ route('reply.destroy', $reply->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Удалить</button>
                                            </form>
                                        </div>
                                    @endcan
                                    @can('mark-answer', $question->user_id)
                                        <div>
                                            @if ($reply->status == 'Ответ')
                                                <form action="{{ route('reply.update', $reply->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-outline-success">Это не ответ</button>
                                                </form>
                                            @else
                                                <form action="{{ route('reply.update', $reply->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-outline-success">Это ответ</button>
                                                </form>
                                            @endif
                                        </div>
                                    @endcan
                                </div>
                            </section>
                        @endforeach
                        <div>
                            {{ $replies->withQueryString()->links() }}

                        </div>
                    </div>
                    <div class="card-footer">
                        <label for="description">Ваш комментарий:</label>
                        <div>
                            <button class="btn code">CODE</button>
                        </div>
                        <form action="{{ route('question.reply.store', $question->id) }}" method="POST">
                            @csrf
                            <textarea class="editor-placeholder form-control" name="description" cols="30" rows="10" required></textarea>
                            <button class="btn" type="submit">Оставить комментарий</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('.code').click(function() {
                // console.log($('#description').val);
                $('textarea').val($('textarea').val() + "[code]\n\n[/code]");
            })
        })
    </script>
@endsection
