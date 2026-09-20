<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // احذف القديم
        Permission::query()->delete();
        Role::query()->delete();
        
        \DB::table('model_has_roles')->delete();
        \DB::table('model_has_permissions')->delete();
        \DB::table('role_has_permissions')->delete();

        // تعريف كل الصلاحيات
        $perms = [
            // الطلاب
            'students.view','students.create','students.edit','students.delete','students.export','students.qr',
            // أولياء الأمور
            'parents.view','parents.create','parents.edit','parents.delete','parents.report',
            // المدرسون
            'teachers.view','teachers.create','teachers.edit','teachers.delete',
            // المواد
            'subjects.view','subjects.manage',
            // المجموعات
            'groups.view','groups.create','groups.edit','groups.delete',
            // التسجيلات
            'enrollments.view','enrollments.create','enrollments.delete',
            // الحصص
            'sessions.view','sessions.create','sessions.edit','sessions.delete','sessions.schedule',
            // الحضور
            'attendances.view','attendances.create','attendances.scan','attendances.sheet','attendances.export',
            // الامتحانات
            'exams.view','exams.create','exams.edit','exams.delete','exams.certificate',
            // المدفوعات
            'payments.view','payments.create','payments.edit','payments.cancel','payments.receipt','payments.export',
            // المصروفات
            'expenses.view','expenses.create','expenses.edit','expenses.delete','expenses.export',
            // التقارير
            'reports.view','reports.revenue','reports.expenses','reports.profit','reports.attendance','reports.students',
            // المستخدمون
            'users.view','users.create','users.edit','users.delete','users.permissions',
            // الإعدادات
            'settings.view','settings.edit',
            // الإشعارات
            'notifications.view',
            // لوحة التحكم
            'dashboard.view',
        ];

        foreach($perms as $p){
            Permission::firstOrCreate(['name'=>$p, 'guard_name'=>'web']);
        }

        // الأدوار
        $admin = Role::firstOrCreate(['name'=>'admin','guard_name'=>'web']);
        $admin->givePermissionTo(Permission::all());

        $reception = Role::firstOrCreate(['name'=>'receptionist','guard_name'=>'web']);
        $reception->givePermissionTo([
            'dashboard.view','students.view','students.create','students.edit','students.qr',
            'parents.view','parents.create','parents.edit',
            'groups.view','enrollments.view','enrollments.create',
            'sessions.view','sessions.create',
            'attendances.view','attendances.create','attendances.scan','attendances.sheet',
            'payments.view','payments.create','payments.receipt',
            'notifications.view',
        ]);

        $accountant = Role::firstOrCreate(['name'=>'accountant','guard_name'=>'web']);
        $accountant->givePermissionTo([
            'dashboard.view','students.view',
            'groups.view',
            'payments.view','payments.create','payments.edit','payments.cancel','payments.receipt','payments.export',
            'expenses.view','expenses.create','expenses.edit','expenses.delete','expenses.export',
            'reports.view','reports.revenue','reports.expenses','reports.profit','reports.attendance','reports.students',
            'notifications.view',
        ]);

        $teacher = Role::firstOrCreate(['name'=>'teacher','guard_name'=>'web']);
        $teacher->givePermissionTo([
            'dashboard.view',
            'groups.view',
            'sessions.view','sessions.create','sessions.schedule',
            'attendances.view','attendances.create','attendances.scan','attendances.sheet',
            'exams.view','exams.create','exams.edit','exams.certificate',
            'students.view',
            'notifications.view',
        ]);

        // ربط المستخدمين الحاليين بالأدوار (حسب حقل role)
        foreach(User::all() as $u){
            $roleName = $u->role ?? 'receptionist';
            if(!in_array($roleName, ['admin','receptionist','accountant','teacher'])) $roleName='receptionist';
            $u->syncRoles([$roleName]);
            echo $u->email.' -> '.$roleName.PHP_EOL;
        }
    }
}
