@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Редактировать пост</h1>

    <form action="{{ route('post.update', $post->id) }}" method="POST" id="edit-post-form" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="title">Название</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">URL</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $post->slug) }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label" for="inputEmail">Body:</label>
            <div id="quill-editor" class="mb-3" style="height: 300px;"></div>
            <textarea rows="3" class="mb-3 d-none" name="content" id="quill-editor-area"></textarea>

            @error('body')
            <span class="text-danger">{{ $message }}</span>
            @endif
        </div>



        <input type="submit" class="btn btn-primary" value="Сохранить изменения">
        <a href="{{ route('post.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script>

    // Получаем содержимое из data-атрибута
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }], // Header levels
                    ['bold', 'italic', 'underline', 'strike'], // Basic formatting
                    [{ 'color': [] }, { 'background': [] }], // Text color and background color
                    [{ 'script': 'sub' }, { 'script': 'super' }], // Subscript/superscript
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }], // Lists
                    [{ 'align': [] }], // Text alignment
                    ['link', 'image', 'video'], // Links, images, videos
                    ['clean'] // Remove formatting
                ]
            }
        });

        quill.root.style.fontFamily = 'Cygre, sans-serif';

        let content = {!! json_encode($post->content) !!};
        if (content) {
            quill.clipboard.dangerouslyPasteHTML(content);
        }

        // Маппинг русских букв на латиницу
        const ruToLat = {
            а: 'a', б: 'b', в: 'v', г: 'g', д: 'd', е: 'e', ё: 'yo', ж: 'zh', з: 'z', и: 'i', й: 'y', к: 'k',
            л: 'l', м: 'm', н: 'n', о: 'o', п: 'p', р: 'r', с: 's', т: 't', у: 'u', ф: 'f', х: 'h', ц: 'ts', ч: 'ch',
            ш: 'sh', щ: 'sch', ы: 'y', э: 'e', ю: 'yu', я: 'ya', ' ': '-', ь: '', ъ: '',
            А: 'A', Б: 'B', В: 'V', Г: 'G', Д: 'D', Е: 'E', Ё: 'Yo', Ж: 'Zh', З: 'Z', И: 'I', Й: 'Y', К: 'K',
            Л: 'L', М: 'M', Н: 'N', О: 'O', П: 'P', Р: 'R', С: 'S', Т: 'T', У: 'U', Ф: 'F', Х: 'H', Ц: 'Ts', Ч: 'Ch',
            Ш: 'Sh', Щ: 'Sch', Ы: 'Y', Э: 'E', Ю: 'Yu', Я: 'Ya'
        };

        function rusToLat(str) {
            return str.split('').map(function(char) {
                return ruToLat[char] || char;
            }).join('');
        }

        // Генерация slug на основе title
        document.getElementById('title').addEventListener('input', function () {
            var title = document.getElementById('title').value;

            // Преобразуем русский текст в латиницу
            var slug = rusToLat(title)
                .toLowerCase() // Преобразуем в нижний регистр
                .replace(/[^\w\s-]/g, '') // Удаляем все символы, кроме букв, цифр и пробела
                .trim() // Убираем пробелы с концов
                .replace(/\s+/g, '-') // Заменяем пробелы на дефисы
                .replace(/-+/g, '-'); // Убираем лишние дефисы

            // Убираем дефисы в начале и в конце строки
            slug = slug.replace(/^-+/, '').replace(/-+$/, '');

            // Обновляем значение инпута slug
            document.getElementById('slug').value = slug;
        });


        // Проверка содержимого перед отправкой формы
        document.querySelector('form').onsubmit = function(event) {
            var content = quill.root.innerHTML;

            if (!content.trim()) {
                event.preventDefault();
                alert("Пожалуйста, введите содержимое.");
                return;
            }

            document.getElementById('content').value = content;
        };
    });
</script>
@endsection
