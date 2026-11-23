<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aset;
use App\Models\Feedback;

class Pelaporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
        'aset_id',
        'user_id',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}