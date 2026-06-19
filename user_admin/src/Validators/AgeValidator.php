<?php

declare(strict_types=1);

namespace App\Validators;

use DateTime;

class AgeValidator
{
    public static function calculateAge(string $birthDate): ?int {
        $birth = new DateTime($birthDate);
        $today = new DateTime();
        
        if ($birth > $today) {
            return null;
        }
        
        return $today->diff($birth)->y;
    }

    public static function isNotFutureDate(string $birthDate): bool {
        $birth = DateTime::createFromFormat('Y-m-d', $birthDate);
        
        if (!$birth) {
            return false;
        }
        
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        $birth->setTime(0, 0, 0);
    
        return $birth <= $today;
    }

    public static function getAgeErrorMessage(
        string $birthDate,
        int $minAge = 18,
        int $maxAge = 100
    ): string {
        if (!strtotime($birthDate)) {
            return "Invalid birth date format";
        }

        if (!self::isNotFutureDate($birthDate)) {
            return "Birth date cannot be in the future. Please enter a valid date.";
        }
        
        $age = self::calculateAge($birthDate);
        
        if ($age < $minAge) {
            return "User must be at least {$minAge} years old. Current age: {$age} years";
        }
        
        if ($age > $maxAge) {
            return "User cannot be older than {$maxAge} years. Current age: {$age} years";
        }
        
        return "";
    }
}
