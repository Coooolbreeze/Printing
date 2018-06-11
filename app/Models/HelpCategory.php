<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 16:13
 */

namespace App\Models;


/**
 * App\Models\HelpCategory
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Help[] $helps
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HelpCategory extends Model
{
    public function helps()
    {
        return $this->hasMany('App\Models\Help');
    }
}