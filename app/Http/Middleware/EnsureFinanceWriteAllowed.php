<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFinanceWriteAllowed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user && $user->role === 'receptionist') {
            // الاستقبال: عرض فقط للمالية بدون تعديل
            if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch') || $request->isMethod('delete')) {
                // allow if route is not finance? check prefix
                $financePrefixes = ['payments', 'expenses'];
                foreach ($financePrefixes as $prefix) {
                    if ($request->is($prefix) || $request->is($prefix.'/*')) {
                        abort(403, 'الاستقبال لديه صلاحية العرض فقط للمالية بدون تعديل.');
                    }
                }
            }
        }
        if ($user && $user->role === 'teacher') {
            // المدرس لا يعدل إلا مجموعاته — handled in controllers for fine-grained check
            // هنا نمنع المالية تماماً للمدرس
            $financePrefixes = ['payments', 'expenses'];
            foreach ($financePrefixes as $prefix) {
                if ($request->is($prefix) || $request->is($prefix.'/*')) {
                    abort(403, 'المدرس لا يملك صلاحية الوصول للمالية.');
                }
            }
            // also block reports that are finance-related? allow attendance/reports
        }
        return $next($request);
    }
}
