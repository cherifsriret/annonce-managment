<?php
/*
Template Name: Annonce Single
Template Post Type: annonce
*/

get_header();


if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        var_export(the_post());
    endwhile;
endif;

get_footer(); ?>