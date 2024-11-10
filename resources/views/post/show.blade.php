@extends('layouts.admin')

@section('content')
<div>
    <tr>
        <td>{{ $post->id }}</td>
        <td>{{ $post->title }}</td>
        <td>{!! $post->content !!}</td>

        <td>
            <a href="{{ route('post.edit', $post) }}" class="btn btn-info">Изменить</a>
        </td>

        <td>
            <form action="{{ route('post.delete', $post) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        </td>
    </tr>
</div>


@endsection
