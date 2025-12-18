<?php

if (! function_exists('format_count')) {
    /**
     * Format large numbers into a compact human-readable form.
     */
    function format_count(
        int|float|string $number,
        int $decimals = 1,
        bool $trimZeros = true
    ): string {
        if (! is_numeric($number)) {
            return '0';
        }

        $num = (float) $number;
        $sign = $num < 0 ? '-' : '';
        $value = abs($num);

        if ($value < 1000) {
            return $sign . number_format((int) $value);
        }

        $suffixes = [
            12 => 'T',
            9  => 'B',
            6  => 'M',
            3  => 'k',
        ];

        foreach ($suffixes as $exp => $suffix) {
            $threshold = 10 ** $exp;

            if ($value >= $threshold) {
                $scaled = $value / $threshold;
                $formatted = number_format($scaled, $decimals, '.', '');

                if ($trimZeros) {
                    $formatted = rtrim(rtrim($formatted, '0'), '.');
                }

                return $sign . $formatted . $suffix;
            }
        }

        return $sign . number_format((int) $value);
    }
}
