<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Inertia\Inertia;

class Dashboardcontroller extends Controller
{
function index(){
         $user = auth()->user()->load('role');
    if (Gate::allows('view-admin-dashboard',$user)) {
    return Inertia::render('Dashboard');

    }elseif(Gate::allows('view-student-dashboard',$user)){
        return Inertia::render('StudentDashboard');
    }elseif(Gate::allows('view-teacher-dashboard',$user)){
        return Inertia::render('TeacherDashboard');
    }
    else{
        abort(403, 'Unauthorized');
    }

}
function students(){
         $user = auth()->user()->load('role');
    if (Gate::denies('view-admin-dashboard',$user)) {
        abort(403, 'Unauthorized');
    }
 return Inertia::render('Students');
    }
    function teachers(){
        $user = auth()->user()->load('role');
        if (Gate::denies('view-admin-dashboard',$user)) {
            abort(403, 'Unauthorized');
        }
        return Inertia::render('Teachers');
    }
    function enrollments(){
        $user = auth()->user()->load('role');
        if (Gate::denies('view-admin-dashboard',$user)) {
            abort(403, 'Unauthorized');
        }
        return Inertia::render('Enrollments');
    }
    function courses(){
         $user = auth()->user()->load('role');
    if (Gate::denies('view-admin-dashboard',$user)) {
        abort(403, 'Unauthorized');
    }
return Inertia::render('Courses');

}
}