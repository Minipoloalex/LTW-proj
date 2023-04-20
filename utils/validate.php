<?php
declare(strict_types = 1);
function is_valid_id(?String $id): bool {
    return isset($id) && is_numeric($id) && intval($id) >= 0;
}

function is_valid_string(?String $string): bool {
    return isset($string) && !empty($string);
}

function is_valid_array_ids(array $array_ids): bool {
    foreach ($array_ids as $id) {
        if (!is_valid_id($id)) {
            return false;
        }
    }
    return true;
}
?>
