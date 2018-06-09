<?php

namespace App\Models;


/**
 * App\Models\Token
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Token whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Token whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Token whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Token whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Token whereUserId($value)
 */
class Token extends Model
{

}
