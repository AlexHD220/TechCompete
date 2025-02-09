<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;

use Illuminate\Support\Facades\DB;

//use Closure;
//use Illuminate\Contracts\Validation\ValidationRule;

//class ValidateUniqueInTables implements ValidationRule
class ValidateUniqueInTables implements Rule
{
    protected $tables;

    /**
     * Crear una nueva instancia de la regla.
     *
     * @param array $tables Nombres de las tablas en donde se validará.
     */
    public function __construct(array $tables)
    {
        $this->tables = $tables;
    }

    /**
     * Determinar si la regla pasa.
     *
     * @param  string  $attribute  El nombre del atributo.
     * @param  mixed   $value      El valor del atributo.
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->tables as $table) {
            if (DB::table($table)->where($attribute, $value)->exists()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtener el mensaje de error de validación.
     *
     * @return string
     */
    public function message()
    {
        //$tablas = implode(', ', $this->tables);
        // return "El :attribute ya está registrado en alguna de las siguientes tablas: $tablas.";
        return "Este :attribute ya ha sido registrado.";
    }
}
