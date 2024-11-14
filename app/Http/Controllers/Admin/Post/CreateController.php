<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;

class CreateController extends BaseController
{
    public function __invoke()
    {
        return view('post.create');
    }
}
