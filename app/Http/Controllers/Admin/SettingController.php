<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    use FileUploadTrait;
    public function index():View{
       // dd(config('mail'));
        return view('admin.setting.index');
    }//end method

    public function updateGeneralSetting(Request $request){
       
       $validatedData = $request->validate([
                        'site_name' => ['required','max:255'],
                        'site_default_currency' =>  ['required','max:4'],
                        'site_currency_icon' =>  ['required','max:4'],
                        'site_currency_icon_position' =>  ['required','max:255'],
                        ]);

        foreach($validatedData as $key=>$value){
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        $settingsService = app(SettingService::class);
        $settingsService->clearCacheSettings();

        toastr('Updated Successfully !','success');

        return redirect()->back();
    }//end method

    public function UpdatePusherSetting(Request $request){
        $validatedData = $request->validate([
            'pusher_app_id' => ['required'],
            'pusher_key' =>  ['required'],
            'pusher_secret' =>  ['required'],
            'pusher_cluster' =>  ['required'],
            ]);

        foreach($validatedData as $key=>$value){
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        }
        $settingsService = app(SettingService::class);
        $settingsService->clearCacheSettings();

        toastr('Updated Successfully !','success');

        return redirect()->back();
    }//end method
     public function updateMailSetting(Request $request){
        $validatedData = $request->validate([
            'mail_driver' => ['required'],
            'mail_host' =>  ['required'],
            'mail_port' =>  ['required'],
            'mail_username' =>  ['required'],
            'mail_password' =>  ['required'],
            'mail_encryption' =>  ['required'],
            'mail_from_address' =>  ['required'],
            'mail_receive_address' =>  ['required'],
            ]);

        foreach($validatedData as $key=>$value){
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        }
        $settingsService = app(SettingService::class);
        $settingsService->clearCacheSettings();
        Cache::forget('mail_settings');

        toastr('Updated Successfully !','success');

        return redirect()->back();
    }//end method

    function updateLogoSetting(Request $request) : RedirectResponse{
        $validatedData = $request->validate([
            'logo' => ['nullable','image','max:1000'],
            'footer_logo' =>  ['nullable','image','max:1000'],
            'favicon' =>  ['nullable','image','max:1000'],
            'breadcrumb' =>  ['nullable','image','max:1000'],          
            ]);

        foreach($validatedData as $key=>$value){
            
            $imagePath = $this->UpdateImage($request, $key);
            if(!empty($imagePath)){
                
                $oldPath = config('settings.'.$key);
                $this->removeImage($oldPath);

                 Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $imagePath]
                 );
            }
       
    
        }
        $settingsService = app(SettingService::class);
        $settingsService->clearCacheSettings();      

        toastr('Updated Successfully !','success');

        return redirect()->back();
    }//end method

    function updateAppearanceSetting(Request $request){
        $validatedData= $request->validate([
            'site_color' => ['required']
        ]);

        foreach($validatedData as $key=>$value){
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            }
            $settingsService = app(SettingService::class);
            $settingsService->clearCacheSettings();            
    
            toastr('Updated Successfully !','success');
    
            return redirect()->back();
    }//end method

    function updateSeoSetting(Request $request){
        $validatedData= $request->validate([
            'seo_title' => ['required','max:255'],
            'seo_description' => ['nullable','max:600'],
            'seo_keywords' => ['nullable']
        ]);

        foreach($validatedData as $key=>$value){
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            }
            $settingsService = app(SettingService::class);
            $settingsService->clearCacheSettings();           
    
            toastr('Updated Successfully !','success');
    
            return redirect()->back();
    }//end method
}
