<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(){
        $center_name = Setting::get('center_name','سمارت سنتر');
        $center_slogan = Setting::get('center_slogan','نظام إدارة السنتر الذكي');
        $center_phone = Setting::get('center_phone','');
        $center_address = Setting::get('center_address','');
        $center_logo = Setting::get('center_logo','');
        return view('settings.index', compact('center_name','center_slogan','center_phone','center_address','center_logo'));
    }
    public function update(Request $request){
        $request->validate([
            'center_name'=>'required|string|max:100',
            'center_slogan'=>'nullable|string|max:150',
            'center_phone'=>'nullable|string|max:30',
            'center_address'=>'nullable|string|max:200',
            'center_logo'=>'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'remove_logo'=>'nullable|boolean',
        ],[
            'center_name.required'=>'اسم السنتر مطلوب',
        ]);

        Setting::set('center_name', $request->center_name);
        Setting::set('center_slogan', $request->center_slogan);
        Setting::set('center_phone', $request->center_phone);
        Setting::set('center_address', $request->center_address);

        if($request->boolean('remove_logo')){
            $old = Setting::get('center_logo');
            if($old) Storage::disk('public')->delete($old);
            Setting::set('center_logo','');
        }elseif($request->hasFile('center_logo')){
            $old = Setting::get('center_logo');
            if($old) Storage::disk('public')->delete($old);
            $path = $request->file('center_logo')->store('logos','public');
            Setting::set('center_logo',$path);
        }

        return back()->with('success','تم حفظ الإعدادات — الاسم واللوجو اتحدثوا فوراً');
    }
}
