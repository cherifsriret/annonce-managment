<?php
/**
 * Plugin Name: Annonces Management
 * Plugin URI: https://example.com/
 * Description: A plugin to manage annonces on your WordPress site.
 * Version: 1.0.0
 * Author: Sriret Cherif amine
 * Author URI: https://example.com/
 * License: GPL2
 */
add_action( 'init', 'create_custom_post_type' );
function create_custom_post_type() {
  $labels = array(
    'name' => __( 'AnnonceC' ),
    'singular_name' => __( 'AnnonceC' ),
    'add_new' => __( 'Add New AnnonceC' ),
    'add_new_item' => __( 'Add New AnnonceC' ),
    'edit_item' => __( 'Edit AnnonceC' ),
    'new_item' => __( 'New AnnonceC' ),
    'view_item' => __( 'View AnnonceC' ),
    'search_items' => __( 'Search AnnonceC' ),
    'not_found' => __( 'No AnnonceC found' ),
    'not_found_in_trash' => __( 'No AnnonceC found in Trash' ),
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'has_archive' => true,
    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
    'rewrite' => array( 'slug' => 'annoncec' ),
    'taxonomies' => array( 'categAnnonce' ),
    'menu_position' => 5,
    'menu_icon' => 'dashicons-megaphone',
    'register_meta_box_cb' => 'add_custom_meta_boxes',
  );
  register_post_type( 'annoncec', $args );
}

function add_custom_meta_boxes() {
  add_meta_box( 'annoncec_images', 'Images', 'display_annoncec_images_meta_box', 'annoncec', 'normal', 'high' );
  add_meta_box( 'annoncec_price', 'Price', 'display_annoncec_price_meta_box', 'annoncec', 'side', 'high' );
}

function display_annoncec_images_meta_box( $post ) {
  $images = get_post_meta( $post->ID, 'annoncec_images', true );
  wp_nonce_field( 'save_annoncec_images_meta_box', 'nonce_annoncec_images' );
  ?>
  <p>
    <label for="annoncec_images"><?php _e( 'Upload Images:' ); ?></label><br />
    <input type="file" id="annoncec_images" name="annoncec_images[]" accept="image/*" multiple="multiple" /><br />
    <span class="description"><?php _e( 'Upload multiple images for the AnnonceC post.' ); ?></span>
  </p>
  <?php if ( ! empty( $images ) ) : ?>
    <ul>
      <?php foreach ( $images as $image ) : ?>
        <li><img src="<?php echo $image; ?>" width="100" height="100" /></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <?php
}

function display_annoncec_price_meta_box( $post ) {
  $price = get_post_meta( $post->ID, 'annoncec_price', true );
  wp_nonce_field( 'save_annoncec_price_meta_box', 'nonce_annoncec_price' );
  ?>
  <p>
    <label for="annoncec_price"><?php _e( 'Price:' ); ?></label><br />
    <input type="text" id="annoncec_price" name="annoncec_price" value="<?php echo $price; ?>" />
<span class="description"><?php _e( 'Enter the price for the AnnonceC post.' ); ?></span>

  </p>
  <?php
}
function save_custom_meta_boxes( $post_id ) {
if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
return;
}

if ( ! isset( $_POST['nonce_annoncec_images'] ) || ! wp_verify_nonce( $_POST['nonce_annoncec_images'], 'save_annoncec_images_meta_box' ) ) {
return;
}

if ( ! isset( $_POST['nonce_annoncec_price'] ) || ! wp_verify_nonce( $_POST['nonce_annoncec_price'], 'save_annoncec_price_meta_box' ) ) {
return;
}

if ( isset( $_POST['annoncec_images'] ) ) {
$images = array();
foreach ( $_POST['annoncec_images'] as $image ) {
$images[] = esc_url_raw( $image );
}
update_post_meta( $post_id, 'annoncec_images', $images );
}

if ( isset( $_POST['annoncec_price'] ) ) {
update_post_meta( $post_id, 'annoncec_price', sanitize_text_field( $_POST['annoncec_price'] ) );
}
}

add_action( 'save_post_annoncec', 'save_custom_meta_boxes' );




