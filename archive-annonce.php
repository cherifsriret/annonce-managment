<?php
/*
Template Name: Annonces Archive
*/
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        $args = array(
            'post_type' => 'annonce',
            'posts_per_page' => -1,
        );
        $query = new WP_Query( $args );
        ?>
        <?php if ( $query->have_posts() ) : ?>
            <div class="annonces-archive">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="annonce">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                        <?php endif; ?>
                        <div class="annonce-excerpt"><?php the_excerpt(); ?></div>
                        <div class="annonce-meta">
                            <?php echo get_the_term_list( get_the_ID(), 'categorie', '<span class="categorie">', ', ', '</span>' ); ?>
                            <span class="annonce-author"><?php echo __('Posted by', 'annonces-management') . ' ' . get_the_author(); ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </main>
</div>
<?php get_footer(); ?>