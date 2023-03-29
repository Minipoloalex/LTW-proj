<?php
declare(strict_types = 1);
class Admin extends Agent {


    public function __construct(int $id, string $name, string $username, string $password, string $email, int $departmentid)
    {
        parent::__construct($id, $name, $username, $password, $email, $departmentid);
    }

    
}

?>
