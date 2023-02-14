<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'password',
        'url_id',
    ];

    protected $casts = [
        'banned_words' => 'array'
    ];

    // protected $primaryKey = 'url_id';

    public function words() {
        return $this->hasMany(Word::class);
    }
}
