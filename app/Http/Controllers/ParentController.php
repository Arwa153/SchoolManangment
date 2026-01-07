<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parent = auth()->user();
        $children = $parent->students()->with('schoolClass', 'grades', 'behaviorRecords')->get();

        $stats = [
            'total_children' => $children->count(),
            'total_grades' => $children->sum(function($child) {
                return $child->grades->count();
            }),
            'positive_behaviors' => $children->sum(function($child) {
                return $child->behaviorRecords->where('type', 'positive')->count();
            }),
            'negative_behaviors' => $children->sum(function($child) {
                return $child->behaviorRecords->where('type', 'negative')->count();
            }),
        ];

        return view('parent.dashboard', compact('children', 'stats'));
    }

    public function childProfile($id)
    {
        $parent = auth()->user();
        $child = $parent->students()
            ->with(['schoolClass.teacher', 'grades.teacher', 'behaviorRecords.teacher'])
            ->findOrFail($id);

        $recentGrades = $child->grades()->with('teacher')->latest()->take(10)->get();
        $recentBehaviors = $child->behaviorRecords()->with('teacher')->latest()->take(10)->get();

        return view('parent.child-profile', compact('child', 'recentGrades', 'recentBehaviors'));
    }

    public function grades()
    {
        $parent = auth()->user();
        $children = $parent->students()->with(['grades.teacher', 'schoolClass'])->get();

        return view('parent.grades', compact('children'));
    }

    public function behaviors()
    {
        $parent = auth()->user();
        $children = $parent->students()->with(['behaviorRecords.teacher', 'schoolClass'])->get();

        return view('parent.behaviors', compact('children'));
    }
}
