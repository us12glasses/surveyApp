<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use CrudTrait;
    protected $fillable = ['question_id', 'answer'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    
    use HasFactory;
}
