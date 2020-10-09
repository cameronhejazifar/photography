<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class IsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param null $modelName
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $modelName = null)
    {
        if ($modelName) {
            $model = $request->route($modelName);
            if ($model->user_id !== Auth::user()->id) {
                abort(404, "{$modelName} does not belong to user.");
            }
        } else {
            foreach ($request->route()->parameters() as $modelName => $model) {
                if ($model->user_id !== Auth::user()->id) {
                    abort(404, "{$modelName} does not belong to user.");
                }
            }
        }
        return $next($request);
    }
}
