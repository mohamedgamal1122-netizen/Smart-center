<?php

namespace App\Http\Controllers;

use App\Models\Communication;
use App\Models\Ticket;
use App\Models\Task;
use App\Models\Lead;
use App\Services\CommunicationService;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    /**
     * عرض قائمة التواصلات
     */
    public function communicationsIndex()
    {
        $communications = Communication::latest()->paginate(15);
        return view('communications.index', compact('communications'));
    }

    /**
     * عرض نموذج إنشاء تواصل جديد
     */
    public function createCommunication()
    {
        $students = \App\Models\Student::all();
        $parents = \App\Models\ParentModel::all(); // تأكد من اسم النموذج الصحيح
        return view('communications.create', compact('students', 'parents'));
    }

    /**
     * حفظ تواصل جديد
     */
    public function storeCommunication(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:call,message,meeting,complaint,inquiry,enrollment_request,reschedule_request,followup',
            'subject' => 'nullable|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'student_id' => 'nullable|exists:students,id',
            'parent_id' => 'nullable|exists:parents,id',
        ]);

        CommunicationService::createCommunication(
            $validated['student_id'] ?? null,
            $validated['parent_id'] ?? null,
            auth()->id(),
            $validated['type'],
            $validated['subject'] ?? null,
            $validated['description'],
            $validated['priority']
        );

        return redirect()->route('communications.index')
            ->with('success', trans('messages.communication_created'));
    }

    /**
     * عرض تذاكر الدعم
     */
    public function ticketsIndex(Request $request)
    {
        $query = Ticket::with(['student', 'parent', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest()->paginate(15);
        return view('tickets.index', compact('tickets'));
    }

    /**
     * عرض تذكرة محددة
     */
    public function ticketShow($id)
    {
        $ticket = Ticket::with(['student', 'parent', 'assignedTo', 'comments.user', 'attachments'])->find($id);
        
        if (!$ticket) {
            return redirect()->route('tickets.index')
                ->with('error', 'التذكرة غير موجودة');
        }

        return view('tickets.show', compact('ticket'));
    }

    /**
     * إنشاء تذكرة جديدة
     */
    public function createTicket()
    {
        $students = \App\Models\Student::all();
        $parents = \App\Models\ParentModel::all();
        return view('tickets.create', compact('students', 'parents'));
    }

    /**
     * حفظ تذكرة جديدة
     */
    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:technical,billing,academic,complaint,inquiry,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'student_id' => 'nullable|exists:students,id',
            'parent_id' => 'nullable|exists:parents,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket = CommunicationService::createTicket(
            $validated['title'],
            $validated['description'],
            $validated['category'],
            $validated['student_id'] ?? null,
            $validated['parent_id'] ?? null,
            $validated['assigned_to'] ?? null
        );

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', trans('messages.ticket_created'));
    }

    /**
     * إضافة تعليق على تذكرة
     */
    public function addTicketComment(Request $request, $ticketId)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        \App\Models\TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
            'is_internal' => $request->has('is_internal'),
        ]);

        return back()->with('success', trans('messages.comment_added'));
    }

    /**
     * عرض قائمة المهام
     */
    public function tasksIndex(Request $request)
    {
        $query = Task::with(['assignedTo', 'creator', 'student', 'parent', 'ticket', 'lead']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->latest()->paginate(15);
        return view('tasks.index', compact('tasks'));
    }

    /**
     * إنشاء مهمة جديدة
     */
    public function createTask()
    {
        $users = \App\Models\User::all();
        $students = \App\Models\Student::all();
        $parents = \App\Models\ParentModel::all();
        $tickets = Ticket::all();
        $leads = Lead::all();

        return view('tasks.create', compact('users', 'students', 'parents', 'tickets', 'leads'));
    }

    /**
     * حفظ مهمة جديدة
     */
    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'student_id' => 'nullable|exists:students,id',
            'parent_id' => 'nullable|exists:parents,id',
            'ticket_id' => 'nullable|exists:tickets,id',
            'lead_id' => 'nullable|exists:leads,id',
        ]);

        $task = CommunicationService::createTask(
            $validated['title'],
            $validated['assigned_to'] ?? null,
            auth()->id(),
            $validated['description'] ?? null,
            $validated['due_date'] ?? null,
            $validated['priority'],
            $validated['student_id'] ?? null,
            $validated['parent_id'] ?? null,
            $validated['ticket_id'] ?? null,
            $validated['lead_id'] ?? null
        );

        // Update notes if provided
        if (isset($validated['notes'])) {
            $task->update(['notes' => $validated['notes']]);
        }

        return redirect()->route('tasks.index')
            ->with('success', trans('messages.task_created'));
    }

    /**
     * تحديث حالة المهمة
     */
    public function updateTaskStatus(Request $request, $taskId)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,completed,cancelled',
        ]);

        $task = Task::findOrFail($taskId);
        $task->update(['status' => $validated['status']]);

        return back()->with('success', trans('messages.task_updated'));
    }

    /**
     * عرض قائمة العملاء المحتمين
     */
    public function leadsIndex()
    {
        $leads = Lead::with('user')->latest()->paginate(15);
        return view('leads.index', compact('leads'));
    }

    /**
     * إنشاء عميل محتمى جديد
     */
    public function createLead()
    {
        return view('leads.create');
    }

    /**
     * حفظ عميل محتمى جديد
     */
    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'child_name' => 'nullable|string|max:255',
            'child_level' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'source' => 'required|in:referral,social_media,ad,walk_in,other',
            'notes' => 'nullable|string',
            'followup_date' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead = CommunicationService::createLead(
            $validated['name'],
            $validated['phone'] ?? null,
            $validated['email'] ?? null,
            $validated['source'],
            $validated['child_name'] ?? null,
            $validated['child_level'] ?? null,
            $validated['subject'] ?? null,
            $validated['branch'] ?? null
        );

        $lead->update([
            'notes' => $validated['notes'] ?? null,
            'followup_date' => $validated['followup_date'] ?? null,
            'user_id' => $validated['user_id'] ?? null,
        ]);

        return redirect()->route('leads.index')
            ->with('success', trans('messages.lead_created'));
    }

    /**
     * تحويل العميل المحتمى إلى طالب
     */
    public function convertLeadToStudent($leadId)
    {
        $lead = Lead::findOrFail($leadId);

        // إنشاء طالب من بيانات العميل المحتمى
        $student = \App\Models\Student::create([
            'name' => $lead->child_name ?? $lead->name,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'status' => 'active',
            'registration_date' => now(),
        ]);

        // تحديث حالة العميل المحتمى
        $lead->update(['status' => 'converted']);

        return redirect()->route('leads.index')
            ->with('success', trans('messages.lead_converted'));
    }
}
