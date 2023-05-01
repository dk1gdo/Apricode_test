<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'developer_id'
    ];
    public function developer()
    {
        return $this->belongsTo(Developer::class );
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    use HasFactory;
}
