<?php
declare(strict_types = 1);
$name_regex = '/^[A-Za-zÀ-ÖØ-öø-ÿ ]+$/';
$username_regex = '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9_\-. ]+$/';
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

function is_valid_email(?String $email): bool {
    return isset($email) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
}
function is_valid_name(?String $name): bool {
    return isset($name) && !empty($name) && preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ\- ]+$/', $name);
}
function is_valid_username(?String $username): bool {
    return isset($username) && !empty($username) && preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ0-9_\-. ]+$/', $username);
}

function is_valid_password(?String $password): bool {
    return isset($password) && !empty($password) && preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&]{6,}$/', $password);
}
function check_valid_data(string $name, string $username, string $email, string $password, string $confirm_password) : array {
    if (!is_valid_name($name) || !is_valid_username($username) || !is_valid_email($email) || !is_valid_string($password) || !is_valid_string($confirm_password)) {
        return array(false, "Username, password, name and email are required");
    }
    if ($password != $confirm_password) {
        return array(false, "Passwords do not match");
    }
    if (!is_valid_password($password)) {
        return array(false, "Password must have at least 6 characters, 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character");
    }
    return array(true, "");
}


function check_valid_password(string $pass) : array {
    if (!is_valid_string($pass)) {
        return array(false, "Password is required");
    }

    if (strlen($pass) < 6) {
        return array(false, "Password must have at least 6 characters");
    }

    return array(true, "");
}


function is_valid_type(string $userType) : bool {
    return $userType === 'Client' || $userType === 'Agent' || $userType === 'Admin';
}
function is_valid_status(string $status) : bool {
    $status = strtolower($status);
    return $status === 'open' || $status === 'closed' || $status === 'in progress';
}

function is_valid_array_hashtag_ids(PDO $db, array $hashtagIds): bool {
    if (!is_valid_array_ids($hashtagIds)) return false;    
    foreach ($hashtagIds as $id) {
        if (!Hashtag::isValidId($db, intval($id))) return false;
    }
    return true;
}
function is_valid_department_id(PDO $db, ?string $departmentID): bool{
    return is_valid_id($departmentID) && Department::isValidId($db, intval($departmentID));
}
function is_valid_priority(?string $priority): bool {
    return isset($priority) && !empty($priority) &&
    ($priority == "high" || $priority == "medium" || $priority == "low");
}
function is_valid_ticket_id($db, ?string $ticketID): bool {
    if (!is_valid_id($ticketID)) return false;
    $ticket = Ticket::getById($db, intval($ticketID));
    return $ticket != null;
}
function is_valid_faq_id(PDO $db, ?string $forum_id): bool {
    if (!is_valid_id($forum_id)) return false;
    $faq = Forum::getById($db, intval($forum_id));
    return $faq != null;
}
function is_valid_title(string $title): bool {
    return is_valid_string($title) && strlen($title) <= 50;
}
function is_valid_agent_id(PDO $db, string $agentID): bool {
    return is_valid_id($agentID) && Agent::isValidId($db, intval($agentID));
}
?>
