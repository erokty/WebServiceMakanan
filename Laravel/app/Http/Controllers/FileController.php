<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class FileController extends ApiController
{

    public function __construct()
    {
    }

    /**
     * @description: Api Download File method
     * @author: Jordy Julianto
     * @param: name
     * @return: Json String response
     */
    public function download($filename){
  
    $file = Storage::disk('local')->get($filename);
    return (new Response($file, 200))
              ->header('Content-Type', "image/jpeg");
  }
}
