<?php

enum CaseSensitivity
{
    case CASE_SENSITIVE;
    case CASE_INSENSITIVE;
}

enum IgnoreWhitespace
{
    case IGNORE_WHITESPACE;
    case CONSIDER_WHITESPACE;
}


function is_palindrome(string $originalString, CaseSensitivity $sensitivity, IgnoreWhitespace $withSpaces): bool
{
    if ($sensitivity === CaseSensitivity::CASE_INSENSITIVE) {
        $originalString = strtolower($originalString);
    }
    if ($withSpaces === IgnoreWhitespace::IGNORE_WHITESPACE) {
        $originalString = str_replace(' ', '', $originalString);
    }

    $revertedString = strrev($originalString);
    
    return $originalString === $revertedString;
}

/*
    O(n) tiempo (Complejidad temporal):

        Significa que el tiempo de ejecución crece linealmente con el tamaño de entrada

        Para un string de longitud n, deberías realizar máximo c * n operaciones (donde c es constante)

        Tu solución cumple esto ✅

    O(1) espacio (Complejidad espacial):

        Significa que usas memoria constante independiente del tamaño de entrada

        No debes crear estructuras de datos adicionales proporcionales al input (como nuevos strings o arrays)

        Tu solución actual no cumple esto porque:

            strtolower() crea un nuevo string

            str_replace() crea otro nuevo string

            strrev() crea un tercer string

        Cada operación usa O(n) espacio extra

🚀 Versión optimizada (O(n) tiempo y O(1) espacio):
*/
function is_palindrome_optimized(string $s): bool {
    $left = 0;
    $right = strlen($s) - 1;
    
    while ($left < $right) {
        // Saltar caracteres no alfanuméricos por izquierda
        while ($left < $right && !ctype_alnum($s[$left])) $left++;
        
        // Saltar caracteres no alfanuméricos por derecha
        while ($left < $right && !ctype_alnum($s[$right])) $right--;
        
        // Comparar caracteres (case-insensitive)
        if (strtolower($s[$left]) !== strtolower($s[$right])) {
            return false;
        }
        
        $left++;
        $right--;
    }
    
    return true;
}

$string = "Anita lava la tina";
$result = is_palindrome($string, CaseSensitivity::CASE_INSENSITIVE, IgnoreWhitespace::IGNORE_WHITESPACE) ? "✅" : "❌";
$result2 = is_palindrome_optimized($string) ? "✅" : "❌";
echo "Result: $string is palindrome? $result\n";
echo "Result2: $string is palindrome? $result2\n";


