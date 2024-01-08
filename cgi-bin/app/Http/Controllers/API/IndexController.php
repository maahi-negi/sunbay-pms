<?php

namespace App\Http\Controllers\API;

use App\Models\Elevations;
use App\Models\HomeDesignTypes;
use App\Models\Homes;
use App\Models\Inquiries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    protected $authorize_token = "LcMDq4lDWA3Zl8hN45l4xFqMe5jJnLprr17iyLq9DNme!MOa2maMY6F7Yx8B7Ouz";

    public function getColors(Request $request){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://library.biorev.dev/api/get-data');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json","token: $this->authorize_token"
        ));
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            return [];
        } else {
            return json_decode($response,true);
        }
    }
    
}
