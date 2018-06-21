<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 15:39
 */

namespace App\Http\Controllers\Api;


use App\Api\Helpers\Api\ApiResponse;
use App\Http\Controllers\Controller;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host=L5_SWAGGER_CONST_HOST,
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="易特印商城API",
 *     ),
 * )
 */
class ApiController extends Controller
{
    use ApiResponse;

    
}