<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/18
 * Time: 16:04
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends ApiController
{
    public function store(Request $request)
    {
        $files = [];
        $paths = [];
        foreach ($request->file() as $file) {
            $src = $file->store('files', 'public');
            array_push($files, [
                'name' => $file->getClientOriginalName(),
                'src' => $src
            ]);
            array_push($paths, $src);
        }
        File::saveAll($files);

        return $this->success(
            FileResource::collection(
                File::whereIn('src', $paths)->get()
            )
        );
    }
}