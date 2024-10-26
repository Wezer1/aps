@extends('layouts.admin')

@section('content')
<div>
    <tr>
        <td>{{ $post->id }}</td>
        <td>{{ $post->title }}</td>
        <td>{!! $post->content !!}</td>
        <img src="{{ asset('storage/' . $post->preview_path) }}" style="max-width: 300px;">

        <td>
            <a href="{{ route('post.edit', $post) }}" class="btn btn-info">Изменить</a>
        </td>
    </tr>
</div>


@endsection
