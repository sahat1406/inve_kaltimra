<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: satu feedback milik satu pelaporan
    public function pelaporan()
    {
        return $this->belongsTo(Pelaporan::class);
    }

    // Jika ingin mendukung reply (feedback membalas feedback lain)
    public function feedbackReply()
    {
        return $this->belongsTo(Feedback::class, 'parent_id');
    }

    // Optional: relasi untuk melihat semua reply dari feedback ini
    public function replies()
    {
        return $this->hasMany(Feedback::class, 'parent_id');
    }
}