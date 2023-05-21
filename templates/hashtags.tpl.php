<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/hashtag.class.php');
?>

<?php function drawHashtagsList($hashtags){ ?>
    <ul class='hashtag-list'>
        <?php foreach ($hashtags as $hashtag) { ?>
            <li class="list-hashtags"><?=$hashtag->hashtagname?></li>
        <?php } ?>
    </ul>
<?php } ?>

<?php function drawHashtagsMenu(array $hashtags){ ?>
<section id="hashtag-section" class="menu hashtags-menu">
    <div class="hashtag-options">
        <form id='add-new-hashtag'>
            <input type="text" name="hashtag" placeholder="Hashtag" required>
            <button id="create-hashtag" type="submit">Add</button>
        </form>
        <form id='delete-hashtag-form'>
            <?php
            output_hashtag_search($hashtags, NULL, "delete-hashtag", "Delete", "Hashtag");
            ?>
        </form>
    </div>
    <?php drawHashtagsList($hashtags); ?>
</section>

<?php } ?>
