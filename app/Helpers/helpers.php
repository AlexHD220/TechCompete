<?php

if (! function_exists('quitar_acentos')) {
    /**
     * Quita acentos y diacríticos de una cadena.
     *
     * @param  string  $texto
     * @return string
     */
    function quitar_acentos(string $texto): string
    {
        // Descompone los caracteres con diacríticos (NFD)
        $decompuesto = \Normalizer::normalize($texto, \Normalizer::FORM_D);
        // Elimina los diacríticos (categoría Mn)
        return preg_replace('/\p{Mn}/u', '', $decompuesto);
    }
}
