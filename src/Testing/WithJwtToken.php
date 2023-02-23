<?php

namespace Alterindonesia\Procurex\Testing;

use Firebase\JWT\JWT;
use Illuminate\Encryption\Encrypter;

trait WithJwtToken
{
    /**
     * @param  int  $id
     * @param  string  $name
     * @param  string  $username
     * @param  string  $email
     * @param  array<int, array{ id: int, name: string}>  $roles
     * @param  array{ code: string, name: string }  $company
     * @return $this
     */
    public function withJwtToken(
        int $id = 1,
        string $name = 'Superadmin',
        string $username = 'superadmin',
        string $email = 'superadmin@app.com',
        array $roles = [['id' => 1, 'name' => 'superadmin']],
        array $company = ['code' => 'A000', 'name' => 'PT Pupuk Indonesia'],
    ): static
    {
        $this->jwtUser = (object) [
            'id' => $id,
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'roles' => array_map(static fn (array $role) => (object) $role, $roles),
            'company' => (object) $company,
            'employee' => null,
        ];

        $encryption = new Encrypter( config('procurex.gateway.secret'), 'aes-256-cbc');
        $payload = [
            'iss' => 'http://procurex.text',
            'value' => $encryption->encrypt($this->jwtUser),
            'expired_at' => now()->addDay()->timestamp,
        ];
        $token = JWT::encode($payload, config('procurex.gateway.secret'), 'HS256');

        return $this->withToken($token);
    }
}