<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use DB;

class SettingService
{

    public function __construct()
    {

    }

    public function getSetting($metaName)
    {

        $findSetting = null;
        $metaName = trim( $metaName );

        if( is_null($metaName) | empty($metaName) )
            return $findSetting;
        
        $setting = Setting::where('name', $metaName)->first();

        if ($setting) {
            $findSetting = $setting->value;
        }

        return $findSetting;
    }

    public function createSetting( $metaName , $metaValue )
    {
        $success = true;
        $id = null;
        $metaName = htmlspecialchars( $metaName , ENT_QUOTES);
        $metaValue = htmlspecialchars( $metaValue , ENT_QUOTES);

        if( is_null($metaName) | empty($metaName) )
            return false;

        try {

            $findSetting = $this->getSetting($metaName);
            if( is_null( $findSetting) ){
                $setting = new Setting();
                $setting->name = $metaName;
                $setting->value = $metaValue;
                $id = $setting->save();
            }else{
                $setting = Setting::find($findSetting->id);
                $setting->value = $metaValue;
                $id = $setting->save();
            }
            
        } catch (\Exception $e) {
            dd($e);
            $success = false;
        }

        return $id;

    }

    public function updateSetting( $metaName , $metaValue )
    {
        $success = true;
        $id = null;
        $metaName = htmlspecialchars( $metaName , ENT_QUOTES);
        $metaValue = htmlspecialchars( $metaValue , ENT_QUOTES);

        if( is_null($metaName) | empty($metaName) ){
            return false;
        }

        try {

            $findSetting = Setting::where('name', $metaName)->first();

            if($findSetting){
                $setting = Setting::find($findSetting->id);
                $setting->value = $metaValue;
                $id = $setting->save();
            }else{
                $id = $this->createSetting( $metaName , $metaValue  );
                
            }
            
        } catch (\Exception $e) {
            dd($e);
            $success = false;
        }

        return $id;

    }

    

}