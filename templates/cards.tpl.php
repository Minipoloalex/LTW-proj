<?php function drawCardContainer()
{ ?>
  <section id="card-wrapper">
    <div id="card-container"></div>
    <div id="loader">
      <div class="skeleton-card d-none"></div>
      <div class="skeleton-card d-none"></div>
      <div class="skeleton-card d-none"></div>
      <div class="skeleton-card d-none"></div>
    </div>
    <div id="no-cards" class="d-none"></div>
    <div class="card-actions">
      <span>Showing
        <span id="card-count"></span> of
        <span id="card-total"></span> cards
      </span>
    </div>
  </section>
<?php } ?>
