<?php

namespace Modules\Setting\Http\Controllers;

use App\Traits\UpdateTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Setting\Entities\Setting;

class UpdateController extends Controller
{
    use UpdateTrait;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('setting::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateSystem()
    {
        $fields = [
            'item_id' => isAppMode() ? '33255812' : '29030619',
            'current_version' => settingHelper('version'),
        ];
        $response = false;
        $request = curlRequest("https://desk.spagreen.net/version-check", $fields);

        if (property_exists($request,'status') && $request->status):
            $response = $request->release_info;
        endif;
        
        $latest_version =  $request->release_info->version;
        return view('setting::update_system',compact('latest_version'));

    }

    public function updateSystemStore(){
        
        try {
            $update = $this->downloadUpdateFile();
            if (is_string($update))
            {
                return redirect()->back()->with('error', $update);
            }
            return redirect()->back()->with('success', 'Update Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateDatabase()
    {
        return view('setting::update-database');

    }

    public function updateDatabaseStore(Request $request)
    {
        if (strtolower(\Config::get('app.demo_mode')) == 'yes'):
            return redirect()->back()->with('error', __('You are not allowed to add/modify in demo mode.'));
        endif;

        $setting        = Setting::firstOrNew(array('title' => 'version'));
        $setting->title = 'version';
        $setting->value = $request->version;
        $setting->save();
        $this->endWrite('APP_URL', route('home'));

        Cache::forget('version');

        return redirect()->back()->with('success', __('successfully_updated'));
    }

    private function endWrite($key, $value)
    {
        $env = file_get_contents(isset($env_path) ? $env_path : base_path('.env')); //fet .env file
        $env = str_replace("$key=" . env($key), "$key=", $env); //replace value

        $value = preg_replace('/\s+/', '', $value); //replace special ch
        $key = strtoupper($key); //force upper for security
        $env = file_get_contents(isset($env_path) ? $env_path : base_path('.env')); //fet .env file
        $env = str_replace("$key=" . env($key), "$key=" . $value, $env); //replace value
        /** Save file with new content */
        $env = file_put_contents(isset($env_path) ? $env_path : base_path('.env'), $env);
        return true;
    }
}
