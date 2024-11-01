<?php

namespace SimpleMasonryLayout;

/**
 *
 *
 * This class consists of methods for scripts and styles enqueue, and shortcode to generate Simple Masonry Layout
 * @package    Simple Masonry Layout
 * @subpackage Simple Masonry Layout Frontend
 * @author Raju Tako
 */

class SimpleMasonryFront
{

    public static $instance = null;

    private function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'simpleMasonryEnqueueScripts']);
        add_shortcode("simple_masonry",  [$this, 'simpleMasonryShortcode']);
    }


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    //Enqueue script and style
    public function simpleMasonryEnqueueScripts()
    {
        wp_register_style('sm-style', SIMPLEMASONRYLAYOUT_DIR_URL . 'assets/css/sm-style.css');
        wp_register_style('darkbox-style', SIMPLEMASONRYLAYOUT_DIR_URL . 'assets/css/darkbox.css');
        wp_register_style('font-awesome', ("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"));
        wp_enqueue_style('sm-style');
        wp_enqueue_style('darkbox-style');
        wp_enqueue_style('font-awesome');
        wp_enqueue_script('jquery');
        wp_enqueue_script('modernizr-script');
        wp_enqueue_script('jquery-masonry');
        wp_register_script('modernizr-script', SIMPLEMASONRYLAYOUT_DIR_URL . 'assets/js/modernizr.custom.js', ['jquery'], '', false);
        wp_register_script('classie-script', SIMPLEMASONRYLAYOUT_DIR_URL . 'assets/js/classie.js', ['jquery'], '', true);
        wp_register_script('AnimOnScroll-script', SIMPLEMASONRYLAYOUT_DIR_URL . 'assets/js/AnimOnScroll.js', ['modernizr-script'], '', true);
        wp_register_script('main-script', SIMPLEMASONRYLAYOUT_DIR_URL . 'assets/js/main.js', ['AnimOnScroll-script'], '', true);
        wp_register_script('darkbox-script', SIMPLEMASONRYLAYOUT_DIR_URL . 'assets/js/darkbox.js', ['jquery'], '', true);
        wp_enqueue_script('classie-script');
        wp_enqueue_script('AnimOnScroll-script');
        wp_enqueue_script('main-script');
        wp_enqueue_script('darkbox-script');;
    }


    // pagination generation method
    public function simpleMasonryPagination($numpages = '', $pagerange = '', $paged = '')
    {

        if (empty($pagerange)) {
            $pagerange = 2;
        }

        /**
         * This first part of our function is a fallback
         * for custom pagination inside a regular loop that
         * uses the global $paged and global $wp_query variables.
         *
         * It's good because we can now override default pagination
         * in our theme, and use this function in default queries
         * and custom queries.
         */
        global $paged;

        if (empty($paged)) {

            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }

        if ($numpages == '') {
            global $wp_query;
            $numpages = $wp_query->max_num_pages;
            if (!$numpages) {
                $numpages = 1;
            }
        }

        /**
         * We construct the pagination arguments to enter into our paginate_links
         * function.
         */
        $pagination_args = [
            'base'            => get_pagenum_link(1) . '%_%',
            'format'          => 'page/%#%',
            'total'           => $numpages,
            'current'         => $paged,
            'show_all'        => False,
            'end_size'        => 1,
            'mid_size'        => $pagerange,
            'prev_next'       => True,
            'prev_text'       => __('<i class="fa fa-chevron-left"></i>'),
            'next_text'       => __('<i class="fa fa-chevron-right"></i>'),
            'type'            => 'plain',
            'add_args'        => false,
            'add_fragment'    => ''
        ];

        $paginate_links = paginate_links($pagination_args);

        if ($paginate_links) {
            echo "<nav class='sm-pagination'>";
            echo $paginate_links;
            echo "</nav>";
        }
    }


    //shortcode generation method
    public function simpleMasonryShortcode($atts, $content = null)
    {
        extract(shortcode_atts([
            'id'                  => 0,
            'sm_post_type'        => 'post',
            'gallery'             => 'no',
            'sm_category_name'    => ''

        ], $atts));

        if ('publish' !== get_post_status($id)) {
            return;
        }

        ob_start();

        $sm_darkbox_enable      = intval(get_post_meta($id, 'simple_post_darkbox', true));
        $sm_post_title_enable   = intval(get_post_meta($id, 'sm_post_title', true));
        $sm_post_author_enable  = intval(get_post_meta($id, 'simple_post_author', true));
        $sm_post_comment_enable = intval(get_post_meta($id, 'sm_post_comment', true));

        $paged                  = get_query_var('paged') ?: (get_query_var('page') ?: 1);

        $sm_args = [
            'posts_per_page'   => intval(get_post_meta($id, 'simple_post_per_page', true)),
            'orderby'          => sanitize_key(get_post_meta($id, 'simple_post_orderby', true)),
            'order'            => sanitize_key(get_post_meta($id, 'simple_post_order', true)),
            'post_type'        => $sm_post_type,
            'category_name'    => $sm_category_name,
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'paged'            => intval($paged),
            'meta_key'         => ($gallery == 'yes') ? '_thumbnail_id' : ''
        ];

        $wp_query = new \WP_Query($sm_args);

        include SIMPLEMASONRYLAYOUT_DIR_PATH . 'views/shortcode-template.php';

        return ob_get_clean();
    }
}
