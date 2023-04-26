<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/forum.class.php');
?>

<?php function output_single_faq(Forum $faq, string $type)
{ ?>
	<article class="faq">
		<span class="question">
			<?= $faq->question ?>
		</span>
		<span class="answer">
			<?= $faq->answer ?>
		</span>

		<?php if ($type !== 'Client') { ?>
			<button class="edit-faq"><span class="material-symbols-outlined">edit</span></button>

			<?php if ($faq->displayed===1) { ?>
				<button class="hide-faq"><span class="material-symbols-outlined">visibility_off</span></button>
			<?php } else { ?>
			<button class="display-faq"><span class="material-symbols-outlined">visibility</span></button>
			<?php } ?>

			<?php if ($faq->answer === NULL) { ?>
				<button class="answer-faq">Answer</button>
			<?php } ?>

		<?php } ?>
	</article>
<?php } ?>

<?php function output_all_faqs(array $faqs, string $type)
{ ?>
	<section id='faqs'>
		<?php
		foreach ($faqs as $faq) {
			output_single_faq($faq, $type);
		}
		?>
	</section>
<?php } ?>

<?php function output_faq_form(Forum $faq = null)
{ ?>
	<div class="faq-page">
		<h1>Frequently Asked Questions</h1>
		<p>Use the form below to ask a question about our service. Our agents will answer your question as soon as possible.
		</p>
		<p>Agents can also use the FAQ to answer tickets, so your question might be added to the FAQ if it's a common
			question.</p>
		<form id='faq-form' class="faq-form">
			<label for="question">Your question:</label>
			<textarea id="question" name="question" required></textarea>
			<!-- <label for="username">Your username:</label> -->
			<!-- <input type="text" id="username" name="username" required> -->
			<button type="submit">Ask</button>
		</form>
		<div class="success-message" id="success-message">Your question has been sent successfully. We'll answer you as soon
			as possible.</div>
		<div class="error-message" id="error-message">There was an error sending your question. Please try again.</div>
	</div>

<?php } ?>