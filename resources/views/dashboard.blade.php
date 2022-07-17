<x-app-layout>
    <x-slot name="header">

         <div class="flex justify-between h-6">
            <div class="flex">
               <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 {{ __('Dashboard') }}
                </h2>
            </div>

            <div class="flex">
               <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 {{ __('Account Balance') }} {{(number_format(Auth::user()->wallet,2,'.',','))}} {{ __('INR')}}
                </h2>
            </div>

           
        </div>
      
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-jet-welcome :transitions="$transitions"/>
            </div>
        </div>
    </div>
</x-app-layout>