function add_annoncec_handler() {
    if ( isset( $_POST['add_annoncec_nonce'] ) && wp_verify_nonce( $_POST['add_annoncec_nonce'], 'add_annoncec_action' ) ) {
  
      $annoncec_title = sanitize_text_field( $_POST['annoncec_title'] );
      $annoncec_description = sanitize_text_field( $_POST['annoncec_description'] );
      $annoncec_images = $_FILES['annoncec_images'];
      $annoncec_price = sanitize_text_field( $_POST['annoncec_price'] );
  
      // Create the AnnonceC post
      $annoncec_id = wp_insert_post( array(
        'post_title'    => $annoncec_title,
        'post_content'  => $annoncec_description,
        'post_status'   => 'publish',
        'post_type'     => 'annoncec' ) );
  
  // Add the AnnonceC price as post meta
  update_post_meta( $annoncec_id, '_annoncec_price', $annoncec_price );
  
  // Add the AnnonceC images as post attachments
  $attachment_ids = array();
  foreach ( $annoncec_images['name'] as $key => $value ) {
    if ( $annoncec_images['name'][$key] ) {
      $file = array(
        'name'     => $annoncec_images['name'][$key],
        'type'     => $annoncec_images['type'][$key],
        'tmp_name' => $annoncec_images['tmp_name'][$key],
        'error'    => $annoncec_images['error'][$key],
        'size'     => $annoncec_images['size'][$key],
      );
      $attachment_id = media_handle_sideload( $file, $annoncec_id );
      if ( ! is_wp_error( $attachment_id ) ) {
        $attachment_ids[] = $attachment_id;
      }
    }
  }
  if ( $attachment_ids ) {
    update_post_meta( $annoncec_id, '_annoncec_images', $attachment_ids );
  }
  
  // Redirect to the newly created AnnonceC post
  wp_redirect( get_permalink( $annoncec_id ) );
  exit;
  }
  }
  add_action( 'admin_post_add_annoncec', 'add_annoncec_handler' );












 
 // Register custom post type
