<?php
namespace App\Helpers;

class VisionClient{

    private $mainData;

    public function __construct()
    {

    }

    public function getByBase64($base64){
        $mainData = [
            'content' => $base64
        ];
        return $this->getOcr($mainData);
    }

    public function getByUrl($url){
        $mainData = [
            'source' => [
                'imageUri' => $url
            ]
        ];
        return $this->getOcr($mainData);
    }

    public function getOcr($mainData){
        $client = new \GuzzleHttp\Client();
        $json = [
            'json' => ['requests' => [
                'image' => $mainData,
                'features' => [
                    'type' => 'TEXT_DETECTION'
                ]
            ]],
            'http_errors' => false,
        ];
        $response = $client->request('POST', 'https://vision.googleapis.com/v1/images:annotate?key=' . env('VISION_API_KEY'), $json
            );
        return json_decode($response->getBody());
    }

    public function checkBomDia($visionResult){

    }
}