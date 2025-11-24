<?php


namespace App\Repositories;

use App\Models\User;
use App\Interface\AuthenticationRepositoryInterface;

class AuthRepositoryImpl implements AuthenticationRepositoryInterface
{
    /**
     * Membuat user baru di database.
     * @param array $data Data user baru.
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Mencari user berdasarkan email.
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
