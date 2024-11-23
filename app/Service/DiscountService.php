<?php

namespace App\Service;

use App\Models\Category;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class DiscountService
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $htmlContent = $data['description'];

            // Используем DOMDocument для парсинга HTML
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();  // Очистить ошибки после загрузки

            $image = $dom->getElementsByTagName('img')->item(0);

            $filePath = null;
            if ($image) {
                $previewPath = $image->getAttribute('src');
                if (preg_match('/^data:image\/(\w+);base64,/', $previewPath, $type)) {
                    // Определяем расширение изображения
                    $extension = strtolower($type[1]);
                    // Убираем base64 и декодируем изображение
                    $imageData = substr($previewPath, strpos($previewPath, ',') + 1);
                    $imageData = base64_decode($imageData);
                    // Генерируем уникальное имя файла
                    $fileName = 'image_' . time() . '_' . Str::random(10) . '.' . $extension;
                    $filePath = 'discount/images/' . $fileName;

                    Storage::disk('public')->put($filePath, $imageData);

                }
            }

            $discount = Discount::create([
                'name' => $data['name'],
                'preview_path' => $filePath,
                'slug' => $data['slug'],
                'percentage' => $data['percentage'],
                'description' => $htmlContent, // Сохраняем исходный контент
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data,Discount $discount)
    {

        try {
            DB::beginTransaction();
            $htmlContent = $data['description'];

            // Используем DOMDocument для парсинга HTML
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();  // Очистить ошибки после загрузки

            $discount = Discount::findOrFail($discount->id);


            if($discount->preview_path){
                Storage::disk('public')->delete($discount->preview_path);
            }
            $discount->update([
                'name' => $data['name'],
                'preview_path' => null,
                'slug' => $data['slug'],
                'percentage' => $data['percentage'],
                'description' => $htmlContent,
            ]);

            $image = $dom->getElementsByTagName('img')->item(0);

            $filePath = null;
            if ($image) {
                $previewPath = $image->getAttribute('src');
                if (preg_match('/^data:image\/(\w+);base64,/', $previewPath, $type)) {
                    // Определяем расширение изображения
                    $extension = strtolower($type[1]);

                    // Генерируем уникальное имя файла
                    $fileName = 'image_' . time() . '_' . Str::random(10) . '.' . $extension;
                    $filePath = 'post/images/' . $fileName;
                }
                $discount->update([
                    'preview_path' => $filePath,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            abort(500);
        }
    }

    public function delete($discount)
    {
        try {
            DB::beginTransaction();
            if($discount->preview_path){
                Storage::disk('public')->delete($discount->preview_path);
            }
            $discount->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            abort(500);
        }
    }
}
