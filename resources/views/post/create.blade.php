@extends('layouts.admin')

@section('title', 'Добавление постов')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Добавить пост</h1>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12 col-md-8">
                <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror" placeholder="Название"
                               value="{{ old('title') }}">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div id="editor" style="height: 300px;"></div>
                        <input type="hidden" name="content" id="content">

                        <!-- Поле для загрузки изображений -->
                        <input type="file" name="image" required>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Добавить">
                        </div>
                </form>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>


<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                ['link', 'image'],
                [{'list': 'ordered'}, {'list': 'bullet'}]
            ]
        }
    });

    document.querySelector('form').onsubmit = function () {
        var content = document.querySelector('#content');
        content.value = quill.root.innerHTML; // Получаем HTML-код из Quill
    };

    document.querySelector('form').addEventListener('submit', function () {
        var editorContent = document.getElementById('editor').innerHTML; // или используйте метод вашего редактора
        document.getElementById('content').value = editorContent;
    });

</script>

<!-- /.content -->
@endsection
