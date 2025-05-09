<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            $project = Project::find($id);
            if ($project == null) {
                return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
            }


            $request->validate([
                'content' => 'required',
                'project_id' => 'nullable|exists:projects,id',
                'user_id' => 'nullable|exists:users,id',
                'parent_id' => 'nullable|exists:comments,id'
            ]);
            $comment = Comment::create([
                'content' => $request->content,
                'project_id' => $project->id,
                'user_id' => Auth::user()->id,
                'parent_id' => $request->parent_id
            ]);
            return response()->json(['status' => 'success', 'message' => 'true get data','data'=> $comment], 200);
        } catch (ValidationException $validationException) {
            return response()->json(['status' => 'error', 'message' => $validationException->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
