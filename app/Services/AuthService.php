<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Interface\AuthenticationRepositoryInterface;
use App\Repositories\AuthRepositoryImpl;
use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthenticationRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $request): User
    {
        try {
            $user = $this->authRepository->create($request);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }

        return $user;
    }

    public function login(array $request): ?User
    {
        try {
            $user = $this->authRepository->findByEmail($request['email']);
            if ($user && Hash::check($request['password'], $user->password)) {
                return $user;
            }
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
        return null;
    }

    // public function getUserByEmail(string $email): User
    // {
    //     return User::where('email', $email)->first();
    // }

    // public function resetPassword(User $user, object $request): User
    // {
    //     $otp = Otp::where([
    //         'user_id' => $user->id,
    //         'code' => $request->otp,
    //         'active' => 1,
    //         'type' => 'password-reset'
    //     ])->first();

    //     if (!$otp) {
    //         abort(422, __('app.invalid_otp'));
    //     }

    //     // Update
    //     $user->password = $request->password;
    //     $user->updated_at = Carbon::now();
    //     $user->update();

    //     $otp->active = 0;
    //     $otp->updated_at = Carbon::now();
    //     $otp->update();

    //     return $user;
    // }

    // public function otp(User $user, string $type = 'verification'): Otp
    // {
    //     // check for spam and throttle
    //     $tries = 3;
    //     $time = Carbon::now()->subMinutes(30);

    //     $count = Otp::where([
    //         'user_id' => $user->id,
    //         'type' => $type,
    //         'active' => 1
    //     ])->where('created_at', '>=', $time)->count();

    //     if ($count >= $tries) {
    //         abort(422, __('app.otp_too_many_request'));
    //     }

    //     $code = random_int(100000, 999999);
    //     $otp = Otp::create([
    //         'user_id' => $user->id,
    //         'type' => $type,
    //         'code' => $code,
    //         'active' => 1
    //     ]);

    //     // Send Mail
    //     Mail::to($user)->send(new OtpMail($user, $otp));

    //     return $otp;
    // }

    // public function verify(User $user, object $request): User
    // {
    //     $otp = Otp::where([
    //         'user_id' => $user->id,
    //         'code' => $request->otp,
    //         'active' => 1,
    //         'type' => 'verification'
    //     ])->first();

    //     if (!$otp) {
    //         abort(422, __('app.invalid_otp'));
    //     }

    //     // Update
    //     $user->email_verified_at = Carbon::now();
    //     $user->update();

    //     $otp->active = 0;
    //     $otp->updated_at = Carbon::now();
    //     $otp->update();

    //     return $user;
    // }

    // 'first_name',
    //     'last_name',
    //     'email',
    //     'password',
    //     'image',
    //     'gender'


    // 'firstNname': firstName,
    //   'lastName': lastName,
    //   'password': password,
    //   'email': email,
    //   'gender': gender,
    //   'age': age,
}
