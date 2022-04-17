<?php
//Version for REST API
//Middleware for Rbac check via Spatie Laravel Permission. Checks if a User has certain permisssions.
//see detailes in ReadMe_Laravel_Com_Commands for error fix => There is no permission named `edit_project` for guard `api`
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User; 
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;

class SpatieRbacMiddleRestApiVersion
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @return mixed
	 *
     */
    public function handle($request, Closure $next)
    { 
        //firstly run request to get user data
        $response = $next($request);
        $authedUser = Auth::user(); //getting the logged user Object, version for Passport
		
        if(!$authedUser->hasAllPermissions(['create_articles', /* 'edit articles', 'delete articles' */  ])){  //'create_articles' is a special api, not web permission
		//if(!$authedUser->hasRole('AdminX')){
            //throw new \App\Exceptions\myException('You have No REST API rbac rights to Admin Panel');
            return response()->json(['error' => true, 'data' => 'You have No REST API Spatie Rbac rights to Admin Panel']);
        } /*else {
            return response()->json(['error' => true, 'data' => 'You have REST API Spatie Rbac rights to Admin Panel. Drop me in middleware']);
        }*/
        
        //return $next($request);
        return $response;
    }
}