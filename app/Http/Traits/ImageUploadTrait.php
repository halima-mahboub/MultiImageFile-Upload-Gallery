<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\ImageUpload;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

trait ImageUploadTrait
{
    public function storeImage(Request $request)
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

           // return "ok";
    }
    
}
 