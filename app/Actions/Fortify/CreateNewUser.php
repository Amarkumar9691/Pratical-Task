<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\transition;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $level=0;
        $parent_user_id = 0;
        
        do
        {
             $referral_code = strtoupper(bin2hex(random_bytes(4)));


        }while(count(User::where('referral_code',$referral_code)->get()) > 0);
       

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'referral_code'=>['nullable','alpha_num','max:8','min:8','exists:users,referral_code'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ],['referral_code.exists'=>'Invalid Referral Code'])->validate();
        

        if($input['referral_code'] !='')
        {
             $parent = User::where('referral_code',$input['referral_code'])->first();
             $parent_user_id = $parent->id;
             $level = (int)$parent->user_level + 1;       
        }
        else
        {
            $level = 0;
            $parent_user_id = 0;
        }

        
        $user =  User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'referral_code'=>$referral_code,
            'wallet'=>100,
            'user_level'=>$level,
            'parent_user_id'=>$parent_user_id,
        ]);

        if($user)
        {
            

            transition::create([
                'user_id'=>$user->id,
                'wallet_amount'=>100,
                'transiton_type'=>'credit',
                'earning_amount'=>100,
                'transiton_description'=>'100 Rs Bouns Credit into your account for signup'
             ]);
            
            if($parent_user_id != 0)
            {   
                $parent = $parent_user_id;
                $tnx_level = 1;

                do
                {   
                   if($tnx_level == 1)
                   {
                        $amount  = round(((100*30)/100),2);
                       
                   }
                   else if($tnx_level == 2)
                   {
                        $amount  =  round(((100*20)/100),2);
                   }
                   else
                   {
                       $amount  =  round(((100*10)/100),2);
                   }

                    $parent_info = User::find($parent);
                   
                    User::where('id',$parent)->update(['wallet'=>(double)$amount+(double)$parent_info->wallet]);

                    

                    transition::create([
                        'user_id'=>$parent_info->id,
                        'wallet_amount'=>(double)$amount+(double)$parent_info->wallet,
                        'earning_amount'=>$amount,
                        'transiton_type'=>'credit',
                        'transiton_description'=>$amount .' Rs Bouns Credit into your account from '.$user->email.' registration'
                    ]);

                   
                   $parent = $parent_info->parent_user_id;

                   $tnx_level++;
                }
                while($parent != 0);

            }

            return $user;
        }
    }
}
