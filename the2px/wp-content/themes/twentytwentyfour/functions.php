<?php
/**
 * Twenty Twenty-Four functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty Twenty-Four
 * @since Twenty Twenty-Four 1.0
 */

// Existing block styles and other functions...

// Register custom fields for SVG Images
function register_custom_fields() {
    // Check if the ACF plugin is activated
    if (function_exists('register_rest_field')) {
        // Register custom fields for SVG Images
        register_rest_field('svg_images', 'svg_image_name', [
            'get_callback' => function($data) {
                return get_field('svg_image_name', $data['id']);
            }
        ]);
        register_rest_field('svg_images', 'svg_image_description', [
            'get_callback' => function($data) {
                return get_field('svg_image_description', $data['id']);
            }
        ]);
        register_rest_field('svg_images', 'svg_image_tags', [
            'get_callback' => function($data) {
                return get_field('svg_image_tags', $data['id']);
            }
        ]);
        register_rest_field('svg_images', 'svg_file_categorie', [
            'get_callback' => function($data) {
                return get_field('svg_file_categorie', $data['id']);
            }
        ]);
        register_rest_field('svg_images', 'svg_image_file', [
            'get_callback' => function($data) {
                return get_field('svg_image_file', $data['id']);
            }
        ]);
    }
}
add_action('rest_api_init', 'register_custom_fields');

// Allow SVG uploads
function allow_svg_uploads($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_uploads');

// Fix for SVG preview in the Media Library
function fix_svg_media_library($response, $attachment_id) {
    $response['type'] = 'image/svg+xml';
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'fix_svg_media_library', 10, 2);

// Add custom columns to the SVG Images admin table
function svg_images_custom_columns($columns) {
    $columns = array(
        'cb' => '<input type="checkbox" />', // Checkbox for bulk actions
        'title' => __('Image Name'),
        'author' => __('Author'), // Add author column
        'date' => __('Date'),
    );
    return $columns;
}
add_filter('manage_svg_images_posts_columns', 'svg_images_custom_columns');

// Populate custom columns with SVG image data
function svg_images_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'author':
            $author_id = get_post_field('post_author', $post_id);
            $author_name = get_the_author_meta('display_name', $author_id);
            echo esc_html($author_name);
            break;
        case 'category':
            echo get_field('svg_file_categorie', $post_id);
            break;
    }
}
add_action('manage_svg_images_posts_custom_column', 'svg_images_custom_column_content', 10, 2);

//CODE TO ADD CUSTOM FUNCTION 

// Hook into the users list table columns
add_filter('manage_users_columns', 'custom_user_svg_images_column');
add_action('manage_users_custom_column', 'show_svg_images_count', 10, 3);

// Add a new column for SVG Images post count
function custom_user_svg_images_column($columns) {
    $columns['svg_images_count'] = __('SVG Images Count');
    return $columns;
}

// Display the SVG Images count for each user
function show_svg_images_count($value, $column_name, $user_id) {
    if ('svg_images_count' === $column_name) {
        // Count the number of SVG Images posts by this user
        $count = count_user_posts($user_id, 'svg_images');
        return $count;
    }
    return $value;
}
