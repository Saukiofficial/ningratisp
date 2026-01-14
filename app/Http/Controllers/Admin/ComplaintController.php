<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('user')->latest()->get()
            ->map(function ($complaint) {
                return [
                    'id' => $complaint->id,
                    'subject' => $complaint->subject,
                    'description' => $complaint->description,
                    'status' => $complaint->status,
                    'user' => $complaint->user,
                    'created_at_formatted' => $complaint->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('Admin/Complaints', [
            'complaints' => $complaints
        ]);
    }

    public function resolve(Complaint $complaint)
    {
        $complaint->update(['status' => 'resolved']);
        return redirect()->back()->with('success', 'Keluhan diselesaikan');
    }
}
