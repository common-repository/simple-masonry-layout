<?php

namespace SimpleMasonryLayout;

/**
 *
 *
 * This class loads all of our plugin admin settings.
 *
 * @package    Simple Masonry Layout
 * @subpackage Simple Masonry Layout Admin settings
 * @author Raju Tako
 */


class SimpleMasonryAdmin
{

    private static $instance           = null;
    const POST_TYPE                    = 'sm-masonry-layout';
    private static $post_types_exclude = ['attachment'];

    private function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addCustomMetabox']);
        add_action('save_post', [$this, 'saveCustomMetaboxData']);
        add_action('init', [$this, 'registerMasonryPostType']);
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueue']);
        add_filter('manage_' . self::POST_TYPE . '_posts_columns', [$this, 'addSmShortcodeColumn']);
        add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [$this, 'populateSmShortcodeColumnData'], 10, 2);
    }


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function adminEnqueue()
    {
        $screen = get_current_screen();
        if ('sm-masonry-layout' == $screen->post_type) {
            wp_enqueue_style('sm-admin-style', SIMPLEMASONRYLAYOUT_DIR_URL . 'assets/css/admin.css');
        }
    }


    public function registerMasonryPostType()
    {
        $labels = [
            'name'               => 'Simple Masonry Layouts',
            'singular_name'      => 'Masonry',
            'menu_name'          => 'Simple Masonry',
            'all_items'          => 'All Layouts',
            'add_new'            => 'Add New Layout',
            'add_new_item'       => 'Add New Masonry',
            'edit_item'          => 'Edit Masonry',
            'new_item'           => 'New Masonry',
            'view_item'          => 'View Masonry',
            'search_items'       => 'Search Masonry',
            'not_found'          => 'No Masonry found',
            'not_found_in_trash' => 'No Masonry found in Trash',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title'],
            'menu_icon'          => 'dashicons-tagcloud',
        ];

        register_post_type(self::POST_TYPE, $args);
    }


    public function addCustomMetabox()
    {
        add_meta_box(
            'simple_masonry_settings_metabox',
            'Simple Masonry Layout Settings',
            [$this, 'renderMetabox'],
            self::POST_TYPE,
            'normal',
            'high'
        );
    }


    public function renderMetabox($post)
    {
        $sm_as_gallery        = get_post_meta($post->ID, 'sm_as_gallery', true);
        $sm_post_type         = get_post_meta($post->ID, 'sm_post_type', true);
        $sm_category          = get_post_meta($post->ID, 'sm_category', true);
        $simple_post_per_page = get_post_meta($post->ID, 'simple_post_per_page', true);
        $simple_post_orderby  = get_post_meta($post->ID, 'simple_post_orderby', true);
        $simple_post_order    = get_post_meta($post->ID, 'simple_post_order', true);
        $simple_post_darkbox  = get_post_meta($post->ID, 'simple_post_darkbox', true);
        $simple_post_author   = get_post_meta($post->ID, 'simple_post_author', true);
        $sm_post_comment      = get_post_meta($post->ID, 'sm_post_comment', true);
        $sm_post_title        = get_post_meta($post->ID, 'sm_post_title', true);

        $post_types           = get_post_types(['public' => true], 'objects');


        // Remove the post types specified in $post_types_exclude
        foreach (self::$post_types_exclude as $post_type_exclude) {
            if (isset($post_types[$post_type_exclude])) {
                unset($post_types[$post_type_exclude]);
            }
        }

        $simple_order_by = [
            'none'          => 'None',
            'ID'            => 'ID',
            'author'        => 'Author',
            'title'         => 'Title',
            'date'          => 'Date',
            'modified'      => 'Modified',
            'parent'        => 'Parent',
            'rand'          => 'Random',
            'comment_count' => 'Comment Count',
            'menu_order'    => 'Menu Order',


        ];

        $simple_order = [
            'ASC'         => 'Ascending',
            'DESC'        => 'Descending',

        ];


        $categories = get_categories([
            'hide_empty'      => true,
        ]);

        $shortcode_atts = [
            'id'           => $post->ID,
            'title'        => $post->post_title,
            'sm_post_type' => $sm_post_type,
        ];

        if ($sm_as_gallery) {
            $shortcode_atts['gallery'] = 'yes';
        }

        if ($sm_category && $sm_category !== 'none') {
            $shortcode_atts['sm_category_name'] = $sm_category;
        }

        $shortcode = '[simple_masonry ' . $this->buildShortcodeAttributes($shortcode_atts) . ']';

        update_post_meta($post->ID, 'sm_shortcode', $shortcode);

        include_once SIMPLEMASONRYLAYOUT_DIR_PATH . 'views/metaboxes.php';
    }


    private function buildShortcodeAttributes($attributes)
    {
        $atts = [];
        foreach ($attributes as $key => $value) {
            $atts[] = $key . '="' . esc_attr($value) . '"';
        }
        return implode(' ', $atts);
    }


    public function saveCustomMetaboxData($post_id)
    {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if the user has permission to edit the post
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $post_type = get_post_type($post_id);

        // Check the post type
        if ($post_type !==  self::POST_TYPE) {
            return;
        }

        $post_title = get_post_field('post_title', $post_id);

        // Save your metabox data using update_post_meta()
        update_post_meta($post_id, 'sm_as_gallery', isset($_POST['sm_as_gallery']) ? 1 : 0);
        update_post_meta($post_id, 'sm_post_type', sanitize_text_field($_POST['sm_post_type'] ?? ''));
        update_post_meta($post_id, 'sm_category', sanitize_text_field($_POST['sm_category'] ?? ''));
        update_post_meta($post_id, 'simple_post_per_page', sanitize_text_field($_POST['simple_post_per_page'] ?? ''));
        update_post_meta($post_id, 'simple_post_orderby', sanitize_text_field($_POST['simple_post_orderby'] ?? ''));
        update_post_meta($post_id, 'simple_post_order', sanitize_text_field($_POST['simple_post_order'] ?? ''));
        update_post_meta($post_id, 'simple_post_darkbox', isset($_POST['simple_post_darkbox']) ? 1 : 0);
        update_post_meta($post_id, 'simple_post_author', isset($_POST['simple_post_author']) ? 1 : 0);
        update_post_meta($post_id, 'sm_post_comment', isset($_POST['sm_post_comment']) ? 1 : 0);
        update_post_meta($post_id, 'sm_post_title', isset($_POST['sm_post_title']) ? 1 : 0);

        if (empty($post_title)) {
            wp_update_post([
                'ID'         => $post_id,
                'post_title' => 'Untitled',
            ]);
        }
    }


    public function addSmShortcodeColumn($columns)
    {
        // Remove the "Date" column from the columns array
        $date_column = $columns['date'];
        unset($columns['date']);

        $columns['shortcode_column'] = 'Shortcode';

        // Add the "Date" column back at the end
        $columns['date'] = $date_column;
        return $columns;
    }


    public function populateSmShortcodeColumnData($column, $post_id)
    {
        if ($column === 'shortcode_column') {
            $shortcode = get_post_meta($post_id, 'sm_shortcode', true);
            echo '<span class="shortcode small-text code">' . esc_attr($shortcode) . '</span>';
        }
    }
}
