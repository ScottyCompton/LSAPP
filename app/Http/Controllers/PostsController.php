<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;

class PostsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check for correct user
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all();
        //$posts = Post::orderBy('created_at', 'desc')->get();
        //$posts = Post::orderBy('created_at', 'desc')->take(1)->get();
        //$posts = DB::select('SELECT * FROM posts');
        //return Post::where('title','Post Two')->get();
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('posts.index')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    protected function saveCoverImage(Request $request, $id=-1) {

        $this->deleteExistingCoverImage($id);

        // Handle image upload if any
        $fileNameToStore = 'noimage.jpg';
        if($request->hasFile('cover_image')) {
            $fileNameWithExt = $request->file('cover_image')->getClientOriginalName();

            // get just file name    
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // get just file ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            
        }
        return $fileNameToStore;
    }


    protected function deleteExistingCoverImage($id) {

        // if this is an existing post and already has a cover image, 
        // delete the existing image first

        if($id != -1) {
            $post = Post::find($id);
            $fileToDelete = $post->cover_image;
            if($fileToDelete != 'noimage.jpg') {
                if (file_exists('public/cover_images/'.$fileToDelete)) {
                    File::delete('public/cover_images/'.$fileToDelete);
                }
            }
        }
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
             'body' => 'required',
             'cover_image' => 'image|nullable|max:1999'
        ]);

        $fileNameToStore = $this->saveCoverImage($request);
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        // Check for correct user
        if(auth()->user()->id != $post->user_id) {
            return redirect('/posts')->with('error', 'You are not authorized to access this page.');
        }

        return view('posts.edit')->with('post', $post);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
             'body' => 'required'
        ]);

        $fileNameToStore = $this->saveCoverImage($request, $id);

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->deleteExistingCoverImage($id);
        $post = Post::find($id);

        // Check for correct user
        if(auth()->user()->id != $post->user_id) {
            return redirect('/posts')->with('error', 'You are not authorized to access this page.');
        }

        $post->delete();

        return redirect('/posts')->with('success', 'Post Deleted');
    }
}
