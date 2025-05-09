<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'student_id',
        'department',
        'specialization'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function studentProjects():HasMany
    {
        return $this->hasMany(Project::class, 'student_id');
    }

    public function supervisedProjects():HasMany
    {
        return $this->hasMany(Project::class, 'supervisor_id');
    }

    public function committeeProjects():HasMany
    {
        return $this->hasMany(Project::class, 'committee_head_id');
    }

    public function comments():HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function evaluations():HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->type, 'admin');
    }
}
