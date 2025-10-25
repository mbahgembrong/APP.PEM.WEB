<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    /**
     * create
     * 
     * @return void
     */
    public function create()
    {
        // render view pada posts.create
        return view('posts.create');
    }

    /**
     * store
     * 
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        // validate request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|min:5|max:255',
            'content' => 'required|string|min:10',
        ]);

        // upload image
        $image = $request->file('image');
        $image ->storeAs('public/posts', $image->hashName());
        // create post
        Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // redirect to posts.index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * edit
     * 
     * @param mixed $post
     * @return void
     */
    public function edit(string $id)
    {
        // get data post
        $post = Post::findOrFail($id);
        
        // render view pada posts.edit
        return view('posts.edit', compact('post'));
    }

    /**
     * store
     * 
     * @param Request $request
     * @param mixed $post
     * @return void
     */
    public function update(Request $request, string $id)
    {
        // validate request
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|min:5|max:255',
            'content' => 'required|string|min:10',
        ]);

        // get data post
        $post = Post::findOrFail($id);

        // check if  image is uploaded
        if ($request->hasFile('image')) {

            // delete old image
            Storage::delete('public/posts/'.$post->image);

            // upload new image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            // update post with new image
            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'content' => $request->content,
            ]);
        } else {
            // update post without image
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }

        // redirect to posts.index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * destroy
     * 
     * @param mixed $post
     * @return void
     */
    public function destroy(string $id)
    {

        // get data post
        $post = Post::findOrFail($id);

        // delete image
        Storage::delete('public/posts/'.$post->image);

        // delete post
        $post->delete();

        // redirect to posts.index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
