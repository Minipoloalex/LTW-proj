<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/department.class.php');
?>

<?php function output_single_department(Department $department) { ?>
    <li>
        <p><?=$department->departmentName?></p>
    </li>
<?php } ?>

<?php function output_departments(array $departments) { ?>
    <section id="departments">
        <header><h2>Departments</h2></header>
        <ul>
            <?php
                foreach($departments as $department) {
                    output_single_department($department);
                }
            ?>
        </ul>
    </section>
<?php } ?>
