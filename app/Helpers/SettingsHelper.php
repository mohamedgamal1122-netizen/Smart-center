<?php
if(!function_exists('setting')){
    function setting(string $key, $default=null){
        try{
            return \App\Models\Setting::get($key,$default);
        }catch(\Throwable $e){ return $default; }
    }
}
if(!function_exists('center_name')){
    function center_name(){ return setting('center_name', config('app.name','سمارت سنتر')); }
}
if(!function_exists('center_logo')){
    function center_logo(){
        $logo = setting('center_logo');
        return $logo ? asset('storage/'.$logo) : null;
    }
}
