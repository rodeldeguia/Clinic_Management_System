<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedback = Feedback::with(['patient', 'doctor', 'appointment'])
            ->latest('submitted_at')
            ->paginate(20);
            
        return view('admin.feedback.index', compact('feedback'));
    }

    public function show($id)
    {
        $feedback = Feedback::with(['patient', 'doctor', 'appointment'])->findOrFail($id);
        return view('admin.feedback.show', compact('feedback'));
    }

    public function respond(Request $request, $id)
    {
        // Store response in feedback table or separate table
        // You might need to add a 'admin_response' column to feedback table
        
        return back()->with('success', 'Response sent to patient.');
    }

    public function sendAnnouncement(Request $request)
    {
        // Implement SMS/Email announcement using Twilio
        // $twilio = new Twilio(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        // $twilio->messages->create($number, ['from' => env('TWILIO_FROM'), 'body' => $request->message]);
        
        return back()->with('success', 'Announcement sent successfully.');
    }

    public function sendNotification(Request $request)
    {
        // Similar to announcement but targeted
        return back()->with('success', 'Notification sent.');
    }
}