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

        $errorForm =Session::get('_error_form.url'); //  


        // Si hubo un error en el envio del formulario BETA
        if ((str_contains($currentUrl, '/create') || str_contains($currentUrl, '/edit')) && ($customPreviousUrl == $respaldoPrevious)) { // BETA
            Session::put('_current_custom_previous.url', $currentUrl);  //
            
            Session::put('_custom_previous.url', $errorForm); //

            Session::put('_respaldo_previous.url', $respaldoPrevious);  //
        }
        // Si la URL anterior contiene '/create' o '/edit'
        else if (str_contains($currentCustomPrevious, '/create') || str_contains($currentCustomPrevious, '/edit')) {                        

            // Si la URL previa es diferente a la actual, la guardamos
            if ($currentUrl !== $currentCustomPrevious){ // Si se recarga la pagina     
                Session::put('_current_custom_previous.url', $currentUrl);  //
                
                Session::put('_custom_previous.url', $respaldoPrevious); //
                    
                Session::put('_respaldo_previous.url', $respaldoPrevious);  //
                

                Session::put('_error_form.url', $customPreviousUrl);  //BETA
            }            
        } else{
            // Si la URL previa es diferente a la actual, la guardamos
            if ($currentUrl !== $currentCustomPrevious){ // Si se recarga la pagina        

                Session::put('_current_custom_previous.url', $currentUrl);  //                         

                if($currentUrl === $previousUrl){ // Si el anterior fue un metodo sin vista
                    Session::put('_custom_previous.url', $respaldoPrevious); //
                    
                    Session::put('_respaldo_previous.url', $respaldoPrevious);  //          
                } else if($respaldoPrevious === $previousUrl){ // Metodos sin vista ni create ([!] Pendiente de revisar)
                    Session::put('_custom_previous.url', $respaldoPrevious); //
                    
                    Session::put('_respaldo_previous.url', $respaldoPrevious);  //          
                } else { // Anterior normal                                                  
                    Session::put('_custom_previous.url', $currentCustomPrevious); // 

                    Session::put('_respaldo_previous.url', $customPreviousUrl);  // 
                }  
            }   
        }        

        // Restablecer valores (1)
        if(0){
            Session::put('_current_custom_previous.url', null);  //
                    
            Session::put('_custom_previous.url', null); //
                        
            Session::put('_respaldo_previous.url', null);  //
                    
            Session::put('_error_form.url', null);  //BETA
        }

        return $next($request);
    }
}
