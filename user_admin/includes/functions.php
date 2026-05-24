<?php
/**
 * Helper functions for validation and formatting
 */

/**
 * Calculate age from birth date
 * @param string $birth_date Format: Y-m-d
 * @return int Age in years
 */
function calculateAge($birth_date) {
    $birth = new DateTime($birth_date);
    $today = new DateTime();
    if ($birth > $today) {
        return false;
    }
    $age = $today->diff($birth)->y;
    return $age;
}

/**
 * Validate birth date is not in future
 * @param string $birth_date Format: Y-m-d
 * @return bool True if date is valid and not in future
 */
function isNotFutureDate($birth_date) {
    $birth = DateTime::createFromFormat('Y-m-d', $birth_date);
    if (!$birth) {
        return false;
    }
    
    $today = new DateTime();
    // Убираем время для корректного сравнения
    $today->setTime(0, 0, 0);
    $birth->setTime(0, 0, 0);
    
    return $birth <= $today;
}

/**
 * Get validation error message for age
 * @param string $birth_date Format: Y-m-d
 * @param int $min_age Minimum age
 * @param int $max_age Maximum age
 * @return string Error message or empty string if valid
 */
function getAgeErrorMessage($birth_date, $min_age = 18, $max_age = 100) {
    if (!strtotime($birth_date)) {
        return "Invalid birth date format";
    }

    // Проверка, что дата не в будущем
    if (!isNotFutureDate($birth_date)) {
        return "Birth date cannot be in the future. Please enter a valid date.";
    }
    
    $age = calculateAge($birth_date);
    
    if ($age < $min_age) {
        return "User must be at least {$min_age} years old. Current age: {$age} years";
    }
    
    if ($age > $max_age) {
        return "User cannot be older than {$max_age} years. Current age: {$age} years";
    }
    
    return "";
}
?>