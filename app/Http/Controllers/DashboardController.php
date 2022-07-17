<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\transition;
use Auth;

class DashboardController extends Controller
{
    
   public function __construct()
   {
      $this->middleware(['auth:sanctum', 'verified']);
   }

   public function Dashboard()
   {
       $transitions = transition::where('user_id',Auth::user()->id)->orderby('created_at','desc')->paginate(10);
       return view('dashboard',compact('transitions'));
   }

   public function mlm_tree()
   {
      $transitions = transition::where('user_id',Auth::user()->id)->paginate(10);
      return view('dashboard',compact('transitions'));
   }

   public static function get_parent($id)
   {
          return User::find($id);
      
   }

   public static function get_children($id)
   {
         return User::where('parent_user_id',$id)->get();
   }
}
