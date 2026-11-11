<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\Feedback;

class Pelaporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
        'barang_id',
        'user_id',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}