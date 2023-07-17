<?php

namespace Tests\Feature;

use App\Models\AuthClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ping()
    {
        $response = $this->get('/api/ping');

        $response->assertStatus(200);
    }

    public function test_login_invalid_client_id_failed()
    {
        $response = $this->post('/api/login',[
            'grant_type'    => 'password',
            'client_id'     => '1',
            'client_secret' => Str::random(60),
            'username'      => 'superadmin',
            'password'      => 'superadmin',
            'scope'         => ''
        ]);

        $response->assertStatus(403);
    }

    public function test_login_invalid_username_password_failed()
    {
        $auth = AuthClient::find(1);
        $response = $this->post('/api/login',[
            'grant_type'    => 'password',
            'client_id'     => $auth->id,
            'client_secret' => $auth->secret,
            'username'      => 'superadmin',
            'password'      => 'superadmin123',
            'scope'         => ''
        ]);
        //bad request
        $response->assertStatus(400);
    }

    public function test_login_success()
    {
        $auth = AuthClient::find(1);
        $response = $this->post('/api/login',[
            'grant_type'    => 'password',
            'client_id'     => $auth->id,
            'client_secret' => $auth->secret,
            'username'      => 'superadmin',
            'password'      => 'superadmin',
            'scope'         => ''
        ]);
        //bad request
        $response->assertStatus(200);
    }

    public function test_login_get_profile_success()
    {
        $auth = AuthClient::find(1);
        $response = $this->post('/api/login',[
            'grant_type'    => 'password',
            'client_id'     => $auth->id,
            'client_secret' => $auth->secret,
            'username'      => 'superadmin',
            'password'      => 'superadmin',
            'scope'         => ''
        ]);
        //bad request
        $responseJson = $response->json();
        $authorization = $responseJson['data']['access_token'];

        $response = $this->get('/api/profile',[
            'Accept'        => 'application/json',
            'Authorization'  => 'Bearer '.$authorization
        ]);
        $response->assertStatus(200);
    }

    public function test_logout_success()
    {
        $auth = AuthClient::find(1);
        $response = $this->post('/api/login',[
            'grant_type'    => 'password',
            'client_id'     => $auth->id,
            'client_secret' => $auth->secret,
            'username'      => 'superadmin',
            'password'      => 'superadmin',
            'scope'         => ''
        ]);
        //bad request
        $responseJson = $response->json();
        $authorization = $responseJson['data']['access_token'];

        $response = $this->post('/api/logout',[],[
            'Accept'        => 'application/json',
            'Authorization'  => 'Bearer '.$authorization
        ]);
        $response->assertStatus(200);
    }
}
