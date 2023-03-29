<?php function output_single_faq($faq) { ?>

<?php } ?>

<?php function output_all_faqs($faqs) { ?>
    <section id='faqs'>
        <?php
        foreach($faqs as $faq) {
            output_single_faq($faq);
        }
        ?>
    </section>
<?php } ?>
