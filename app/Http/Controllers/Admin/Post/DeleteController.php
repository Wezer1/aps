<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteController extends Controller
{
    public function __invoke(Post $post)
    {
        $oldImages = PostImage::where('post_id', $post->id)->get();

        // Удаляем старые изображения из файловой системы и базы данных
        foreach ($oldImages as $oldImage) {
            // Удаляем файл изображения с диска
            Storage::disk('public')->delete($oldImage->image_path);

            // Удаляем запись из базы данных
            $oldImage->delete();
        }

        // Удаляем сам пост из базы данных
        $post->delete();

        // Перенаправляем на страницу с индексом постов
        return redirect()->route('post.index');

    }

}
