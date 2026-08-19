<?php
/**
 * Renderer for native Divi pages (served via inc/native-gate.php, never by
 * slug). Mirrors the parent theme's page.php for builder pages with ONE
 * change: the content wrapper is a real <main> landmark, matching the
 * sidecar templates. Without it, every design rule scoped under `main ...`
 * goes dead on native pages and screen readers lose the landmark
 * (fidelity council round: semantic parity gate).
 *
 * Parent baseline: Divi 5.2.1 page.php (builder branch only; native pages
 * always use the builder, so the classic-editor branch is not mirrored).
 * Re-check on any Divi update per the pin policy in docs/DEPLOY-RUNBOOK.md.
 */

get_header();
?>

<main id="main-content">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>

		</article>

	<?php endwhile; ?>

</main>

<?php
get_footer();
