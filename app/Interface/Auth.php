<?php

namespace App\Interface;

use App\Models\User;


interface AuthenticationRepositoryInterface
{
    /**
     * Membuat user baru di database.
     * @param array $data Data user baru.
     * @return User
     */
    public function create(array $data): User;

    /**
     * Mencari user berdasarkan email.
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}
