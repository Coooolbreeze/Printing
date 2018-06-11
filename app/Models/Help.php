<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 16:13
 */

namespace App\Models;


/**
 * App\Models\Help
 *
 * @property int $id
 * @property int $help_category_id
 * @property string $title
 * @property string $body
 * @property int $status 0未发布 1已发布
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\HelpCategory $helpCategory
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Help whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Help whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Help whereHelpCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Help whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Help whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Help whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Help whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Help extends Model
{
    public function helpCategory()
    {
        return $this->belongsTo('App\Models\HelpCategory');
    }
}