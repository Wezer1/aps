<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use App\Models\PostImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Post $post)
    {
        $data = $request->validated();

        // Проверка на наличие нового изображения
        if (isset($data['image'])) {
            $image = $data['image'];
            $name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();

            // Удаление старого изображения, если оно существует
            if ($post->preview_path) {
                Storage::disk('public')->delete($post->preview_path);
            }
            $post->update([
                'preview_path' => 'images/'. $name
                // Можно добавить другие поля, если нужно
            ]);


            // Сохранение нового изображения
            $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);
        }

        // Обновление других полей поста
        $post->update([
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'content' => $data['content'],
            // Можно добавить другие поля, если нужно
        ]);

        // Если есть новое изображение, обновляем запись в PostImage
        if (isset($data['image'])) {
            PostImage::updateOrCreate(
                ['post_id' => $post->id],
                ['image_path' => $filePath]
            );
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

    function transliterate($text) {
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
