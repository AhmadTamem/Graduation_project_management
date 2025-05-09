<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Validation\ValidationException;

class EvaluationController extends Controller
{
    public function store(Request $request, $id) {
        try{
            $request->validate([
                'project_id' => 'nullable|exists:projects,id',
                'evaluator_id'=>'nullable|exists:users,id',
                'technical_score' => 'required|numeric',
                'presentation_score' => 'required|numeric',
                'documentation_score'=>'required|numeric',
                'feedback' => 'required|string'
            ]);
            $project = Project::find($id);
            if ($project == null) {
                return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
            }
            $evaluation = Evaluation::create([
                'project_id' => $project->id,
                'evaluator_id' => FacadesAuth::user()->id,
                'technical_score' => $request->technical_score,
                'presentation_score' => $request->presentation_score,
                'documentation_score' => $request->documentation_score,
                'feedback' => $request->feedback,
            ]);
            return response()->json(['status' => 'success', 'message' => 'true get data', 'data' => $evaluation], 200);

        }
        catch(ValidationException $validationException) {
            return response()->json(['status' => 'error', 'message' => $validationException->errors()], 400);

        }
        catch(Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

    }
}
