<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $account_name
 * @property integer $charId
 * @property string $char_name
 * @property boolean $level
 * @property integer $maxHp
 * @property integer $curHp
 * @property integer $maxCp
 * @property integer $curCp
 * @property integer $maxMp
 * @property integer $curMp
 * @property boolean $face
 * @property boolean $hairStyle
 * @property boolean $hairColor
 * @property boolean $sex
 * @property integer $heading
 * @property integer $x
 * @property integer $y
 * @property integer $z
 * @property integer $exp
 * @property integer $expBeforeDeath
 * @property integer $sp
 * @property integer $karma
 * @property integer $fame
 * @property integer $pvpkills
 * @property integer $pkkills
 * @property integer $clanid
 * @property boolean $race
 * @property boolean $classid
 * @property boolean $base_class
 * @property integer $transform_id
 * @property integer $deletetime
 * @property boolean $cancraft
 * @property string $title
 * @property integer $title_color
 * @property integer $accesslevel
 * @property boolean $online
 * @property integer $onlinetime
 * @property boolean $char_slot
 * @property integer $newbie
 * @property integer $lastAccess
 * @property integer $clan_privs
 * @property boolean $wantspeace
 * @property boolean $isin7sdungeon
 * @property boolean $power_grade
 * @property boolean $nobless
 * @property integer $subpledge
 * @property boolean $lvl_joined_academy
 * @property integer $apprentice
 * @property integer $sponsor
 * @property integer $clan_join_expiry_time
 * @property integer $clan_create_expiry_time
 * @property integer $death_penalty_level
 * @property integer $bookmarkslot
 * @property integer $vitality_points
 * @property integer $hunting_bonus
 * @property integer $nevit_blessing_points
 * @property integer $nevit_blessing_time
 * @property string $createDate
 * @property string $language
 * @property CustomBufferServiceUlist[] $customBufferServiceUlists
 */
class Characters extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'charId';

    protected $connection = 'game';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['account_name', 'char_name', 'level', 'maxHp', 'curHp', 'maxCp', 'curCp', 'maxMp', 'curMp', 'face', 'hairStyle', 'hairColor', 'sex', 'heading', 'x', 'y', 'z', 'exp', 'expBeforeDeath', 'sp', 'karma', 'fame', 'pvpkills', 'pkkills', 'clanid', 'race', 'classid', 'base_class', 'transform_id', 'deletetime', 'cancraft', 'title', 'title_color', 'accesslevel', 'online', 'onlinetime', 'char_slot', 'newbie', 'lastAccess', 'clan_privs', 'wantspeace', 'isin7sdungeon', 'power_grade', 'nobless', 'subpledge', 'lvl_joined_academy', 'apprentice', 'sponsor', 'clan_join_expiry_time', 'clan_create_expiry_time', 'death_penalty_level', 'bookmarkslot', 'vitality_points', 'hunting_bonus', 'nevit_blessing_points', 'nevit_blessing_time', 'createDate', 'language'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customBufferServiceUlists()
    {
        return $this->hasMany('App\Models\CustomBufferServiceUlist', 'ulist_char_id', 'charId');
    }
}
