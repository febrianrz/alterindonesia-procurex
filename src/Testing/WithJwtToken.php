<?php

namespace Alterindonesia\Procurex\Testing;

use Firebase\JWT\JWT;
use Illuminate\Encryption\Encrypter;

trait WithJwtToken
{
    /**
     * @see self::getDefaultJwtUserData()
     */
    public function withFakeJwtToken(array $data = []): static
    {
        $this->jwtUser = (object) array_merge(
            $this->getDefaultJwtUserData(),
            $data,
        );

        $encryption = new Encrypter( config('procurex.gateway.secret'), 'aes-256-cbc');
        $payload = [
            'iss' => 'http://procurex.text',
            'value' => $encryption->encrypt($this->jwtUser),
            'expired_at' => now()->addDay()->timestamp,
        ];
        $token = JWT::encode($payload, config('procurex.gateway.secret'), 'HS256');

        return $this->withToken($token);
    }

    private function getDefaultJwtUserData(): array
    {
        return [
            'id' => 1,
            'username' => 'superadmin',
            'email' => 'superadmin@app.com',
            'name' => 'Superadmin',
            'employee' => null,
            'company_code' => 'A000',
            'company_name' => 'PT Pupuk Indonesia',
            'roles' => [
                (object) [
                    'id' => 1,
                    'name' => 'superadmin',
                ],
            ],
            'company' => (object) [
                'code' => 'A000',
                'name' => 'PT Pupuk Indonesia',
            ],
        ];
    }
}