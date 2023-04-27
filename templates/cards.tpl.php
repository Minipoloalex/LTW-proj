<?php function drawCardContainer()
{ ?>
  <section id="card-wrapper">
    <!-- This is where the cards will be drawn -->
    <div id="card-container"></div>
    <!-- loading new cards  -->
    <div id="loader">
      <div class="skeleton-card"></div>
      <div class="skeleton-card"></div>
      <div class="skeleton-card"></div>
    </div>
    <div class="card-actions">
      <span>Showing
        <span id="card-count"></span> of
        <span id="card-total"></span> cards
      </span>
    </div>
  </section>
<?php } ?>
<!-- This is the template for the cards - TICKET -->
<div class="card">
  <article>
    <span class="card-title"></span>
    <span class="card-info"></span>
    <span class="card-info card-description"></span>
    <span class="card-info card-hashtags"></span>
    
  </article>
</div>