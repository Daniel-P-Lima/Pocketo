<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stash extends Model
{
    /** @use HasFactory<\Database\Factories\StashFactory> */
    use HasFactory;

    protected $fillable = ['name', 'amount', 'goal_amount','purpose'];
}
