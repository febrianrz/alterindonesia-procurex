<?php
namespace Alterindonesia\Procurex\Facades;

use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Auth extends \Illuminate\Support\Facades\Auth {

    private static ?object $user = null;
    private static $instance = null;
    private static $currentAuthorization = null;
    public ?int $id = null;
    public ?string $username = null;
    public ?string $email = null;
    public ?string $name = null;
    public ?string $company_code = null;
    public ?string $company_name = null;
    public ?object $employee = null;
    public ?array $roles = [];
    public ?object $company = null;

    public function __construct($user) {
        $this->id = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->name = $user->name;
        $this->company_code = $user->company?->code;
        $this->company_name = $user->company?->name;
        $this->roles = $user->roles;
        $this->company = $user->company;
        $this->employee = $user->employee;
    }

    public static function check(): bool
    {
        return (bool)self::user();
    }

    public static function user(): Auth|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        try {
            if(request()->header('Authorization') && (self::$currentAuthorization  !== request()->header('Authorization'))) {
                $authorization = str_replace('Bearer ','',request()->header('Authorization'));
                $jwt = JWT::decode($authorization, new Key(config('procurex.gateway.secret'), 'HS256'));
                $encryption = new \Illuminate\Encryption\Encrypter( config('procurex.gateway.secret'), 'aes-256-cbc');
                $now = Carbon::now()->timestamp;
                if($now >= $jwt->expired_at) throw new \Exception("Expired");
                $payload = $encryption->decrypt($jwt->value);
                self::$instance = new self($payload);
                self::$currentAuthorization = request()->header('Authorization');
            }
            return self::$instance;
        } catch (\Exception $e) {
//            throw new \Exception("Invalid Account");
            return abort(401,"Unauthenticated");
        }
    }

    public function hasCompany($company) : bool
    {
        return $this->company_code === $company;
    }

    public function hasRoles($roleName) : bool
    {
        foreach ($this->roles as $role) {
            if($role->name === $roleName) return true;
        }
        return false;
    }

    public function hasPermission($permissionName) : bool
    {
        if(self::isSuperadmin()) return true;
        $exists = Role::join('role_has_permissions','role_has_permissions.role_id','roles.id')
            ->join('permissions','permissions.id','role_has_permissions.permission_id')
            ->where('permissions.name',$permissionName)
            ->whereIn('roles.name',self::pluckRoleName())
            ->first();
        if($exists) return true;
        return false;
    }

    public function isSuperadmin() : bool
    {
        return (in_array("superadmin",self::pluckRoleName()));
    }

    public function pluckRoleName() : array
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->name;
        }
        return $roles;
    }

    public function pluckRoleId() : array
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->id;
        }
        return $roles;
    }

    public function id(): int {
        return $this->id;
    }

    public function can($permissionName): bool {

        $roleNames = $this->pluckRoleName();
        $check = DB::table('role_permission_procurex')
            ->whereIn('role_code',$roleNames)
            ->where('permission_name',$permissionName)
            ->first();
        return boolval($check);
    }

    public function isEmployee(): bool {
        $user = User::findOrFail($this->id);
        return (boolean)$user->employee;
    }


}
