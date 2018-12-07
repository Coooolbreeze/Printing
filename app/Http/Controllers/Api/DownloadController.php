<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/12/7
 * Time: 23:41
 */

namespace App\Http\Controllers\Api;


use App\Models\File;

class DownloadController extends ApiController
{
    public function show(File $file)
    {
        $path = public_path('store/' . $file->src);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-type:text/html;charset=utf-8");
        header('Content-Disposition: attachment; filename=' . $file->name);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        readfile($path);
    }
}