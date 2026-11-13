<?php

namespace App\Support;

class Mensajes
{
    /** Mensajes de validación en español. */
    public static function validacion(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_if' => 'El campo :attribute es obligatorio.',
            'unique' => 'El valor de :attribute ya está registrado.',
            'email' => 'El campo :attribute debe ser un correo válido.',
            'max' => 'El campo :attribute no debe superar :max.',
            'min' => 'El campo :attribute debe ser al menos :min.',
            'in' => 'El valor de :attribute no es válido.',
            'exists' => 'El valor de :attribute no existe.',
            'numeric' => 'El campo :attribute debe ser numérico.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'after_or_equal' => 'El campo :attribute debe ser igual o posterior a :date.',
        ];
    }
}
