<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/common.tpl.php');
?>


<?php function drawFilterMenu(array $filterValues, string $pageType)
{
    $type = 'ticket';
    ?>
    <section class="filter-manage-hashtag-grid">
        <?php
        drawHashtagsLink();
        drawMenuTitle('Filters');
        ?>
        <section id="filter-section" class="menu hidden filter-section" data-type="<?=$type?>" data-pageType="<?=$pageType?>">
            <div id="filter-values" class="filter-options">
                <?php
                drawDropdownTitle('Status');
                drawDropdownOptions("status-section", "status", $filterValues[0], $type);

                drawDropdownTitle('Priority');
                drawDropdownOptions("priority-section", "priority", $filterValues[1], $type);
                
                drawDropdownTitle('Hashtags');
                drawDropdownOptions("hashtags-section", "hashtags", $filterValues[2], $type, "hashtags");
                
                drawDropdownTitle('Agent');
                drawDropdownOptions("agents-section", "agents", $filterValues[3], $type, "agent");

                drawDropdownTitle('Department');
                drawDropdownOptions("departments-section", "departments", $filterValues[4], $type, "department");
                ?>
            </div>
            
            <?php
            drawFilterButtons();
            ?>
        </section>
    </section>
<?php } ?>

<?php function drawHashtagsLink(){ ?>
    <button><a class='link-to-hashtags' href="../pages/hashtags.php">Manage hashtags</a></button>
<?php } ?>
