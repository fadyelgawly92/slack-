<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

use App\User;

class AuthController extends Controller
{
    public function redirectToProvider($provider)
    {
       return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user , $provider);
        Auth::login($authUser , true);
        return redirect()->action('HomeController@index');
    }

    public function findOrCreateUser($user , $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();

        if($authUser){
            return $authUser;
        }

        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id,
        ]);
    }
}
