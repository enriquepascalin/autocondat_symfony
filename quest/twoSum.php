<?php

/**
 * Encuentra dos números en un array que sumen un valor objetivo
 * 
 * @param array $nums Array de enteros (ej: [2, 7, 11, 15])
 * @param int $target Suma objetivo (ej: 9)
 * @return array Indices de los dos números que suman el objetivo (ej: [0, 1])
 * 
 * Requisitos:
 * - Tiempo O(n) 
 * - Espacio O(n)
 * - Existe exactamente una solución
 */
function twoSum(array $nums, int $target): array {
    // Implementa tu solución aquí
}

// Casos de prueba
$test_cases = [
    [[2, 7, 11, 15], 9, [0, 1]],
    [[3, 2, 4], 6, [1, 2]],
    [[3, 3], 6, [0, 1]],
    [[-1, 0, 10, 5], 4, [0, 3]],
];

foreach ($test_cases as $case) {
    [$nums, $target, $expected] = $case;
    $result = twoSum($nums, $target);
    sort($result); // Ordenar índices para comparación
    echo "Input: nums = [" . implode(',', $nums) . "], target = $target\n";
    echo "Resultado: [" . implode(',', $result) . "]\n";
    echo "Esperado: [" . implode(',', $expected) . "]\n";
    echo "Estado: " . ($result === $expected ? "✅ Éxito" : "❌ Fallo") . "\n\n";
}