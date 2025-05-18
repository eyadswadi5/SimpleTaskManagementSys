<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "title",
        "description",
        "status_id",
        "created_by",
        "assigned_to",
        "finished_at",
        "priority"
    ];

    public function status(): BelongsTo {
        return $this->belongsTo(Status::class, "status_id");
    }

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, "created_by");
    }

    public function assignee(): BelongsTo {
        return $this->belongsTo(User::class, "assigned_to");
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class, "task_id");
    }

}
