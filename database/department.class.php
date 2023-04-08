<?php
declare(strict_types = 1);
class Department {
    public int $departmentId;
    public string $departmentName;
    public function __construct(int $departmentId, string $departmentName) {
        $this->departmentId = $departmentId;
        $this->departmentName = $departmentName;
    }

    static public function getDepartments(PDO $db): array {
        $stmt = $db->prepare('SELECT DepartmentID, DepartmentName FROM DEPARTMENT');
        $stmt->execute();

        $departments = array();
        while ($department = $stmt->fetch()){
            $departments[] = new Department(
                intval($department['DepartmentID']),
                $department['DepartmentName']
            );
        }
        return $departments;
    }
    static public function getById(PDO $db, int $id): Department {
        $stmt = $db->prepare('SELECT DepartmentID, DepartmentName FROM DEPARTMENT WHERE DepartmentId = ?');
        $stmt->execute(array($id));
        $department = $stmt->fetch();

        return new Department(
            intval($department['DepartmentID']),
            $department['DepartmentName']
        );
    }
}

?>
