<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Group;
use App\Models\Enrollment;
use App\Models\Session;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── المستخدمون ──
        $admin = User::create([
            'name' => 'المدير العام',
            'email' => 'admin@center.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '01000000001',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $reception = User::create([
            'name' => 'موظف الاستقبال',
            'email' => 'reception@center.test',
            'password' => Hash::make('password'),
            'role' => 'receptionist',
            'phone' => '01000000002',
            'is_active' => true,
        ]);
        $accountant = User::create([
            'name' => 'المحاسب',
            'email' => 'accountant@center.test',
            'password' => Hash::make('password'),
            'role' => 'accountant',
            'phone' => '01000000003',
            'is_active' => true,
        ]);
        $teacherUser = User::create([
            'name' => 'أ. أحمد المدرس',
            'email' => 'teacher@center.test',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '01000000004',
            'is_active' => true,
        ]);

        // ── المواد ──
        $math = Subject::create(['name'=>'الرياضيات','stage'=>'المرحلة الإعدادية','description'=>'رياضيات عامة','is_active'=>true]);
        $arabic = Subject::create(['name'=>'اللغة العربية','stage'=>'المرحلة الثانوية','description'=>'نحو وبلاغة','is_active'=>true]);
        $english = Subject::create(['name'=>'اللغة الإنجليزية','stage'=>'المرحلة الإعدادية','description'=>'لغة إنجليزية','is_active'=>true]);
        $science = Subject::create(['name'=>'العلوم','stage'=>'المرحلة الابتدائية','description'=>'علوم عامة','is_active'=>true]);

        // ── المدرسون ──
        $t1 = Teacher::create(['name'=>'أ. محمد عبد الله','phone'=>'01011111111','email'=>'mohamed@center.test','specialization'=>'الرياضيات','rate_type'=>'per_session','rate_value'=>150,'start_date'=>'2024-09-01','is_active'=>true]);
        $t2 = Teacher::create(['name'=>'أ. سارة أحمد','phone'=>'01022222222','email'=>'sara@center.test','specialization'=>'اللغة العربية','rate_type'=>'percentage','rate_value'=>40,'start_date'=>'2024-09-01','is_active'=>true]);
        $t3 = Teacher::create(['name'=>'أ. خالد محمود','phone'=>'01033333333','email'=>'khaled@center.test','specialization'=>'اللغة الإنجليزية','rate_type'=>'per_session','rate_value'=>120,'start_date'=>'2024-10-01','is_active'=>true]);

        // ── أولياء الأمور ──
        $parents = [];
        for($i=1;$i<=8;$i++){
            $parents[] = ParentModel::create([
                'name' => "ولي الأمر $i",
                'phone_primary' => "010000000".(10+$i),
                'phone_secondary' => "011000000".(10+$i),
                'relation' => $i % 2 ==0 ? 'أب' : 'أم',
                'email' => "parent$i@test.com",
                'address' => "القاهرة - المنطقة $i",
            ]);
        }

        // ── الطلاب (15) ──
        $grades = ['الصف الأول الإعدادي','الصف الثاني الإعدادي','الصف الثالث الإعدادي','الصف الأول الثانوي'];
        $students = [];
        for($i=1;$i<=15;$i++){
            $s = Student::create([
                'code' => 'STU'.str_pad($i,4,'0',STR_PAD_LEFT),
                'name' => "الطالب $i ".['أحمد','محمد','عمر','سارة','نور','خالد','ليلى','يوسف','مريم','حسن'][$i%10],
                'birth_date' => Carbon::now()->subYears(12+$i%6)->subMonths(rand(0,11))->format('Y-m-d'),
                'gender' => $i % 3 ==0 ? 'female' : 'male',
                'grade' => $grades[$i % count($grades)],
                'school' => "مدرسة النور $i",
                'phone' => $i % 4 ==0 ? "0109990000$i" : null,
                'status' => $i==15 ? 'paused' : ($i==14 ? 'withdrawn' : 'active'),
                'enrollment_date' => now()->subDays(rand(5,90))->format('Y-m-d'),
                'notes' => $i % 5 ==0 ? 'طالب مجتهد' : null,
            ]);
            // ربط ولي أمر عشوائي
            $p1 = $parents[array_rand($parents)]; $s->parents()->attach($p1->id); if(rand(0,1)) { $p2 = $parents[array_rand($parents)]; if($p2->id !== $p1->id) try{ $s->parents()->attach($p2->id); }catch(\Exception $e){} }
            $students[] = $s;
        }

        // ── المجموعات (3) ──
        $g1 = Group::create([
            'name'=>'مجموعة الرياضيات - السبت والثلاثاء',
            'subject_id'=>$math->id,
            'grade'=>'الصف الثالث الإعدادي',
            'teacher_id'=>$t1->id,
            'room'=>'قاعة 1',
            'days'=>json_encode(['السبت','الثلاثاء']),
            'start_time'=>'16:00',
            'end_time'=>'17:30',
            'max_students'=>20,
            'monthly_fee'=>350,
            'start_date'=>'2025-09-01',
            'status'=>'open',
        ]);
        $g2 = Group::create([
            'name'=>'مجموعة اللغة العربية - الأحد والأربعاء',
            'subject_id'=>$arabic->id,
            'grade'=>'الصف الأول الثانوي',
            'teacher_id'=>$t2->id,
            'room'=>'قاعة 2',
            'days'=>json_encode(['الأحد','الأربعاء']),
            'start_time'=>'17:30',
            'end_time'=>'19:00',
            'max_students'=>15,
            'monthly_fee'=>300,
            'start_date'=>'2025-09-01',
            'status'=>'open',
        ]);
        $g3 = Group::create([
            'name'=>'مجموعة الإنجليزية - الإثنين والخميس',
            'subject_id'=>$english->id,
            'grade'=>'الصف الثاني الإعدادي',
            'teacher_id'=>$t3->id,
            'room'=>'قاعة 3',
            'days'=>json_encode(['الإثنين','الخميس']),
            'start_time'=>'15:00',
            'end_time'=>'16:30',
            'max_students'=>8,
            'monthly_fee'=>280,
            'start_date'=>'2025-09-01',
            'status'=>'open',
        ]);
        // مجموعة مكتملة للاختبار
        $g4 = Group::create([
            'name'=>'مجموعة العلوم - الجمعة',
            'subject_id'=>$science->id,
            'grade'=>'الصف الأول الإعدادي',
            'teacher_id'=>$t1->id,
            'room'=>'قاعة 4',
            'days'=>json_encode(['الجمعة']),
            'start_time'=>'10:00',
            'end_time'=>'11:30',
            'max_students'=>5,
            'monthly_fee'=>250,
            'start_date'=>'2025-08-01',
            'status'=>'full',
        ]);

        // ── التسجيلات ──
        // 7 طلاب في g1, 5 في g2, 4 في g3, 5 في g4 (مكتملة)
        $enrollData = [
            [$g1, array_slice($students,0,7)],
            [$g2, array_slice($students,5,5)],
            [$g3, array_slice($students,3,4)],
            [$g4, array_slice($students,0,5)],
        ];
        foreach($enrollData as [$group,$sts]){
            foreach($sts as $stu){
                // منع التكرار
                if(Enrollment::where('student_id',$stu->id)->where('group_id',$group->id)->exists()) continue;
                Enrollment::create([
                    'student_id'=>$stu->id,
                    'group_id'=>$group->id,
                    'enrollment_date'=>now()->subDays(rand(10,40))->format('Y-m-d'),
                    'agreed_fee'=>$group->monthly_fee,
                    'discount'=> rand(0,3)==0 ? 50 : 0,
                    'status'=>'active',
                ]);
            }
        }

        // ── الحصص (class_sessions) ──
        $sessions = [];
        foreach([$g1,$g2,$g3] as $g){
            for($d=5;$d>=0;$d--){
                $sessions[] = Session::create([
                    'group_id'=>$g->id,
                    'date'=>now()->subDays($d*3)->format('Y-m-d'),
                    'start_time'=>$g->start_time,
                    'end_time'=>$g->end_time,
                    'status'=> $d==0 ? 'completed' : (rand(0,10)>8 ? 'cancelled' : 'completed'),
                    'notes'=>null,
                ]);
            }
        }

        // ── الحضور ──
        foreach($sessions as $sess){
            $enrolls = Enrollment::where('group_id',$sess->group_id)->where('status','active')->get();
            foreach($enrolls as $en){
                // حضور عشوائي: 80% حاضر، 10% غائب، 5% متأخر، 5% بعذر
                $r = rand(1,100);
                $status = $r<=80 ? 'present' : ($r<=90 ? 'absent' : ($r<=95 ? 'late' : 'excused'));
                // غياب اليوم للاختبار
                if($sess->date == now()->format('Y-m-d') && rand(0,1)) $status = 'absent';
                try{
                    Attendance::create([
                        'session_id'=>$sess->id,
                        'student_id'=>$en->student_id,
                        'status'=>$status,
                        'notes'=> $status=='late' ? 'تأخر 10 دقائق' : null,
                    ]);
                }catch(\Exception $e){}
            }
        }

        // ── الاختبارات ──
        $exam1 = Exam::create([
            'title'=>'اختبار الشهر الأول - رياضيات',
            'group_id'=>$g1->id,
            'subject_id'=>$math->id,
            'exam_date'=>now()->subDays(7)->format('Y-m-d'),
            'max_score'=>50,
            'exam_type'=>'monthly',
            'notes'=>'اختبار شهري',
        ]);
        $exam2 = Exam::create([
            'title'=>'اختبار قصير - عربي',
            'group_id'=>$g2->id,
            'subject_id'=>$arabic->id,
            'exam_date'=>now()->subDays(3)->format('Y-m-d'),
            'max_score'=>20,
            'exam_type'=>'quiz',
        ]);
        $exam3 = Exam::create([
            'title'=>'اختبار قادم - إنجليزي',
            'group_id'=>$g3->id,
            'subject_id'=>$english->id,
            'exam_date'=>now()->addDays(5)->format('Y-m-d'),
            'max_score'=>30,
            'exam_type'=>'quiz',
        ]);

        // نتائج exam1 (7 طلاب)
        $scores1 = [45,38,42,30,48,25,40];
        $en1 = Enrollment::where('group_id',$g1->id)->get();
        foreach($en1 as $idx=>$en){
            $score = $scores1[$idx % count($scores1)];
            ExamResult::create([
                'exam_id'=>$exam1->id,
                'student_id'=>$en->student_id,
                'score'=>$score,
                
                'grade'=> $score>=45 ? 'ممتاز' : ($score>=35 ? 'جيد جداً' : ($score>=25 ? 'جيد' : 'ضعيف')),
                'notes'=>null,
            ]);
        }
        // نتائج exam2
        $scores2 = [18,15,12,20,10];
        $en2 = Enrollment::where('group_id',$g2->id)->get();
        foreach($en2 as $idx=>$en){
            $score = $scores2[$idx % count($scores2)];
            ExamResult::create([
                'exam_id'=>$exam2->id,
                'student_id'=>$en->student_id,
                'score'=>$score,
                
                'grade'=> $score>=18 ? 'ممتاز' : ($score>=15 ? 'جيد جداً' : 'جيد'),
            ]);
        }
        // تحديث ترتيب exam1
        $results = ExamResult::where('exam_id',$exam1->id)->orderByDesc('score')->get();
        foreach($results as $rank=>$r){ $r->rank = $rank+1; $r->save(); }

        // ── المدفوعات (الشهر الحالي والسابق) ──
        $now = Carbon::now('Africa/Cairo');
        foreach(Enrollment::with(['student','group'])->get() as $en){
            // شهر حالي: بعضهم مدفوع كامل، بعضهم جزئي، بعضهم لم يدفع
            $r = rand(1,100);
            $required = $en->agreed_fee - $en->discount;
            if($r <= 40){
                // مدفوع كامل
                Payment::create([
                    'student_id'=>$en->student_id,
                    'group_id'=>$en->group_id,
                    'month'=>$now->month,
                    'year'=>$now->year,
                    'required_amount'=>$en->agreed_fee,
                    'discount'=>$en->discount,
                    'paid_amount'=>$required,
                    
                    'payment_date'=>now()->subDays(rand(1,10))->format('Y-m-d'),
                    'payment_method'=>['cash','transfer','card'][array_rand(['cash','transfer','card'])],
                    'receipt_number'=>'REC-'.str_pad(rand(1,99999),5,'0',STR_PAD_LEFT),
                    'created_by'=>$reception->id,
                    'is_cancelled'=>false,
                ]);
            } elseif($r <= 65){
                // جزئي
                $paid = round($required * 0.5);
                Payment::create([
                    'student_id'=>$en->student_id,
                    'group_id'=>$en->group_id,
                    'month'=>$now->month,
                    'year'=>$now->year,
                    'required_amount'=>$en->agreed_fee,
                    'discount'=>$en->discount,
                    'paid_amount'=>$paid,
                    
                    'payment_date'=>now()->subDays(rand(1,5))->format('Y-m-d'),
                    'payment_method'=>'cash',
                    'receipt_number'=>'REC-'.str_pad(rand(1,99999),5,'0',STR_PAD_LEFT),
                    'created_by'=>$reception->id,
                    'is_cancelled'=>false,
                ]);
            } elseif($r <= 85){
                // لم يدفع (متأخر) - لا ننشئ سجل أو ننشئ بمبلغ صفر
                Payment::create([
                    'student_id'=>$en->student_id,
                    'group_id'=>$en->group_id,
                    'month'=>$now->month,
                    'year'=>$now->year,
                    'required_amount'=>$en->agreed_fee,
                    'discount'=>$en->discount,
                    'paid_amount'=>0,
                    
                    'payment_date'=>null,
                    'payment_method'=>'cash',
                    'receipt_number'=>'REC-'.str_pad(rand(1,99999),5,'0',STR_PAD_LEFT),
                    'created_by'=>$reception->id,
                    'is_cancelled'=>false,
                ]);
            }
            // شهر سابق: 80% مدفوع
            if(rand(1,100) <= 80){
                $prev = $now->copy()->subMonth();
                Payment::create([
                    'student_id'=>$en->student_id,
                    'group_id'=>$en->group_id,
                    'month'=>$prev->month,
                    'year'=>$prev->year,
                    'required_amount'=>$en->agreed_fee,
                    'discount'=>$en->discount,
                    'paid_amount'=>$required,
                    
                    'payment_date'=>$prev->copy()->addDays(rand(1,20))->format('Y-m-d'),
                    'payment_method'=>'cash',
                    'receipt_number'=>'REC-'.str_pad(rand(1,99999),5,'0',STR_PAD_LEFT),
                    'created_by'=>$accountant->id,
                    'is_cancelled'=>false,
                ]);
            }
        }

        // ── المصروفات ──
        $cats = ['rent','electricity','water','salaries','supplies','marketing','maintenance'];
        foreach($cats as $cat){
            Expense::create([
                'category'=>$cat,
                'amount'=> rand(500,5000),
                'expense_date'=>now()->subDays(rand(1,25))->format('Y-m-d'),
                'payment_method'=>'cash',
                'description'=> "مصروف $cat لشهر ". $now->format('Y/m'),
                'created_by'=>$admin->id,
            ]);
        }
        // مصروف كبير
        Expense::create([
            'category'=>'salaries',
            'amount'=>15000,
            'expense_date'=>now()->subDays(2)->format('Y-m-d'),
            'payment_method'=>'transfer',
            'description'=>'رواتب المدرسين - الشهر الحالي',
            'created_by'=>$admin->id,
        ]);

        $this->command->info('✓ تم إنشاء البيانات التجريبية بنجاح');

        // ── نظام التواصل والإشعارات ──
        $this->call(CommunicationSystemSeeder::class);
    }
}



