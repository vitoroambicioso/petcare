<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Status extends Model
{
    use HasApiTokens, HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idAdmin',
        'idDenuncia',
        'status',
        'admin',
        'org',
        'message',
    ];

}
