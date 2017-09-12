<?php

namespace CoreCMF\Storage\Http\Controllers\Api;

use Illuminate\Http\Request;
use CoreCMF\Core\Support\Http\Request as CoreRequest;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\Storage\Http\Models\Config;

class ConfigController extends Controller
{
    private $configModel;

    public function __construct(Config $configPro){
       $this->configModel = $configPro;
    }
    public function index(CoreRequest $request)
    {
        return [];
    }
    public function update(Request $request)
    {
        return [];
    }

}
