<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOpportunity;
use App\Models\JobApplication;
use App\Models\Notification;

class JobInboxController extends Controller
{
    /**
     * Fetch job opportunities.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJobOpportunities()
    {
        // Fetch all job opportunities from the database
        $jobOpportunities = JobOpportunity::with('company')->get();

        return response()->json([
            'success' => true,
            'data' => $jobOpportunities,
        ]);
    }

    /**
     * Fetch job applications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJobApplications()
    {
        // Fetch all job applications for the authenticated user
        $user = auth()->user();
        $jobApplications = JobApplication::where('user_id', $user->id)
            ->with('jobOpportunity')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jobApplications,
        ]);
    }

    /**
     * Fetch notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications()
    {
        // Fetch all notifications for the authenticated user
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Apply for a job opportunity.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyForJob(Request $request)
    {
        // Validate the request
        $request->validate([
            'job_opportunity_id' => 'required|exists:job_opportunities,id',
        ]);

        // Create a new job application
        $user = auth()->user();
        $jobApplication = JobApplication::create([
            'user_id' => $user->id,
            'job_opportunity_id' => $request->job_opportunity_id,
            'status' => 'applied',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job application submitted successfully.',
            'data' => $jobApplication,
        ]);
    }

    /**
     * Archive a job opportunity.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function archiveJobOpportunity(Request $request)
    {
        // Validate the request
        $request->validate([
            'job_opportunity_id' => 'required|exists:job_opportunities,id',
        ]);

        // Archive the job opportunity for the authenticated user
        $user = auth()->user();
        $jobOpportunity = JobOpportunity::find($request->job_opportunity_id);

        // Add logic to archive the job opportunity (e.g., update a field or create a record in a pivot table)
        $user->archivedJobOpportunities()->attach($jobOpportunity->id);

        return response()->json([
            'success' => true,
            'message' => 'Job opportunity archived successfully.',
        ]);
    }

    /**
     * Mark a notification as read.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotificationAsRead(Request $request)
    {
        // Validate the request
        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);

        // Mark the notification as read
        $notification = Notification::find($request->notification_id);
        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }
}