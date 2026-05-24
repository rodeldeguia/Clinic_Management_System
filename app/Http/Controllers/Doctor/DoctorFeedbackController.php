<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class DoctorFeedbackController extends Controller
{
    public function index()
    {
        $doctorId = Auth::user()->user_id;
        
        // Get all feedback for this doctor
        $feedback = Feedback::where('doctor_id', $doctorId)
            ->with('patient')
            ->latest('submitted_at')
            ->paginate(20);
        
        // Calculate average rating
        $average_rating = Feedback::where('doctor_id', $doctorId)->avg('rating') ?? 0;
        
        // Calculate rating distribution
        $rating_counts = [
            1 => Feedback::where('doctor_id', $doctorId)->where('rating', 1)->count(),
            2 => Feedback::where('doctor_id', $doctorId)->where('rating', 2)->count(),
            3 => Feedback::where('doctor_id', $doctorId)->where('rating', 3)->count(),
            4 => Feedback::where('doctor_id', $doctorId)->where('rating', 4)->count(),
            5 => Feedback::where('doctor_id', $doctorId)->where('rating', 5)->count(),
        ];
        
        $total_feedback = Feedback::where('doctor_id', $doctorId)->count();
        
        return view('doctor.feedback.index', compact('feedback', 'average_rating', 'rating_counts', 'total_feedback'));
    }

    public function show($id)
    {
        $feedback = Feedback::where('doctor_id', Auth::user()->user_id)
            ->with('patient')
            ->findOrFail($id);
            
        return view('doctor.feedback.show', compact('feedback'));
    }
}