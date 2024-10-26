<?php

namespace App\Http\Controllers\Admin\Post;
use App\Models\Post;
use Illuminate\Routing\Controller;
use IlluminateHttpRequest;
class PostController extends Controller
{
    public function store(Request $request)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'preview_path' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:posts',
            'content' => 'required|string',
        ]);

        // Создание нового поста
        Post::create($validatedData);

        return redirect()->route('posts.index')->with('success', 'Пост успешно создан!');
    }
}

