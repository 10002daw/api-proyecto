<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('admin', 'owner')->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('admin', 'owner')
            ->withTimestamps()
            ->wherePivot('owner', 1);
    }
}
