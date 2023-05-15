<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class UploadController extends Controller
{
    public function upload(Request $request,$driver_id){
        $fileName = substr(json_encode($request->all()), 2, -5);
//        error_log($fileName);
//        return response()->json(['success'=>'You have successfully upload file.']);
//        exit();
        $file = $request->file($fileName);
        $name = time().$file->getClientOriginalName();
//        if ($type=='shopUser'){
            $ruta=public_path('/images/'.$name);
            Image::make($file->getRealPath())
                ->resize(300,300
                    ,function ($constraint){
                        $constraint->aspectRatio();
                    }
                )
                ->save($ruta,72);
            $driver = Driver::find($driver_id);
            $driver->foto = $name;
            $driver->save();
            return $name;
//        }
//        if ($type=='fileCreate'){
//            error_log($name);
//            $ruta=public_path('/files/');
//            $file->move($ruta,$name);
//            return $name;
//        }

    }
    public function base64($photo){
        $path = public_path('/images/'.$photo);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
