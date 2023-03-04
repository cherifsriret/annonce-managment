<?php
/*
Template Name: Add AnnonceC
*/

get_header(); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
      </header>

      <div class="entry-content">
        <form id="add-annoncec-form" method="post" enctype="multipart/form-data">
          <?php wp_nonce_field( 'add_annoncec_action', 'add_annoncec_nonce' ); ?>

          <p>
            <label for="annoncec_title"><?php _e( 'Title:' ); ?></label>
            <input type="text" id="annoncec_title" name="annoncec_title" />
          </p>

          <p>
            <label for="annoncec_description"><?php _e( 'Description:' ); ?></label>
            <textarea id="annoncec_description" name="annoncec_description"></textarea>
          </p>

          <p>
            <label for="annoncec_images"><?php _e( 'Images:' ); ?></label>
            <input type="file" id="annoncec_images" name="annoncec_images[]" multiple />
          </p>

          <p>
            <label for="annoncec_price"><?php _e( 'Price:' ); ?></label>
            <input type="text" id="annoncec_price" name="annoncec_price" />
          </p>

          <p>
            <input type="submit" value="<?php _e( 'Add AnnonceC' ); ?>" />
          </p>
        </form>
      </div><!-- .entry-content -->
    </article><!-- #post-<?php the_ID(); ?> -->

  </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>