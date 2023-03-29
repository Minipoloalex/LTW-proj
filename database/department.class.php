<?php
declare(strict_types = 1);
class Department {
    public int $departmentId;
    public string $departmentName;
    public function __construct(int $departmentId, string $departmentName) {
        $this->departmentId = $departmentId;
        $this->departmentName = $departmentName;
    }

    public function getDepartments(PDO $db, int $count): array {
        $stmt = $db->prepare('SELECT DepartmentId, DepartmentName FROM DEPARTMENT');
        $stmt->execute();

        $departments = array();
        while ($department = $stmt->fetch()){
            $departments[] = new Department(
                intval($department['DepartmentId']),
                $department['DepartmentName']
            );
        }
        return $departments;
    }
}

?>
