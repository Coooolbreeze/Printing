<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/10
 * Time: 17:11
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\AccountIsExistException;
use App\Exceptions\UserNotFoundException;
use App\Mail\SendVerificationCode;
use App\Models\UserAuth;
use App\Services\VerificationCode;
use Illuminate\Http\Request;

class EmailController extends ApiController
{
    public function sendEmail(Request $request)
    {
        $email = $request->email;
        $auth = UserAuth::where('identifier', $email)->first();

        if ($request->is_register && $auth) throw new AccountIsExistException();
        elseif (!$request->is_register && !$auth) throw new UserNotFoundException();

        $verification = VerificationCode::create($request->email);

        \Mail::send(new SendVerificationCode($request->email, $verification['verification_code']));

        return $this->success([
            'verification_token' => $verification['verification_token']
        ]);
    }
}