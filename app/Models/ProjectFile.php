<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectFile extends Model
{
    protected $fillable=[
        'name',
        'path',
        'type',
        'project_id',
        'uploaded_by'
    ];





    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function uploadedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
