<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    //table users
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //a user belongs to a branch except for the admin
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id', 'id');
    }

    //has many customers
    // public function customers()
    // {
    //     return $this->hasMany('App\Models\Customer', 'user_id', 'id');
    // }

    //has many loans
    public function loans()
    {
        return $this->hasMany('App\Models\Loan', 'user_id', 'id');
    }

    //branch manager has many loans
    // public function branch_manager_loans()
    // {
    //     return $this->hasMany('App\Models\Loan', 'branch_manager_id', 'id');
    // }

    //creator of loan has many loans
    public function creator_loans()
    {
        return $this->hasMany('App\Models\Loan', 'created_by', 'id');
    }

    //approver of loan has many loans
    public function approver_loans()
    {
        return $this->hasMany('App\Models\Loan', 'approved_by', 'id');
    }

    //user admin disburses loans
    public function disburse_loans()
    {
        return $this->hasMany('App\Models\Disburse', 'disbursed_by', 'id');
    }

    //relationship wit Spatie Role, a user has one role
    // public function roles()
    // {
    //     return $this->belongsTo(Spatie\Permission\Models\Role::class, 'role_id', 'id');
    // }

    // public function getIsAdminAttribute()
    // {
    //     return $this->roles->contains(1);
    // }
    // public function getIsBranchManagerAttribute()
    // {
    //     return $this->roles->contains(2);
    // }
    // public function getIsRoleManagerAttribute()
    // {
    //     return $this->roles->contains(3);
    // }
    // public function getIsUserAttribute()
    // {
    //     return $this->roles->contains(4);
    // }

    //a user has one role
    // public function role(){
    //     //use sp
    //     return $this->hasOne('App\Models\Role', 'id', 'role_id');
    // }

    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();
        return $permission_groups;
    }

    public static function getPermissionGroup()
    {
        return $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
    }
    public static function getpermissionsByGroupName($group_name)
    {
        $permissions = FacadesDB::table('permissions')->select('name', 'id')->where('group_name', $group_name)->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }
}
