<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        try {
            $data['posts'] = Post::all();

            return view('post', compact('data'));
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }
    
    public function show(Request $request, $id)
    {
        try {
            $data['post'] = Post::findOrFail($id);

            return view('post', compact('data'));
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
            ]);

            $post = Post::create($validated);

            $data['posts'] = Post::all();
            return route('post', compact('data'));
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            $validated = $request->validate($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
            ]);

            $post->update($validated);


            $data['posts'] = Post::all();
            return view('post', compact('data'));
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }
    
    public function deletePost($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            // $data['posts'] = Post::all();
            return response()->json(['message' => 'Post deleted successfully.']);

        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
