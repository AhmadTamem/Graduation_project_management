<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
   protected $fillable= [
    'content',
    'project_id',
    'user_id',
    'parent_id'
];


        // علاقة many-to-one مع المشروع
        public function project():BelongsTo
        {
            return $this->belongsTo(Project::class);
        }
    
        // علاقة many-to-one مع المستخدم الذي كتب التعليق
        public function user():BelongsTo
        {
            return $this->belongsTo(User::class);
        }
    
        // علاقة one-to-many مع الردود (تعليقات فرعية)
        public function replies():HasMany
        {
            return $this->hasMany(Comment::class, 'parent_id');
        }
    
        // علاقة many-to-one مع التعليق الأصلي
        public function parent():BelongsTo
        {
            return $this->belongsTo(Comment::class, 'parent_id');
        }
}
