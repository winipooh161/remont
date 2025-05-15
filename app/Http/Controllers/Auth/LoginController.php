<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Clean phone number before attempt login
     *
     * @param Request $request
     */
    protected function attemptLogin(Request $request)
    {
        // Очистим номер телефона от форматирования
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        
        return $this->guard()->attempt(
            ['phone' => $phone, 'password' => $request->password], $request->filled('remember')
        );
    }
    
    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectTo()
    {
        if (auth()->user()->isAdmin()) {
            return '/admin';
        } elseif (auth()->user()->isPartner()) {
            return '/partner';
        }
        
        return '/home';
    }
}
