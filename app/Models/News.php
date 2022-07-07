<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $title
 * @property string $username
 * @property string $new
 * @property string $created_at
 * @property string $updated_at
 */
class News extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['title', 'username', 'new', 'created_at', 'updated_at'];

    protected $connection = 'web';
}
