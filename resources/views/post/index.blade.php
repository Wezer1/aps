@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список постов</h1>

    <a href="{{ route('post.create') }}" class="btn btn-primary">Создать новый пост</a>
    <!-- Кнопка для создания нового поста -->

    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Контент</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post) <!-- Перебираем все посты -->
        <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->title }}</td>
            <td>{!! $post->content !!}</td>
            <td>
                <a href="{{ route('post.show', $post) }}" class="btn btn-info">Просмотреть</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <a href="{{ route('post.create') }}" class="btn btn-primary">Создать новый пост</a>


</div>

@endsection
