<?php

namespace App\Http\Controllers;

use App\Helpers\BomDiaChecker;
use App\Helpers\VisionClient;
use Illuminate\Http\Request;

class ImageProcessController extends Controller
{
    function processApi(Request $request){
        $vision = new VisionClient();
        $response = null;

        if($request->hasFile('img_file')){
            $this->validate($request, [
                'img_file' => 'image'
            ]);
            $file = $request->file('img_file');
            $path = $file->getRealPath();
            $data = file_get_contents($path);
            $base64 = base64_encode($data);
            $response = $vision->getByBase64($base64);
        }
        else if($request->has('img_base64')){
            $base64 = $request->img_base64;
            $response = $vision->getByBase64($base64);
        }
        else if($request->has('img_url')){
            $response = $vision->getByUrl($request->img_url);
        }
        else{
            return response(['erro' => 'parametros inválidos'], 422);
        }

        if(isset($response->error)){
            return response(['erro' => 'malformed data'], 422);
        }

        if(isset($response->responses[0]->error)){
            return response($response->responses->error, 422);
        }

        if(!isset($response->responses[0]->textAnnotations)){
            return ['bom_dia' => 'false'];
        }

        $containsBomDia = BomDiaChecker::check($response->responses[0]->textAnnotations[0]->description);
        if($containsBomDia) {
            return ['bom_dia' => 'true'];
        }
        return ['bom_dia' => 'false'];
    }
}
