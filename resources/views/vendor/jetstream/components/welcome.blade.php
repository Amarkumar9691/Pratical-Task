
<div class="p-2 bg-white border-b border-gray-200">
    
    @if(request()->routeIs('dashboard'))
    <div class="p-2 text-gray-500">
      <div class="mt-0 text-1xl">
        <h4>{{__('My Eearning History')}}</h4>      
      </div>

      @if(isset($transitions) && $transitions && count($transitions) > 0)

      <div class="p-2 text-info-500">
                <table class="table-auto">
                  <thead>
                    <tr>
                      <th class="px-6 py-2 text-left">S.No</th>
                      <th class="px-6 py-2 text-left">{{__('Description')}}</th>
                      <th class="px-6 py-2 text-left">{{__('Date')}}</th>
                      <th class="px-6 py-2 text-left">{{__('Earnings Bouns')}}</th>
                      <th class="px-6 py-2 text-left">{{__('Wallet Balance')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $count = 1 ; @endphp
                    @foreach($transitions as $single)
                    <tr>
                      <td class="px-6 py-4">{{$count++}}</td>
                      <td class="px-6 py-4">{{$single->transiton_description}}</td>
                      <td class="px-6 py-4">{{date_format(date_create($single->created_at),'d,M Y')}}</td>
                      <td class="px-6 py-4"><span class="text-green-500">+</span> {{number_format($single->earning_amount,2,'.',',')}} INR</td>
                      <td class="px-6 py-4"> {{number_format($single->wallet_amount,2,'.',',')}}  INR</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>     
      </div>
      <div class="p-2 text-info-500">
          
                 <div class="mt-4">
                  {{$transitions->links()}}
                </div>

      </div>
      @else

       <div class="p-2 mt-4 text-info-500">
        <h4 class="text-dark-500 text-center">No Records found.</h4>   
       </div>
      @endif
    </div>
    @endif

    @if(request()->routeIs('mlm_tree'))
    
    <div class="p-2 text-gray-500">
       <div class="mt-0 text-1xl p-2">
          <h4  class="mt-2 text-dark-500">{{__('Lists of all users which are top of you')}}</h4>      
       </div>

       @if(Auth::user()->parent_user_id != 0)
             <div class="p-2 text-info-500">
                   @php
                    $count = 1 ;
                    $parent = Auth::user()->parent_user_id;               
                    do
                    {
                   @endphp

                   @if($parent_info=App\Http\Controllers\DashboardController::get_parent($parent))
                   <h4>{{__('Level')}} {{$count++}}</h4>
                   <div class="w-40 ml-20 p-2 rounded overflow-hidden shadow-lg">
                      <img class="max-w-sm mx-4" src="http://127.0.0.1:8000//user.png" alt="Sunset in the mountains" width="100" height="100">
                      <div class="px-1 mx-3 py-2">
                       <div class="font-bold text-sm mb-1">{{$parent_info->name}}</div>
                       <div class="font-bold text-sm mb-2">Ref. {{$parent_info->referral_code}}</div>
                      </div>
                   </div>
            

                  @endif
                  

                  @php
                  }while($parent = App\Http\Controllers\DashboardController::get_parent($parent)->parent_user_id);
                  @endphp

                   </div>

      
       @else 
       <div class="p-2 text-info-500">
         <h4 class="mt-2 text-dark-500 text-center">You are root user. No users are ahead you.</h4> 
       </div>
       @endif

       @if(Auth::user()->referral_code != '')
        <div class="p-2 text-info-500">
        <h4 class="mt-2 text-dark-500">{{__('List of users which are below you')}}</h4>
        
        @if($all = Auth::user()->fetch_all_referral(Auth::user()->id))
          @php 
            array_multisort( array_column($all, "level"), SORT_ASC, $all );
            $level = 0;
          @endphp

          <div class="p-2 mt-1 text-info-500">
          @foreach($all as $userlevel)
           
             @if($level != $userlevel['level'])
                  <div class="p-2 mt-1">
                  <h4>{{__('Level')}}  {{$userlevel['level']}}</h4>
                   
                    @php 
                    $user_level = $userlevel['level'];
                    $_SESSION['user_level'] = $user_level;
                    $level_user = array_filter($all,function($var){
              
                    if($var['level'] == $_SESSION['user_level'])
                    {
                       return true;
                    }
               
                    });
                    $level++;
                    @endphp
                    <div class="flex ml-6">
                    @foreach($level_user as $single)
                     <div class="flex-1 max-w-sm">
                      <div class="w-40 p-2 rounded overflow-hidden shadow-lg">
                      <img class="max-w-sm mx-4" src="http://127.0.0.1:8000//user.png" alt="Sunset in the mountains" width="100" height="100">
                      <div class="px-1 mx-3 py-2">
                       <div class="font-bold text-sm mb-1">{{$single['name']}}</div>
                       <div class="font-bold text-sm mb-2">Ref. {{$single['code']}}</div>
                      </div>
                      </div>
                    
                     </div>

                    @endforeach
                   </div>

                  </div>
             @endif  
            
    

           @endforeach
           </div>

         
        @else

        <h4 class="mt-2 text-dark-500 text-center">No users are below you.</h4> 

        @endif

       </div>
       @endif
    </div>
    @endif
</div>

