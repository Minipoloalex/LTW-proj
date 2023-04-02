<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/forum.class.php');

?>

<?php function output_single_faq(Forum $faq) { ?>
    <article>
    </article>
<?php } ?>

<?php function output_all_faqs(array $faqs) { ?>
    <section id='faqs'>
        <?php
        foreach($faqs as $faq) {
            output_single_faq($faq);
        }
        ?>
    </section>
<?php } ?>
