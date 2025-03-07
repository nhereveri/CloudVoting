<?php

namespace App\Services;

class RunValidatorService
{
    /**
     * Validate a Chilean RUN
     */
    public function validate(string $run): bool
    {
        // Clean and format RUN
        $run = $this->formatRun($run);
        
        if (!$run) {
            return false;
        }

        list($number, $verifier) = explode('-', $run);
        
        // Validate number range
        if (!$this->validateNumberRange($number)) {
            return false;
        }

        // Calculate and validate verifier digit
        return $this->calculateVerifier($number) === $verifier;
    }

    /**
     * Format RUN string (clean and standardize)
     */
    private function formatRun(string $run): ?string
    {
        // Remove whitespace and dots
        $run = preg_replace('/[\s.]/', '', $run);
        
        // Validate basic format (XXXXXXXX-X)
        if (!preg_match('/^[0-9]{7,8}-[0-9kK]$/', $run)) {
            return null;
        }

        // Convert 'k' to 'K'
        return str_replace('k', 'K', $run);
    }

    /**
     * Validate if number is within valid range
     */
    private function validateNumberRange(string $number): bool
    {
        $num = intval($number);
        return $num >= 1000000 && $num <= 99999999;
    }

    /**
     * Calculate verifier digit using modulo 11 algorithm
     */
    private function calculateVerifier(string $number): string
    {
        $sum = 0;
        $multiplier = 2;
        
        // Reverse the number and multiply each digit
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $sum += intval($number[$i]) * $multiplier;
            $multiplier = $multiplier === 7 ? 2 : $multiplier + 1;
        }

        $remainder = $sum % 11;
        $verifier = 11 - $remainder;

        // Convert result to verifier digit
        return match ($verifier) {
            11 => '0',
            10 => 'K',
            default => (string)$verifier
        };
    }

    /**
     * Generate a valid RUN with its verifier digit
     */
    public function generateValidRun(?int $number = null): string
    {
        if ($number === null) {
            $number = random_int(1000000, 99999999);
        }

        if (!$this->validateNumberRange((string)$number)) {
            throw new \InvalidArgumentException('Number must be between 1000000 and 99999999');
        }

        $verifier = $this->calculateVerifier((string)$number);
        return $number . '-' . $verifier;
    }
}