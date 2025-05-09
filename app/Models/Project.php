<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable=[
        'title',
        'description',
        'status',
        'student_id',
        'supervisor_id',
        'committee_head_id'
    ];



    public function student():BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function supervisor():BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function committeeHead():BelongsTo
    {
        return $this->belongsTo(User::class, 'committee_head_id');
    }

    public function files():HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function updates():HasMany
    {
        return $this->hasMany(ProjectUpdate::class);
    }

    public function comments():HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function evaluations():HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
