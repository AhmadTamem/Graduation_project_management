<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    protected $fillable=[
        'project_id',
        'evaluator_id',
        'technical_score',
        'presentation_score',
        'documentation_score',
        'feedback'
    ];




        // علاقة many-to-one مع المشروع
        public function project():BelongsTo
        {
            return $this->belongsTo(Project::class);
        }
    
        // علاقة many-to-one مع المقيم (مشرف أو رئيس لجنة)
        public function evaluator():BelongsTo
        {
            return $this->belongsTo(User::class, 'evaluator_id');
        }
}
