<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tasks extends Model
{
    use HasFactory;

    protected $fillable = ['tasks_name'];

    protected $primaryKey = 'tasks_id';

    protected $table = 'tasks';

}
