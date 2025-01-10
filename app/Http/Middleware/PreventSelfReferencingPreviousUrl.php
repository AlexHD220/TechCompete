<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Session;

class PreventSelfReferencingPreviousUrl
{
    public function handle($request, Closure $next)
    {
        /*// URL y método actual
        $currentUrl = $request->fullUrl();
        $currentMethod = $request->method();

        // Recuperar URL y método previos desde la sesión
        $previousData = session()->get('_previous_data', ['url' => $currentUrl, 'method' => $currentMethod]);
        $previousUrl = $previousData['url'];
        $previousMethod = $previousData['method'];

        $customPreviousData = session()->get('_back_previous_data', ['url' => null, 'method' => null]);
        $backPrevious = $customPreviousData['url'];
        $customPreviousMethod = $customPreviousData['method'];

        // Si la URL previa es diferente a la actual, la guardamos
        if ($currentUrl !== $previousUrl && $previousMethod === 'GET') {                        
            session()->put('_previous_data', ['url' => $previousUrl, 'method' => $previousMethod]);

            session()->put('_back_previous_data', ['url' => $previousUrl, 'method' => $previousMethod]);
        }else{
            session()->put('_previous_data', ['url' => $backPrevious, 'method' => $customPreviousMethod]);
        }*/


        /*// URL actual
        $currentUrl = $request->fullUrl();    

        // URL previa registrada
        $previousUrl = Session::get('_previous.url');

        $backPrevious = Session::get('_back_previous.url');

        $customPreviousPreviousUrl = Session::get('_back_previous_previous.url');

        // Si la URL previa es diferente a la actual, la guardamos
        if ($currentUrl !== $previousUrl) {                        
            Session::put('_previous.url', $previousUrl);            

            Session::put('_back_previous.url', $previousUrl);    
            
            Session::put('_back_previous_previous.url', $backPrevious);    
        }elseif($previousUrl === $backPrevious){
            Session::put('_previous.url', $customPreviousPreviousUrl);            

            Session::put('_back_previous.url', $customPreviousPreviousUrl);                          
        }else{
            Session::put('_previous.url', $backPrevious);        
        }*/


        // URL actual
        $currentUrl = $request->fullUrl();    

        // URL previa registrada
        $previousUrl = Session::get('_previous.url');

        $backPrevious = Session::get('_back_previous.url');        

        // Si la URL previa es diferente a la actual, la guardamos
        if ($currentUrl !== $previousUrl) {                        
            Session::put('_previous.url', $previousUrl);            

            Session::put('_back_previous.url', $previousUrl);                            
        }else{
            Session::put('_previous.url', $backPrevious);        
        }

        return $next($request);
    }
}
