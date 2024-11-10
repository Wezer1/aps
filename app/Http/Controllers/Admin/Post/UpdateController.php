<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use App\Models\PostImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Post $post)
    {
        $data = $request->validated();

        // Получаем HTML-контент из поля content
        $htmlContent = $data['content'];

        // Используем DOMDocument для парсинга HTML
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();  // Очистить ошибки после загрузки

        // Получаем существующий пост по ID
        $post = Post::findOrFail($post->id);

        // Получаем все старые изображения, связанные с постом
        $oldImages = PostImage::where('post_id', $post->id)->get();

        // Удаляем старые изображения из файловой системы и базы данных
        foreach ($oldImages as $oldImage) {
            // Удаляем файл изображения с диска
            Storage::disk('public')->delete($oldImage->image_path);

            // Удаляем запись из базы данных
            $oldImage->delete();
        }
        // Обновляем данные поста
        $post->update([
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'content' => $htmlContent, // Обновляем контент
        ]);

        // Получаем изображения из контента
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');

            // Проверяем, является ли src изображением в формате base64
            if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
                // Определяем расширение изображения
                $extension = strtolower($type[1]);
                // Убираем base64 и декодируем изображение
                $imageData = substr($src, strpos($src, ',') + 1);
                $imageData = base64_decode($imageData);

                // Генерируем уникальное имя файла
                $fileName = 'image_' . time() . '_' . Str::random(10) . '.' . $extension;
                $filePath = 'uploads/images/' . $fileName;

                // Сохраняем изображение в файловой системе
                Storage::disk('public')->put($filePath, $imageData);

                // Заменяем base64 изображение на URL загруженного файла
                $image->setAttribute('src', Storage::url($filePath));

                // Сохраняем информацию об изображении в таблице post_images
                PostImage::create([
                    'post_id' => $post->id,
                    'image_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('post.index');

    }

    private function generateSlug($title)
    {
        // Удаляем лишние символы и пробелы
        $title = preg_replace('/[^A-Za-z0-9а-яА-ЯёЁ\s-]/u', '', $title);
        $title = trim($title);
        $title = preg_replace('/[\s-]+/', '-', $title);

        return $this->transliterate($title);
    }

    function transliterate($text)
    {
        $transliterationTable = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            // Заглавные буквы
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        ];

        return strtr($text, $transliterationTable);
    }
}
