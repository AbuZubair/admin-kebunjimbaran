<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\SignInRequest;
use Illuminate\Support\Facades\Log;
use App\Library\Services\Shared;
use App\Library\Model\Model;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Sign Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    private $model;
    private $sharedService;

    /**
     * Create a new controller instance.
     *
     */

    public function __construct(Shared $sharedService,Model $model)
    {
        $this->middleware('guest');
        $this->model = $model;
        $this->sharedService = $sharedService;
    }

    public function index(){
        return view('auth.login');
    }

    public function onSignedIn(SignInRequest $request){
        $credentials = array(
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        );
       
        if (Auth::attempt($credentials)) {
            $msg = Auth::user()->getusername().' has been login';
            Log::info($msg);            
            $this->sharedService->logs($msg);
            return redirect(route('dashboard'));
        } else {
            Log::info($request->input('username').' try to login but failed with message '.trans('validation.password'));
            return redirect(route('login'))->withInput($request->except('password'))->withErrors([
                'password' => 'Password salah'
            ]);
        }
       
    }

}
