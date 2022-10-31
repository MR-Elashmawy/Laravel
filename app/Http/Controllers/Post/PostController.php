<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::orderBy('updated_at', 'DESC')->paginate(10);
        return view('posts.index', compact('posts'));
    } // end of index


    public function create()
    {
        $users = User::all('id', 'name');
        // dd($users);
        return view('posts.create', compact('users'));
    } // end of create


    public function store(StorePostRequest $request)
    {
        if (request()->hasFile('image')) {
            $image = $request->file('image');
            $imgExt = $image->extension();
            $imgName = request()->title . time() . "." . $imgExt;
            $path = public_path('\images\posts');
            $image->move($path, $imgName);
        } else {
            $imgName = 'default.png';
        }
        $formData = request()->all();
        $formData['image'] = $imgName;
        // dd($formData);
        Post::create($formData);
        //show success message
        return to_route('posts.create')->with('message', 'Post created successfully');
    } // end of store


    public function show($slug)
    {
        $post = Post::withTrashed()->where('slug', $slug)->get()->first();
        return view('posts.show', compact('post'));
    } // end of show

    public function edit($slug)
    {
        $post = Post::withTrashed()->where('slug', $slug)->get()->first();
        $users = User::all();
        return view('posts.edit', compact('post', 'users'));
    } // end of edit

    public function update(UpdatePostRequest $request, $slug)
    {
        $post = Post::withTrashed()->where('slug', $slug)->get()->first();
        $oldImg = $post->image; // old image name

        if (request()->hasFile('image')) {
            $image = $request->file('image');
            $imgExt = $image->extension();
            $imgName = request()->title . time() . "." . $imgExt;
            $path = public_path('\images\posts');
            $image->move($path, $imgName); //upload new image

            if ($oldImg !== 'default.png') { // don't remove default image
                $image_path =  public_path('images/posts/' . $oldImg);
                if (file_exists($image_path)) {
                    @unlink($image_path); //delete old image
                }
            }
        } else { //user doesn't update image
            $imgName = $oldImg;
        }

        $formData = request()->all();
        $formData['image'] = $imgName;
        // dd(request()->all());
        $post->title = request()->title;
        $post->details = request()->details;
        $post->image =  $imgName;
        $post->user_id = request()->user_id;
        $post->save();
        //show success message
        return to_route('posts.edit', $slug)->with('message', 'Post updated successfully');
    } // end of update

    public function deletedPosts()
    {
        $posts = Post::onlyTrashed()->orderBy('updated_at', 'DESC')->paginate(10);
        return view('posts.deleted', compact('posts')); //return delted posts page

    } // end of delted posts

    public function destroy($id)
    {
        Post::find($id)->delete(); //soft delete
        // show success message
        return to_route('posts.index')->with('message', 'Post deleted successfully');
    } // end of destroy

    public function deleteForEver($id)
    {
        $post = Post::withTrashed()->where('id', $id)->get()->first();
        $oldImg = $post->image;
        $post->forceDelete();
        //delete image from server
        if ($oldImg !== 'default.png') { // don't remove default image
            $image_path =  public_path('images/posts/' . $oldImg);
            if (file_exists($image_path)) {
                @unlink($image_path); //delete old image
            }
        }

        // show success message
        return to_route('posts.deleted')->with('message', 'Post deleted successfully');
    } // end of delte forever

    public function restorePost($id)
    {
        Post::where('id', $id)->restore();
        // show success message
        return to_route('posts.deleted')->with('message', 'Post restored successfully');
    } // end of restore post
}// end of class
