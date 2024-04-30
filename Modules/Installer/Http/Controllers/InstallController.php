<?php
namespace Modules\Installer\Http\Controllers;



use ZipArchive;
use App\Traits\UpdateTrait;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Nwidart\Modules\Routing\Controller;
use Modules\Installer\Http\Requests\InstallRequest;

class InstallController extends Controller
{
    use UpdateTrait;
    public function index()
    {
        try {
            return view('installer::index');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function getInstall(InstallRequest $request)
    {
        ini_set('max_execution_time', 900); //900 seconds
        $host           = $request->host;
        $db_user        = $request->db_user;
        $db_name        = $request->db_name;
        $db_password    = $request->db_password;

        $first_name     = $request->first_name;
        $last_name      = $request->last_name;
        $email          = $request->email;
        $login_password = $request->password;

        $purchase_code  = $request->purchase_code;

        //check for valid database connection
        try{
            $mysqli = @new \mysqli($host, $db_user, $db_password, $db_name);
        }catch (\Exception $e){
             return redirect()->back()->with('error', 'Please input valid database information.')->withInput($request->all());
        }
        if (mysqli_connect_errno()) {
            return redirect()->back()->with('error', 'Please input valid database information.');
        }
        $mysqli->close();

        // validate code

        $data['DB_HOST']        = $host;
        $data['DB_DATABASE']    = $db_name;
        $data['DB_USERNAME']    = $db_user;
        $data['DB_PASSWORD']    = $db_password;
        $verification = validate_purchase($purchase_code, $data);
        if ($verification === 'success') :
            session()->put('email', $email);
            session()->put('first_name', $first_name);
            session()->put('last_name', $last_name);
            session()->put('login_password', $login_password);
            session()->put('purchase_code', $purchase_code);

            return redirect()->route('install.finalize');
        elseif ($verification === 'connection_error'):
            return redirect()->back()->with('error', 'There is a problem to connect with SpaGreen server.Make sure you have active internet connection!');

        elseif ($verification === false):
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        else:
            return redirect()->back()->with('error', $verification);
        endif;
    }

    public function final()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            foreach (DB::select('SHOW TABLES') as $table) {
                $table_array = get_object_vars($table);
                Schema::drop($table_array[key($table_array)]);
            }
            DB::unprepared(file_get_contents(base_path('public/sql/sql.sql')));
            if (file_exists(base_path('public/sql/sql.sql'))):
                unlink(base_path('public/sql/sql.sql'));
            endif;
            $zip_file = base_path('public/install/installer.zip');
            if (file_exists($zip_file)) {
                $zip = new ZipArchive;
                if ($zip->open($zip_file) === TRUE) {
                    $zip->extractTo(base_path('public/install/installer/'));
                    $zip->close();
                } else {
                    return redirect()->back()->with('error', 'Installation files Not Found, Please Try Again');
                }
                unlink($zip_file);
            }

            $update_file_path = base_path('public/install/installer');

            if(is_dir($update_file_path))
            {
                $config_file = $update_file_path.'/config.json';
                if(file_exists($config_file)) {
                    $config = json_decode(file_get_contents($config_file), true);
                    $this->recurse_copy($update_file_path, base_path('/'));
                }
                else{
                    return redirect()->back()->with('error', 'Config File Not Found, Please Try Again');
                }
            }
            else{
                return redirect()->back()->with('error', 'Installation files Not Found, Please Try Again');
            }

            $this->dataInserts($config);
            $this->envUpdates();
            File::deleteDirectory($update_file_path);
            sleep(3);
            Artisan::call('all:clear');
            return redirect('/')->with('success', 'Installation was Successfull');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    protected function dataInserts($config)
    {
        try {
            DB::transaction(function () {
                $user                = User::find(1);
                $user->email         = session()->get('email');
                $user->first_name    = session()->get('first_name');
                $user->last_name     = session()->get('last_name');
                $user->password      = bcrypt(session()->get('login_password'));
                $user->save();
            });
        } catch (\Throwable $th) {
            dd($th);
        }

    DB::transaction(function () use($config) {
        $code = Setting::where('title','purchase_code')->first();

        if ($code)
        {
            $code->update([
                'value' => session()->get('purchase_code'),
            ]);
        }
        else{
            Setting::create([
                'title' => 'purchase_code',
                'value' => session()->get('purchase_code'),
            ]);
        }

        if (isAppMode())
        {
            $version = $config['app_version'];
            $version_code = $config['app_version_code'];
        }
        else{
            $version = $config['web_version'];
            $version_code = $config['web_version_code'];
        }

        $code       = Setting::where('title','version_code')->first();
        $current_version_no = Setting::where('title','current_version')->first();
        $version_no = Setting::where('title','version')->first();

        if ($code)
        {
            $code->update([
                'value' => $version_code,
            ]);
        }
        else{
            Setting::create([
                'title' => "version_code",
                'value' => $version_code
            ]);
        }

        if ($version_no)
        {
            $version_no->update([
                'value' => $version_code,
            ]);
        }
        else{
            Setting::create([
                'title' => "version",
                'value' => $version_code
            ]);
        }

        if ($current_version_no)
        {
            $current_version_no->update([
                'value' => $version,
            ]);
        }
        else{
            Setting::create([
                'title' => "current_version",
                'value' => $version
            ]);
        }

        if (arrayCheck('removed_directories',$config))
        {
            foreach ($config['removed_directories'] as $directory)
            {
                File::deleteDirectory(base_path($directory));
            }
        }
    });
        app_config();
        pwa_config();
    }

    protected function envUpdates()
    {
        envWrite('APP_URL', URL::to('/'));
        envWrite('MIX_ASSET_URL', URL::to('/').'/public');
        envWrite('APP_INSTALLED', 'yes');
        Artisan::call('key:generate');
        Artisan::call('migrate', ['--force' => true]);
    }
}