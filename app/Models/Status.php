<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $fillable = [
        "label",
        "key"
    ];

    public function tasks(): HasMany {
        return $this->hasMany(Task::class, "status_id");
    }
}
