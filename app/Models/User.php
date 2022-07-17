<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'wallet',
        'referral_code',
        'parent_user_id',
        'user_level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public  function children($id)
    {
        return User::where('parent_user_id',$id)->get();
    }

    public  function fetch_all_referral($id)
    {
        static $refferal = array();

        if($this->children($id)->isNotEmpty())
        {
            foreach($this->children($id) as $child)
            {  
                $refferal[] = array('name'=>$child->name,'code'=>$child->referral_code,'level'=>$child->user_level);
                if($this->children($child->id)->isNotEmpty())
                {
                   Self::fetch_all_referral($child->id);
                }
            }
        }

       
        return $refferal;

    }


}

