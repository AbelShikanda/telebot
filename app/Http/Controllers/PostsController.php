<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Posts::all();
        return view('posts.index', with([
            'posts' => $posts,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create', with([
            // 'posts' => Posts::all()
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posts = $request->validate([
            'image' => 'required',
            'caption' => 'required',
        ]);
        
        $file = $request->file('image');
        if (isset($file)) {
            $file = $request->file('image');
            // dd($file);
            $currentDate = now()->toDateString();
            $fileName = $currentDate . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Create new ImageManager instance with desired driver
            $manager = new ImageManager(Driver::class); // or ['driver' => 'gd']

            // Read the image
            $image = $manager->read($file->getPathname());

            // Resize and crop the image to a 2:3 aspect ratio (800x1200)
            $croppedImage = $image->resize(1280, 853);

            // Save the resized and cropped image to storage
            $croppedImagePath = 'app/public/posts/' . $fileName;
            Storage::disk('public')->put($croppedImagePath, (string) $croppedImage->toJpeg());
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
        // dd($fileName);
        
        try {
            DB::beginTransaction();
            
            $posts = Posts::create([
                'image' => $fileName,
                'caption' => $request->caption,
            ]);

            if(!$posts){
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('posts.index')->with('success', 'User Stored Successfully.');


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function show(Posts $posts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Posts::findOrFail($id);
        return view('posts.edit', with([
            'post' => $post,
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Posts $posts)
    {
        $post = Posts::findOrFail($posts->id);
        $posts = $request->validate([
            'image' => 'required',
            'caption' => 'required',
        ]);

        $images = $request->file('image');
        if (isset($images))
        {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $currentDate.'-'.uniqid().'.'.$images->getClientOriginalExtension();
            if (!Storage::disk('public')->exists('posts'))
            {
                Storage::disk('public')->makeDirectory('posts');
            }
            $postImage = Posts::make($images)->resize(320, 370)->stream();
            Storage::disk('public')->put('posts/'.$imageName, $postImage);
        } else
        {
            $imageName = 'default.png';
        }
        
        try {
            DB::beginTransaction();
            
            if ($post) {
                if ($request->image) {
                    $image = $request->image;
                    $post->image = $image;
                }
                if ($request->caption) {
                    $caption = $request->caption;
                    $post->caption = $caption;
                }
                $post->save();
            }

            if(!$posts){
                DB::rollBack();

                return back()->with('error', 'Something went wrong while saving user data');
            }

            DB::commit();
            return redirect()->route('posts.index')->with('success', 'User Stored Successfully.');


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posts $posts)
    {
        //
    }
}
