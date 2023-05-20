<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/common.tpl.php');
?>
<?php function drawUsersFilterMenu(array $filterValues)
{
    $type = 'user';
    ?>
    <?php
    drawMenuTitle("Filters");
    ?>
    <section id="filter-section" class="menu hidden filter-section" data-type="<?=$type?>">
        <div class="filter-options">
            <?php
            drawDropdownTitle("Department");
            drawDropdownOptions("department-section", "department", $filterValues[0], $type);
            
            drawDropdownTitle("User type");
            drawDropdownOptions("usertype-section", "user_type", $filterValues[1], $type);
            ?>
        </div>
        
        <?php
        drawFilterButtons();
        ?>
    </section>
<?php } ?>
