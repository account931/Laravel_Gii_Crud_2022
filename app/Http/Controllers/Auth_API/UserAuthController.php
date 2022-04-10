<?php
//Login and register via REST API + Passport (passport token is issued on successful login)
namespace App\Http\Controllers\Auth_API;

use App\Http\Controllers\Controller;
//use App\Models\User;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    
    /**
     * Registration via Passport 
     * @param Request $request
     * @return Json
     *
     */
    public function register(Request $request)
    {
        
        
        $validator =  Validator::make($request->all(), [
            'name'                  => 'required|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => ['required', 'confirmed'], //same as => 'required|confirmed',
            'password_confirmation' => 'required' // //must be named 'password_confirmation', i.e 'firstINPUT_confirmation' to be automatically validate in back-end ()

        ]);
        
        if ($validator->fails()) {
            //return response()->json($request->validator->messages(), 400);
            return response()->json(['error' => true, 'data' => 'Looked OK, but validation crashes', 'validateErrors'=>  $validator->messages(), 'error_message' => 'Validation failed']);
        } 

        if ($validator->passes()) {
            return response()->json(['error' => true, 'data' => 'Too Good, and validation OK']);
        
        
            //return response([ 'user' => $request->name]);
        
            $data['password'] = bcrypt($request->password);

            $user = User::create($data);

            $token = $user->createToken('API Token')->accessToken;

            return response([ 'user' => $user, 'token' => $token]);
        }
    }



    /**
     * Login via Passport (passport token is issued on successful login)
     * @param Request $request
     * @return Json
     *
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response(['error_message' => 'Incorrect Details. Please try again']);
        }
        
        //IMPLEMENT DELTING OLD USER"S PASSPORT TOKEN HERE............
        
        $token = auth()->user()->createToken('API Token')->accessToken; //create token via Passport
        
        //save token to session to use in requests
        //Session::put('PassportToken', $token); //Session get => session()->get('PassportToken');	
        
        return response(['user' => auth()->user(), 'token' => $token ]);

    }
}