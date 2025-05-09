<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
class ProjectFileController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            Gate::authorize('create',ProjectFile::class);
            $request->validate([
                'name' => 'required',
                'path' => 'required|mimes:pdf,doc,docx,ppt,pptx',
                'type' => 'required|in:proposal,update,final',
                'project_id' => 'nullable|exists:projects,id',
                'uploaded_by' => 'nullable|exists:users,id'
            ]);
            $project = Project::find($id);
            if ($project == null) {
                return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
            }
            if ($request->has('path')) {
                $file = $request->file('path');
                $file_extension =  $file->getClientOriginalExtension();
                $file_name = time() . "." . $file_extension;
                $path = 'files/';
                $file->move($path, $file_name);
                $file_path = $path . $file_name;
            }
            $projectfile = ProjectFile::create([
                'name' => $request->name,
                'path' => url($file_path),
                'type' => $request->type,
                'project_id' => $project->id,
                'uploaded_by' => Auth::user()->id,
            ]);
            return response()->json(['status' => 'success', 'message' => 'true get data', 'data' => $projectfile], 200);
        } catch (ValidationException $validationException) {
            return response()->json(['status' => 'error', 'message' => $validationException->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try {
            Gate::authorize('destroy',ProjectFile::class);
            $projectfile = ProjectFile::find($id);
            if ($projectfile == null) {
                return response()->json(['status' => 'error', 'message' => 'Project file not found'], 404);
            }
            $projectfile->delete();
            return response()->json(['status' => 'success', 'message' => 'true delete data'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
