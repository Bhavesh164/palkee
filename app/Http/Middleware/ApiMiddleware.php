<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\DB;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ($role == 'rider') {
            if (isset($request->rider_id) && isset($request->token)) {
                // if ($request->token == '123456') {
                //     return $next($request);
                // }
                $rider_id =  $request->rider_id;
                $token =  $request->token;

                $rider_validate = DB::table('rider')->where([
                    ['id', '=', $rider_id],
                    ['token', '=', $token],
                ])->exists();
                if (!$rider_validate) {
                    return response()->json(array('status' => '2', 'message' => 'Invalid Token'), 200);
                }
            } else {

                return response()->json(array('status' => '2', 'message' => 'Invalid Token'), 200);
            }
        } elseif ($role == 'driver') {
            if (isset($request->driver_id) && isset($request->token)) {
                // if ($request->token == '123456') {
                //     return $next($request);
                // }
                $driver_id =  $request->driver_id;
                $token =  $request->token;

                $driver_validate = DB::table('driver')->where([
                    ['id', '=', $driver_id],
                    ['token', '=', $token],
                ])->exists();

                if (!$driver_validate) {
                    return response()->json(array('status' => '2', 'message' => 'Invalid Token'), 200);
                }
            } else {

                return response()->json(array('status' => '2', 'message' => 'Invalid Token'), 200);
            }
        }


        return $next($request);
    }
}
