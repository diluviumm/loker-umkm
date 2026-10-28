<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentPrune extends Model
{
    protected $fillable = ['type', 'path', 'reason', 'bytes'];
}
