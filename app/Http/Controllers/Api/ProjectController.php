<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;

use function PHPSTORM_META\type;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            Gate::authorize('index',Project::class);
            $Project = Project::with('student')->get();
            return response()->json(['status' => 'success', 'message' => 'true get data', $Project], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        try {
            Gate::authorize('show',Project::class);
            $Project = Project::with('student', 'supervisor', 'files', 'comments', 'evaluations', 'updates')->find($id);
            return response()->json(['status' => 'success', 'message' => 'true get data', $Project], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            Gate::authorize('create',Project::class);
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'status' => 'in:submitted,accepted,under_review,rejected,completed',
                'student_id' => 'nullable|exists:users,id',
                'supervisor_id' => 'nullable|exists:users,id,type,supervisor',
                'committee_head_id' => 'nullable|exists:users,id,type,committee_head',
            ]);
            $Project = Project::create(
                [
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status ?? 'submitted',
                    'student_id' => Auth::user()->id,
                    'supervisor_id' => $request->supervisor_id,
                    'committee_head_id' => $request->committee_head_id
                ]
            );
            return response()->json(['status' => 'success', 'message' => 'true get data', $Project], 200);
        } catch (ValidationException $validationException) {
            return response()->json(['status' => 'error', 'message' => $validationException->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            Gate::authorize('update', Project::class);
            
            // Validate the request
            $request->validate([
                'status' => 'in:submitted,accepted,under_review,rejected,completed',
                'supervisor_id' => 'nullable|exists:users,id,type,supervisor',
                'committee_head_id' => 'nullable|exists:users,id,type,committee_head',
            ]);
            
            // Find the project
            $Project = Project::find($id);
            if ($Project == null) {
                return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
            }
            
            // Get supervisor ID if name is provided
            if ($request->has('name')) {
                $user = User::select('id')
                    ->where('name', $request->name) // Correctly fetching name from the request
                    ->where('type', 'supervisor') // Correctly checking type
                    ->first();
                    
                if ($user) {
                    $Project->supervisor_id = $user->id; // Assign the found user ID
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Supervisor not found'], 404);
                }
            }
    
            // Update project fields
            $Project->status = $request->status ?? $Project->status;
            $Project->committee_head_id = Auth::user()->id; // Assuming this is correct
            $Project->save();
    
            return response()->json(['status' => 'success', 'message' => 'Successfully updated project data', 'project' => $Project], 200);
        } catch (ValidationException $validationException) {
            return response()->json(['status' => 'error', 'message' => $validationException->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
