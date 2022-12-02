<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\SendsAPIErrors;
use Closure;
use Illuminate\Http\Request;

class RequiresVersionHeader
{
    use SendsAPIErrors;

    public function handle(Request $request, Closure $next)
    {
        $accepts = $request->header('Accept');

        if (!$accepts) {
            return $this->errorResponse(406, 'accepts_header_missing', 'You must send a valid Accepts-header to use the API');
        }

        if ($accepts !== 'application/vnd.bolknoms.v1+json') {
            return $this->errorResponse(406, 'accepts_header_unsupported', 'The given API version is unsupported');
        }

        return $next($request);
    }
}
