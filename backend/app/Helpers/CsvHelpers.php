<?php

if (!function_exists('validate_csv_headers')) {
    function validate_csv_headers($file, array $requiredHeaders)
    {
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if (!$header) {
            return ['error' => 'CSV file is empty or invalid.'];
        }

        $missing = array_diff($requiredHeaders, $header);
        if (!empty($missing)) {
            return [
                'error' => 'CSV headers are incorrect.',
                'missing_columns' => $missing,
            ];
        }

        return ['handle' => $handle, 'header' => $header];
    }
}
