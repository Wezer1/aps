<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    // Загрузка изображения и сохранение в таблице post_images
    public function uploadImage(Request $request)
    {
        // Проверка на файл изображения
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Получаем файл
            $image = $request->file('image');

            // Генерация уникального имени для изображения
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();

            // Сохранение файла в хранилище
            $imagePath = $image->storeAs('images', $imageName, 'public');

            // Создание записи в таблице post_images (например, для поста с id=1)
            // Замените id=1 на соответствующий идентификатор вашего поста, если нужно.
            // Можно также получить post_id через авторизацию пользователя или другую логику.
            $post = Post::latest()->first(); // Пример: получаем последний созданный пост

            $postImage = PostImage::create([
                'post_id' => $post->id,  // Убедитесь, что у вас есть пост для привязки
                'image_path' => $imagePath,
            ]);

            // Возвращаем URL изображения
            $imageUrl = asset('storage/' . $imagePath);

            return response()->json([
                'success' => true,
                'url' => $imageUrl
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ошибка загрузки изображения'
        ]);
    }

    // Сохранение поста с содержимым
    public function save(Request $request)
    {
        // Логика сохранения поста
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Создание нового поста
        $post = Post::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Пост успешно создан!');
    }
}
