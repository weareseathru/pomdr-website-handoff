<?php
/** Template for the Adoption Application page (WP slug: adoption-questionnaire). Ports prototype adoption-form.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
  <style>
    .placeholder-form {
      max-width: 600px;
      margin: 80px auto;
      padding: 40px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    .placeholder-form h1 {
      margin-top: 0;
      color: var(--teal);
    }
  </style>
<main id="main-content">
  <main>
    <div class="container">
      <div class="placeholder-form">
        <h1>Adoption Application</h1>
        <p>This is a placeholder for the Little Green Light (LGL) adoption form integration.</p>
        <p id="prefilled-dog" style="font-weight: 600; color: var(--purple); margin-bottom: 24px;"></p>
        <form onsubmit="event.preventDefault(); alert('Placeholder form submitted!');">
          <div style="margin-bottom: 16px;">
            <label for="name" style="display:block; margin-bottom: 8px;">Your Name</label>
            <input type="text" id="name" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px;" required>
          </div>
          <div style="margin-bottom: 24px;">
            <label for="email" style="display:block; margin-bottom: 8px;">Email Address</label>
            <input type="email" id="email" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px;" required>
          </div>
          <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Application</button>
        </form>
      </div>
    </div>
  </main>
  <script>
    // Grab the dog name from the URL params to demonstrate LGL field_21 prefilling
    const params = new URLSearchParams(window.location.search);
    const dogName = params.get('field_21');
    if (dogName) {
      document.getElementById('prefilled-dog').innerText = `Applying to adopt: ${dogName}`;
    }
  </script>
</main>
<?php get_footer();
