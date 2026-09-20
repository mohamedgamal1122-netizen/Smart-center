<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * عرض قائمة الإشعارات للمستخدم الحالي
     */
    public function index(Request $request)
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead(Notification $notification)
    {
        $this->authorize('view', $notification);
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back();
    }

    /**
     * تحديد كل الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back();
    }

    /**
     * حذف إشعار
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);
        $notification->delete();

        return back();
    }

    /**
     * عدد الإشعارات غير المقروءة — للهيدر
     */
    public function unreadCount()
    {
        $count = auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
