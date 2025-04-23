<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['question_text', 'type', 'options', 'labels'];
    use CrudTrait;
    
    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    use HasFactory;
}
