<?php
namespace App\Services\Login;
use App\Http\Resources\LoginResource;
use App\Libraries\Auth;
use App\Models\Employee;
use App\Models\User;
use App\Services\Login\LoginInterface;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class LoginService implements LoginInterface {

    public function login($username, $password) : array
    {
        $this->loginToSSOPI($username,$password);
        $user = User::where('username',$username)->where('status','Active')->first();
        if(!$user) throw new \Exception(__("User Not Found"));
        if(!Hash::check($password,$user->password)) throw new \Exception(__("Username or Password is Invalid"));

        //check ke gateway

        $expiredAt = Carbon::now()->addSeconds(config('procurex.gateway.duration'))->timestamp;
        $jwt = $this->generateJWT($user,$expiredAt);
        return [
            'token_type'    => 'Bearer',
            'access_token'  => $jwt,
            'refresh_token' => '',
            'expired_at'    => $expiredAt
        ];
    }

    private function loginToSSOPI($username, $password)
    {
        $payload = [
            'uid'  => $username,
            'password'  => $password
        ];
        $result = Http::asForm()->withBasicAuth(config('procurex.sso_pi.username'), config('procurex.sso_pi.password'))
            ->post(config('procurex.sso_pi.url')."/api/login/users",$payload);
        $json = $result->json();
        if($result->status() === 200) {
            $resultEmployee = Http::withBasicAuth(config('procurex.sso_pi.username'), config('procurex.sso_pi.password'))
                ->get(config('procurex.sso_pi.url').'/api/login/masterkary?badge='.$json['emp_no']);
            $employee = $resultEmployee->json();
            // Update or Create Employee
            $employeeModel = Employee::updateOrCreate([
                'emp_no'    => $employee['emp_no']
            ],$employee);

            // Update or Create User
            $user = User::updateOrCreate([
                'username'  => $employee['emp_no']
            ],[
                'email'     => $employeeModel->email,
                'name'      => $employeeModel->nama,
                'email_verified_at'=> date('Y-m-d H:i:s'),
                'company_code'=> $employeeModel->company,
                'status'    => $employeeModel->status ? 'Active' : 'Inactive',
                'password'  => bcrypt($password)
            ]);

            // Assign Role If Not Exists
            if($user->roles()->count() === 0) {
                $user->assignRole(config('procurex.sso_pi.default_role_name')); // default role
            }
        }
    }

    /**
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    private function generateJWT(User $user,$expiredAt) : string
    {

        $payload = json_decode((new LoginResource($user))->toJson());
        $kong = $this->getKongData($user);
        if(!isset($kong['key'])) throw new \Exception("Unauthenticated");
        $kong_iss = $kong['key'];
        $encryption = new \Illuminate\Encryption\Encrypter( config('procurex.gateway.secret'), 'aes-256-cbc');
        $newPayload = [
            'iss'       => $kong_iss,
            'value'     => $encryption->encrypt($payload),
            'expired_at'=> $expiredAt,
        ];

        $jwt = JWT::encode((array)$newPayload, $kong['secret'], $kong['algorithm']);
        return $jwt;
    }

    /**
     * @param User $user
     * @return array|mixed
     * @throws \Exception
     */
    private function getKongData(User $user)
    {
        $this->getConsumer($user);
        return $this->getKeyJWT($user);
    }

    private function getConsumer(User $user){
        if(!$user->consumer_id){
            $post = Http::post(config('procurex.gateway.host')."/consumers",[
                'username'  => $user->username,
                'custom_id' => 'procurex-'.$user->id.'-'.$user->username,
            ]);
            $user->consumer_id = isset($post->json()['id']) ? $post->json()['id'] : null;
            $user->save();
        }
        return $user->consumer_id;
    }

    private function getKeyJWT($user) {
        if(!$user->consumer_id) throw new \Exception("Failed Get Response From Gateway");
        $host = config('procurex.gateway.host');;
        $post = Http::post($host."/consumers/{$user->consumer_id}/jwt",[
            'secret'                => config('procurex.gateway.secret'),
        ]);
        // jika consumer di gateway dihapus, maka generate baru
        if($post->status() == 404) {
            $user->consumer_id = null;
            $consumer_id = $this->getConsumer($user);
            $user->consumer_id = $consumer_id;
            $user->save();

            $host = config('procurex.gateway.host');
            $post = Http::post($host."/consumers/{$user->consumer_id}/jwt",[
                'secret'                => config('procurex.gateway.secret'),
            ]);
        }
        return $post->json();
    }

    /**
     * @throws \Exception
     */
    public function logout(\App\Models\User $user)
    {
        $listJwt = $this->getListJWT($user->consumer_id);
        foreach ($listJwt['data'] as $jwt) {
            $this->logoutCredential($user->consumer_id,$jwt['id']);
        }
    }

    /**
     * @throws \Exception
     */
    private function getListJWT(?string $consumer_id) {
        $host = config('procurex.gateway.host');
        $result = Http::get("{$host}/consumers/{$consumer_id}/jwt");
        if($result->status() !== 200) throw new \Exception("Failed Get Credential List");
        return $result->json();
    }

    /**
     * @throws \Exception
     */
    private function logoutCredential($consumer_id, $jwt){
        $host = config('procurex.gateway.host');
        $result = Http::get("{$host}/consumers/{$consumer_id}/jwt/{$jwt}");
        if($result->status() !== 200) throw new \Exception("Failed Delete Credential List");
    }

    public function updateProfile(\App\Models\User $user, Request $request)
    {
        try {
            // TODO: Implement updateProfile() method.
            $user->name = $request->name;
            $user->save();
        } catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function updatePassword(\App\Models\User $user, Request $request)
    {
        // TODO: Implement updatePassword() method.
        try {
            $user = User::findOrFail(Auth::user()->id);
            $user->password = bcrypt($request->new_password);
            $user->save();
        } catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function refreshJwt(\App\Models\User $user)
    {
        try {
            $this->logout($user);
            $expiredAt = Carbon::now()->addSeconds(config('procurex.gateway.duration'))->timestamp;
            $jwt = $this->generateJWT($user,$expiredAt);
            return [
                'token_type'    => 'Bearer',
                'access_token'  => $jwt,
                'refresh_token' => '',
                'expired_at'    => $expiredAt
            ];
        } catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function roles() {
        return [];
    }
}
