<?php

namespace Modules\Api\Http\Controllers;

use App\Traits\ApiReturnFormat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Modules\Api\Entities\AppIntro;
use Modules\Language\Entities\Language;

class ConfigController extends Controller
{
    use ApiReturnFormat;

    public function config(Request $request){
        $language   = $request->lang ?? settingHelper('default_language');

        //primary app config
        $app_config['mandatory_login']          = settingHelper('mandatory_login');
        $app_config['logo_url']                 = static_asset(settingHelper('logo'));
        $app_config['privacy_policy_url']       = settingHelper('privacy_policy_url');
        $app_config['terms_n_condition_url']    = settingHelper('terms_n_condition_url');
        $app_config['support_url']              = settingHelper('support_url');

        $intro['is_skippable']                  = settingHelper('intro_skippable');
        $app_config['app_intro']                = ['intro_skippable' => $intro['is_skippable'], 'intro' => $this->imageUrlset($this->appIntro($language))];

        // mobile ads config
        $ads_config['ads_enable']                         =   settingHelper('ads_enable');
        $ads_config['mobile_ads_network']                 =   settingHelper('mobile_ads_network');
        $ads_config['admob_app_id']                       =   settingHelper('admob_app_id');
        $ads_config['admob_banner_ads_id']                =   settingHelper('admob_banner_ads_id');
        $ads_config['admob_interstitial_ads_id']          =   settingHelper('admob_interstitial_ads_id');
        $ads_config['admob_native_ads_id']                =   settingHelper('admob_native_ads_id');

        // get APK version info
        $get_android_version_info['latest_apk_version']         =  settingHelper('latest_apk_version');
        $get_android_version_info['latest_apk_code']            =  settingHelper('latest_apk_code');
        $get_android_version_info['apk_file_url']               =  settingHelper('apk_file_url');
        $get_android_version_info['whats_new_on_latest_apk']    =  settingHelper('whats_new_on_latest_apk');
        $get_android_version_info['apk_update_skipable_status'] =  settingHelper('apk_update_skipable_status');

        // get iOS version info
        $get_ios_version_info['latest_ipa_version']             =  settingHelper('latest_ipa_version');
        $get_ios_version_info['latest_ipa_code']                =  settingHelper('latest_ipa_code');
        $get_ios_version_info['ipa_file_url']                   =  settingHelper('ipa_file_url');
        $get_ios_version_info['whats_new_on_latest_ipa']        =  settingHelper('whats_new_on_latest_ipa');
        $get_ios_version_info['ios_update_skipable_status']     =  settingHelper('ios_update_skipable_status');

        $languages = Language::where('status','active')->get(['id','name','code']);

        $config = [
            'app_config'            => $app_config ,
            'ads_config'            => $ads_config,
            'android_version_info'  => $get_android_version_info,
            'ios_version_info'      => $get_ios_version_info,
            'languages'             => $languages
        ];

        return $this->responseWithSuccess('Successfully data found', $config, 200);
    }


    protected function appIntro($language){
        return AppIntro::where('language',$language)->select('id','language','disk','title','description','image')->get();
    }

    protected function imageUrlset($intros)
    {

        foreach ($intros as $intro) {
            if (isset($intro->image)) {
                if ($intro->disk == 's3') {
                    $s3Link = "https://s3." . Config::get('filesystems.disks.s3.region') . ".amazonaws.com/" . Config::get('filesystems.disks.s3.bucket') . "/";

                    $intro->image = $s3Link . $intro->image;

                } else {
                    $intro->image = static_asset($intro->image);
                }
            }
        }
        return $intros;
    }
}
