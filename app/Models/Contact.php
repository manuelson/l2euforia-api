<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $email
 * @property string $reason
 * @property string $content
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Contact extends Model
{

    protected $connection = 'web';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'contact';

    /**
     * @var array
     */
    protected $fillable = ['email', 'reason', 'content', 'status', 'created_at', 'updated_at'];
}
