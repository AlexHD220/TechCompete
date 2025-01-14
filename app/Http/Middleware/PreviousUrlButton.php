<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Session;

class PreviousUrlButton
{
    public function handle($request, Closure $next)
    {

        // URL actual
        $currentUrl = $request->fullUrl(); // 

        
        // URL previa registrada
        $previousUrl = Session::get('_previous.url'); // 

        
        // URL Anterior
        $customPreviousUrl = Session::get('_custom_previous.url'); //
        
        // Actual -- pasa a ser --> Anterior
        $currentCustomPrevious = Session::get('_current_custom_previous.url'); // 

        // URL Respaldo
        $respaldoPrevious = Session::get('_respaldo_previous.url'); // 


        // Si la URL anterior contiene '/create' o '/edit'
        if (str_contains($currentCustomPrevious, '/create') || str_contains($currentCustomPrevious, '/edit')) {
            // Si la URL previa es diferente a la actual, la guardamos
            if ($currentUrl !== $currentCustomPrevious){ // Si se recarga la pagina     
                Session::put('_current_custom_previous.url', $currentUrl);  //   
                
                Session::put('_custom_previous.url', $respaldoPrevious); //
                    
                Session::put('_respaldo_previous.url', $respaldoPrevious);  //                         
            }
        } else{
            // Si la URL previa es diferente a la actual, la guardamos
            if ($currentUrl !== $currentCustomPrevious){ // Si se recarga la pagina        

                Session::put('_current_custom_previous.url', $currentUrl);  //                         

                if($currentUrl === $previousUrl){ // Listo
                    Session::put('_custom_previous.url', $respaldoPrevious); //
                    
                    Session::put('_respaldo_previous.url', $respaldoPrevious);  //          
                } else if($respaldoPrevious === $previousUrl){ // Pendiente de revisar
                    Session::put('_custom_previous.url', $respaldoPrevious); //
                    
                    Session::put('_respaldo_previous.url', $respaldoPrevious);  //          
                } else { // Listo                                                  
                    Session::put('_custom_previous.url', $currentCustomPrevious); // 

                    Session::put('_respaldo_previous.url', $customPreviousUrl);  // 
                }  
            }   
        }        

        return $next($request);
    }
}