function annonces_management_register_post_type() {
    $labels = array(
        'name'                  => _x( 'Annonces', 'Post type general name', 'annonces-management' ),
        'singular_name'         => _x( 'Annonce', 'Post type singular name', 'annonces-management' ),
        'menu_name'             => _x( 'Annonces', 'Admin Menu text', 'annonces-management' ),
        'name_admin_bar'        => _x( 'Annonce', 'Add New on Toolbar', 'annonces-management' ),
        'add_new'               => __( 'Add New', 'annonces-management' ),
        'add_new_item'          => __( 'Add New Annonce', 'annonces-management' ),
        'new_item'              => __( 'New Annonce', 'annonces-management' ),
        'edit_item'             => __( 'Edit Annonce', 'annonces-management' ),
        'view_item'             => __( 'View Annonce', 'annonces-management' ),
        'all_items'             => __( 'All Annonces', 'annonces-management' ),
        'search_items'          => __( 'Search Annonces', 'annonces-management' ),
        'parent_item_colon'     => __( 'Parent Annonces:', 'annonces-management' ),
        'not_found'             => __( 'No annonces found.', 'annonces-management' ),
        'not_found_in_trash'    => __( 'No annonces found in Trash.', 'annonces-management' ),
        'featured_image'        => _x( 'Annonce Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'annonces-management' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'annonces-management' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'annonces-management' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'annonces-management' ),
        'archives'              => _x( 'Annonce archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'annonces-management' ),
        'insert_into_item'      => _x( 'Insert into Annonce', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'annonces-management' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Annonce', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'annonces-management' ),
        'filter_items_list'     => _x( 'Filter Annonces list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'annonces-management')
    );
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'annonce' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'supports'           => array( 'title', 'editor', 'thumbnail', 'author' ),
        );
        
        register_post_type( 'annonce', $args );
    }
    add_action( 'init', 'annonces_management_register_post_type' );


    // Add custom fields to the "annonce" post type
function annonces_management_add_custom_fields() {
    add_meta_box( 'annonce_title', 'Title', 'annonces_management_render_title_field', 'annonce', 'normal', 'default' );
    add_meta_box( 'annonce_description', 'Description', 'annonces_management_render_description_field', 'annonce', 'normal', 'default' );
    add_meta_box( 'annonce_images', 'Images', 'annonces_management_render_images_field', 'annonce', 'normal', 'default' );
    add_meta_box( 'annonce_prices', 'Prices', 'annonces_management_render_prices_field', 'annonce', 'normal', 'default' );
    add_meta_box( 'annonce_categories', 'Categories', 'annonces_management_render_categories_field', 'annonce', 'side', 'default' );
    }
    add_action( 'add_meta_boxes', 'annonces_management_add_custom_fields' );
    
    // Render title field
    function annonces_management_render_title_field() {
    global $post;
    $title = get_post_meta( $post->ID, '_annonce_title', true );
    echo '<input type="text" name="annonce_title" value="' . esc_attr( $title ) . '" />';
    }
    
    // Render description field
    function annonces_management_render_description_field() {
    global $post;
    $description = get_post_meta( $post->ID, '_annonce_description', true );
    echo '<textarea name="annonce_description">' . esc_textarea( $description ) . '</textarea>';
    }
    
    // Render images field
    function annonces_management_render_images_field() {
    global $post;
    $images = get_post_meta( $post->ID, '_annonce_images', true );
    echo '<input type="file" name="annonce_images" />';
    }
    
    // Render prices field
    function annonces_management_render_prices_field() {
    global $post;
    $prices = get_post_meta( $post->ID, '_annonce_prices', true );
    echo '<input type="text" name="annonce_prices" value="' . esc_attr( $prices ) . '" />';
    }
    
    // Render categories field
    function annonces_management_render_categories_field() {
    global $post;
    $categories = get_the_terms( $post->ID, 'categorie' );
    $categories_args = array(
    'taxonomy' => 'categorie',
    'hide_empty' => false,
    'selected' => $categories,
    'name' => 'annonce_categories[]',
    'value_field' => 'term_id');

    wp_terms_checklist( $post->ID, $categories_args );
}

// Save custom fields
function annonces_management_save_custom_fields( $post_id ) {
    // Save title field
    if ( isset( $_POST['annonce_title'] ) ) {
    update_post_meta( $post_id, '_annonce_title', sanitize_text_field( $_POST['annonce_title'] ) );
    }

    // Save description field
if ( isset( $_POST['annonce_description'] ) ) {
    update_post_meta( $post_id, '_annonce_description', sanitize_textarea_field( $_POST['annonce_description'] ) );
}

// Save images field
if ( isset( $_FILES['annonce_images'] ) ) {
    $upload = wp_upload_bits( $_FILES['annonce_images']['name'], null, file_get_contents( $_FILES['annonce_images']['tmp_name'] ) );
    if ( isset( $upload['error'] ) && $upload['error'] != 0 ) {
        wp_die( 'There was an error uploading your file. The error message was: ' . $upload['error'] );
    } else {
        update_post_meta( $post_id, '_annonce_images', $upload );
    }
}

// Save prices field
if ( isset( $_POST['annonce_prices'] ) ) {
    update_post_meta( $post_id, '_annonce_prices', sanitize_text_field( $_POST['annonce_prices'] ) );
}

// Save categories field
if ( isset( $_POST['annonce_categories'] ) ) {
    wp_set_object_terms( $post_id, $_POST['annonce_categories'], 'categorie', false );
}
}
add_action( 'save_post', 'annonces_management_save_custom_fields' );




// /// annonce archive
// function annonces_register_page_templates( $page_templates ) {
//     $page_templates['archive-annonce.php'] = __( 'Annonces Archive', 'annonces-management' );
//     return $page_templates;
// }
// add_filter( 'theme_page_templates', 'annonces_register_page_templates' );

// function annonces_add_page_templates( $templates ) {
//     $templates['archive-annonce.php'] = __( 'Annonces Archive', 'annonces-management' );
//     return $templates;
// }
// add_filter( 'template_include', 'annonces_add_page_templates' );



/// add annonces
function annonces_register_page_templates_add( $page_templates ) {
    $page_templates['add-annonce.php'] = __( 'Add Annonce', 'annonces-management' );
    return $page_templates;
}
add_filter( 'theme_page_templates', 'annonces_register_page_templates_add' );

add_filter( 'template_include', 'annonces_add_page_templates_add', 99 );
function annonces_add_page_templates_add( $template ) {
    if ( is_page_template( 'add-annonce.php' )  ) {
        $template_path =  plugin_dir_path( __DIR__ ) . '/annonces-management/' . "add-annonce.php";
        if(file_exists($template_path)){
            return $template_path;
 //           include($template_path);
          //  exit;
        }
    }
    return $template;
}





/* single page */
function my_annonce_single_template( $single_template ) {
    global $post;
    if ( 'annonce' === $post->post_type ) {
        $single_template = dirname( __FILE__ ) . '/annonce-single.php';
    }
    return $single_template;
}
add_filter( 'single_template', 'my_annonce_single_template' );










// function register_annonce_single_template() {
//     \Elementor\Plugin::instance()->templates_manager->register_template_type( 'annonce-single', [
//         'label' => __( 'Annonce Single', 'annonces-management' ),
//         'icon' => 'fa fa-file-text',
//         'is_internal' => true,
//         'singular_label' => __( 'Annonce Single', 'annonces-management' ),
//         'supports' => [ 'page' ],
//     ] );
// }
// add_action( 'elementor/init', 'register_annonce_single_template' );

// function create_annonce_single_template_content() {
//     // Your Elementor template code goes here
// }

// function register_annonce_single_elementor_template() {
//     if ( ! \Elementor\Plugin::instance()->templates_manager->get_template_type( 'annonce-single' ) ) {
//         return;
//     }
    
//     $template = new \Elementor\TemplateLibrary\Source_Local( $this->plugin_path . '/templates/annonce-single.php' );
    
//     \Elementor\Plugin::instance()->templates_manager->register_template( 'annonce-single', [
//         'title' => __( 'Annonce Single', 'annonces-management' ),
//         'template_type' => 'annonce-single',
//         'template_file' => $template->get_file(),
//         'template_preview_url' => '',
//         'is_editable' => true,
//     ] );
// }
// add_action( 'elementor/template-library/elementor/editor/footer', 'register_annonce_single_elementor_template' );





//// Create recipes CPT
function recipes_post_type() {
    register_post_type( 'recipes',
        array(
            'labels' => array(
                'name' => __( 'Recipes' ),
                'singular_name' => __( 'Recipe' )
            ),
            'public' => true,
            'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
        'rewrite'   => array( 'slug' => 'my-home-recipes' ),
            'menu_position' => 5,
        'menu_icon' => 'dashicons-food',
        // 'taxonomies' => array('cuisines', 'post_tag') // this is IMPORTANT
        )
    );
}
add_action( 'init', 'recipes_post_type' );

//// Add cuisines taxonomy
function create_recipes_taxonomy() {
    register_taxonomy('cuisines','recipes',array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x( 'Cuisines', 'taxonomy general name' ),
            'singular_name' => _x( 'Cuisine', 'taxonomy singular name' ),
            'menu_name' => __( 'Cuisines' ),
            'all_items' => __( 'All Cuisines' ),
            'edit_item' => __( 'Edit Cuisine' ), 
            'update_item' => __( 'Update Cuisine' ),
            'add_new_item' => __( 'Add Cuisine' ),
            'new_item_name' => __( 'New Cuisine' ),
        ),
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    ));
    register_taxonomy('ingredients','recipes',array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x( 'Ingredients', 'taxonomy general name' ),
            'singular_name' => _x( 'Ingredient', 'taxonomy singular name' ),
            'menu_name' => __( 'Ingredients' ),
            'all_items' => __( 'All Ingredients' ),
            'edit_item' => __( 'Edit Ingredient' ), 
            'update_item' => __( 'Update Ingredient' ),
            'add_new_item' => __( 'Add Ingredient' ),
            'new_item_name' => __( 'New Ingredient' ),
        ),
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    ));
}
add_action( 'init', 'create_recipes_taxonomy', 0 );

?>