<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Group;
use App\Models\ParentModel;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['students'=>[],'groups'=>[],'parents'=>[]]);
        }

        $like = "%{$q}%";

        $students = Student::query()
            ->where(function($qq) use ($like){
                $qq->where('name','like',$like)->orWhere('code','like',$like)->orWhere('phone','like',$like);
            })
            ->select('id','name','code','grade')
            ->limit(6)->get()
            ->map(fn($s)=>[
                'id'=>$s->id,
                'name'=>$s->name,
                'code'=>$s->code,
                'grade'=>$s->grade,
                'url'=>route('students.show',$s->id),
                'type'=>'student',
            ]);

        $groups = Group::query()
            ->where('name','like',$like)
            ->with('subject:id,name')
            ->select('id','name','subject_id')
            ->limit(5)->get()
            ->map(fn($g)=>[
                'id'=>$g->id,
                'name'=>$g->name,
                'subject'=>$g->subject->name ?? '',
                'url'=>route('groups.show',$g->id),
                'type'=>'group',
            ]);

        $parents = ParentModel::query()
            ->where(function($qq) use ($like){
                $qq->where('name','like',$like)->orWhere('phone_primary','like',$like);
            })
            ->select('id','name','phone_primary')
            ->limit(5)->get()
            ->map(fn($p)=>[
                'id'=>$p->id,
                'name'=>$p->name,
                'phone'=>$p->phone_primary,
                'url'=>route('parents.show',$p->id),
                'type'=>'parent',
            ]);

        return response()->json([
            'students'=>$students,
            'groups'=>$groups,
            'parents'=>$parents,
        ]);
    }
}
