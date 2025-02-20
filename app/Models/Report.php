<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public function getConnectionName()
    {
        return session('db_connection', 'mysql');
    }
} 