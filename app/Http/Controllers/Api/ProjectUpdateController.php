<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUpdate;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class ProjectUpdateController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            Gate::authorize('create',ProjectUpdate::class);
            $request->validate([
                'project_id' => 'nullable|exists:projects,id',
                'content' => 'required',
            ]);
            $project = Project::find($id);
            if ($project == null) {
                return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
            }
            $projectupdate = ProjectUpdate::create([
                'project_id' => $project->id,
                'content' => $request->content,
                'user_id' => Auth::user()->id,
            ]);
            return response()->json(['status' => 'success', 'message' => 'true get data', 'data' => $projectupdate], 200);
        } catch (ValidationException $validationException) {
            return response()->json(['status' => 'error', 'message' => $validationException->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
