<?php

namespace App\Http\Controllers\API;

use App\Exports\InvesmentExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Money;
use App\Model\Log;
use App\Model\Profile;
use App\Model\LogAdmin;
use App\Model\LogUser;
use App\Model\Eggs;
use App\Model\Foods;
use App\Model\Pools;
use App\Model\EggTypes;
use Illuminate\Support\Facades\Auth;
use App\Exports\UserExport;
use App\Exports\WalletExport;
use App\Model\Investment;
use App\Model\MUser;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendMailJobs;

use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function getBanner(){
        $banner = DB::table('banner')->get();
        return $this->response(200, $banner);
    }
}
