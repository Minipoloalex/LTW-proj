<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/forum.class.php');
require_once(__DIR__ . '/common.tpl.php');
?>

<?php function output_single_faq(Forum $faq, string $type)
{ ?>

	<article class="faq" data-id="<?= $faq->forumId ?>">
		<textarea id="question" name="question" class="question input-readonly" value="<?= htmlentities($faq->question) ?>"
			maxlength="100" rows="1" readonly><?= htmlentities($faq->question) ?></textarea>

		<textarea id="answer" name="answer" class="answer input-readonly" value="<?= htmlentities($faq->answer ?? '') ?>"
			rows="1" readonly><?= htmlentities($faq->answer ?? '') ?></textarea>

		<?php if ($type !== 'Client') { ?>
			<button id="editFaqBtn" class="edit-faq"><span class="material-symbols-outlined">edit</span></button>
			<button id="saveFaqBtn" class="save-faq" hidden><span class="material-symbols-outlined">save</span></button>
			<button id="deleteFaqBtn" class="delete-faq"><span class="material-symbols-outlined">delete</span></button>

			<?php if ($faq->displayed === 1) { ?>
				<button id="hideBtn" class="hide-faq"><span class="material-symbols-outlined">visibility_off</span></button>
				<button id="displayBtn" class="hide-faq" hidden><span class="material-symbols-outlined">visibility</span></button>
			<?php } else { ?>
				<button id="hideBtn" class="hide-faq" hidden><span class="material-symbols-outlined">visibility_off</span></button>
				<button id="displayBtn" class="hide-faq"><span class="material-symbols-outlined">visibility</span></button>
			<?php } ?>


			<!-- // !FIXME: esta verificação tem que passar para js. Todos têm que ter este botão. -->
			<?php if ($faq->answer === NULL) { ?>
				<button id="answerFaq" class="answer-faq">Answer question</button>
				<button id="saveAnswerBtn" class="save-answer" hidden>Save answer</button>
			<?php } ?>

		<?php } ?>
	</article>

	<!-- <div id="feedback-message-single-faq" class="feedback-message">m</div> -->

<?php } ?>
<?php function output_all_faqs(array $faqs, string $type)
{ ?>
	<section id='faqs'>
		<?php
		foreach ($faqs as $faq) {
			if ($type !== 'Client' || ($type === 'Client' && $faq->displayed === 1))
				output_single_faq($faq, $type);
		}
		?>
	</section>
<?php } ?>

<?php function output_faq_form(Forum $faq = null)
{ ?>
	<header class="faq-page">
		<h2 class="faq-header">Frequently Asked Questions</h2>
		<p>Browse our FAQs for quick answers to common questions! If you can't find what you're looking for, use the form
			below to ask a question about our service. Our agents will answer your question as soon as possible.
		</p>
		<p>Agents can also use the FAQ to answer tickets, so your question might be added to the FAQ if it's a common
			question.</p>
			<?php
			output_textarea_form(
				"faq-form",
				"Your question <small>(max 100 chars)</small>:",
				"question-form",
			array("<button type='submit'>Ask</button>"));
			?>
		<div id="feedback-message" class="feedback-message"></div>

		</div>
<!-- 
		<div class="success-message" id="success-message">Your question has been sent successfully. We'll answer you as soon
			as possible.</div>
		<div class="error-message" id="error-message">There was an error sending your question. Please try again.</div> -->
	</header>
<?php } ?>
