<?php

class Session
{

    private array $messages;
    public function __construct()
    {
        session_set_cookie_params(0, '/', '', false, true);
        session_start();

        $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
        unset($_SESSION['messages']);
        
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = Session::generate_random_token();
        }
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['id']);
    }

    public function logout()
    {
        session_destroy();
    }

    public function getId(): ?int
    {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;
    }

    public function getName(): ?string
    {
        return isset($_SESSION['name']) ? $_SESSION['name'] : null;
    }
    public function getCsrf(): ?string {
        return $_SESSION['csrf'];
    }
    public function verifyCsrf(string $csrf): bool {
        $return_value = $_SESSION['csrf'] === $csrf;
        // $_SESSION['csrf'] = Session::generate_random_token();
        return $return_value;
    } 
    public function setId(int $id)
    {
        $_SESSION['id'] = $id;
    }

    public function setName(string $name)
    {
        $_SESSION['name'] = $name;
    }
    public function addMessage(string $type, string $text)
    {
        $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages()
    {
        return $this->messages;
    }
    private static function generate_random_token() {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}
?>
