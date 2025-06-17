<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
//use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    // Social login functionality is temporarily disabled
    
    public function redirectToFacebook()
    {
        return redirect()->route('login')->withErrors('Social login is temporarily disabled');
    }

    public function handleFacebookCallback()
    {
        return redirect()->route('login')->withErrors('Social login is temporarily disabled');
    }

    public function redirectToGithub()
    {
        return redirect()->route('login')->withErrors('Social login is temporarily disabled');
    }

    public function handleGithubCallback()
    {
        return redirect()->route('login')->withErrors('Social login is temporarily disabled');
    }

    public function redirectToGoogle()
    {
        return redirect()->route('login')->withErrors('Social login is temporarily disabled');
    }

    public function handleGoogleCallback()
    {
        return redirect()->route('login')->withErrors('Social login is temporarily disabled');
    }
}
