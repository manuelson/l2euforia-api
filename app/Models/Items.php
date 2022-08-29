<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Items extends Model
{

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'object_id';

    protected $connection = 'game';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';

    /**
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'item_id',
        'count',
        'enchant_level',
        'loc',
        'loc_data',
        'time_of_use',
        'custom_type1',
        'custom_type2',
        'mana_left',
        'time'
    ];
}
