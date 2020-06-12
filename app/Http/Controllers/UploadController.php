<?php

namespace App\Http\Controllers;

use App\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images= ImageUpload::latest()->get();
        return view('welcome')->withImages($images);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!is_dir(public_path('/images')))
        {
            mkdir(\public_path('/images'),0777);
        }
        $images= Collection::wrap($request->file('file'));
        $images->each(function($image){
            $basename=Str::random(5);

            $original= $basename.'.'.$image->getClientOriginalExtension();
            $thumbnail= $basename.'_thumb.'.$image->getClientOriginalExtension();
             
            Image::make($image)
            ->fit(250,250)
            ->save(public_path('/images/'.$thumbnail));
         
           $image->move(public_path('/images'),$original);
            ImageUpload::create([
                'original'=>'/images/'.$original,
                'thumbnail'=>'/images/'.$thumbnail
            ]);
        });


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageUpload $imageUpload)
    {
        //delete file(original & thumbnail)
        File::delete([
            public_path($imageUpload->original),
            public_path($imageUpload->thumbnail),
        ]);
        //delete record database
        $imageUpload->delete();
        //return
        return redirect('/');
    }
}
