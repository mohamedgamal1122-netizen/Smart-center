<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q=User::with('roles','permissions');
        if($s=$request->input('q')) $q->where('name','like',"%{$s}%")->orWhere('email','like',"%{$s}%");
        if($r=$request->input('role')) $q->where('role',$r);
        $users=$q->latest()->paginate(20)->withQueryString();
        return view('users.index',compact('users'));
    }
    public function create(){ $roles=Role::all(); $permissions=Permission::all()->groupBy(function($p){ return explode('.',$p->name)[0]; }); return view('users.create',compact('roles','permissions')); }
    public function store(UserRequest $request){
        $data=$request->validated();
        $perms = $request->input('permissions',[]);
        $user=User::create($data);
        if(!empty($data['role'])){
            try{ $user->assignRole($data['role']); }catch(\Throwable $e){}
        }
        if(!empty($perms)){
            try{ $user->syncPermissions($perms); }catch(\Throwable $e){}
        }
        return redirect()->route('users.index')->with('success','تم إنشاء المستخدم.');
    }
    public function show(User $user){ $user->load('roles','permissions'); return view('users.show',compact('user')); }
    public function edit(User $user){ $roles=Role::all(); $permissions=Permission::all()->groupBy(function($p){ return explode('.',$p->name)[0]; }); $user->load('roles','permissions'); return view('users.edit',compact('user','roles','permissions')); }
    public function update(UserRequest $request, User $user){
        $data=$request->validated();
        $perms = $request->input('permissions',[]);
        if(empty($data['password'])) unset($data['password']);
        $user->update($data);
        if(!empty($data['role'])){
            try{ $user->syncRoles([$data['role']]); }catch(\Throwable $e){}
        }
        try{ $user->syncPermissions($perms); }catch(\Throwable $e){}
        return redirect()->route('users.index')->with('success','تم التحديث.');
    }
    public function destroy(User $user){
        if(auth()->id()===$user->id) return back()->withErrors(['user'=>'لا يمكنك حذف نفسك.']);
        $user->delete();
        return back()->with('success','تم الحذف.');
    }
    public function toggleActive(User $user){
        $user->update(['is_active'=>!$user->is_active]);
        return back()->with('success','تم تحديث الحالة.');
    }
    // صفحة الصلاحيات المنفصلة — تحكم دقيق
    public function permissions(User $user){
        $allPerms = Permission::all()->groupBy(function($p){ return explode('.',$p->name)[0]; });
        $userPerms = $user->getPermissionNames()->toArray();
        $userRoles = $user->getRoleNames()->toArray();
        $roles = Role::with('permissions')->get();
        return view('users.permissions', compact('user','allPerms','userPerms','userRoles','roles'));
    }
    public function updatePermissions(Request $request, User $user){
        $request->validate(['permissions'=>'nullable|array','permissions.*'=>'exists:permissions,name','roles'=>'nullable|array','roles.*'=>'exists:roles,name']);
        $roles = $request->input('roles',[]);
        $perms = $request->input('permissions',[]);
        try{ $user->syncRoles($roles); }catch(\Throwable $e){}
        try{ $user->syncPermissions($perms); }catch(\Throwable $e){}
        // حدث حقل role للتوافق
        if(!empty($roles)) $user->update(['role'=>$roles[0]]);
        return back()->with('success','تم تحديث الصلاحيات — ستطبق فوراً عند تسجيل الدخول القادم');
    }
}
