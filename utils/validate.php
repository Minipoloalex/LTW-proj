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


// TODO: put these functions in the right file
function check_valid_data(string $name, string $username, string $email, string $password, string $confirm_password) {
    // TODO: do not allow special characters in name/username. only letters, spaces and numbers: slide 24/63 web security
    if (!is_valid_string($name) || !is_valid_string($username) || !is_valid_string($email) || !is_valid_string($password) || !is_valid_string($confirm_password)) {
        return array(false, "Username, password, name and email are required");
    }
    if ($password != $confirm_password) {
        return array(false, "Passwords do not match");
    }
    if (strlen($password) < 6) {
        return array(false, "Password must have at least 6 characters");
    }
    return array(true, "");
}


function check_valid_password(string $pass){
    if (!is_valid_string($pass)) {
        return array(false, "Password is required");
    }

    if (strlen($pass) < 6) {
        return array(false, "Password must have at least 6 characters");
    }

    return array(true, "");
}


function is_valid_type(string $userType) {
    return $userType === 'Client' || $userType === 'Agent' || $userType === 'Admin';
}
function is_valid_status(string $status) {
    $status = strtolower($status);
    return $status === 'open' || $status === 'closed' || $status === 'in progress';
}
?>
