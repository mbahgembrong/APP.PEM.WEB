<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        // get posts
        $posts = Post::latest()->paginate(5);

        // render view pada posts.index
        return view('posts.index', compact('posts'));
    }
}
