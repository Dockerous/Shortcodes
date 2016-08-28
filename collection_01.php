<?php

if (!class_exists('DockerousShorcode01')) {

    class DockerousShorcode01 {

        public function __construct() {
            add_shortcode('list_child', array($this, 'list_child_pages'));
        }

        function list_child_pages($atts = array(), $content = null) {
            global $post;
            $a = shortcode_atts(array(
                'force_child' => '0',
                'sort_column' => 'menu_order, post_title',
                    ), $atts);
            $title_li = NULL;
            if (is_page() && $post->post_parent && $a['force_child'] != '1') {
                $child_of = $post->post_parent;
            } else {
                $child_of = $post->ID;
            }
            $r = array(
                'depth' => 0, 'show_date' => '',
                'date_format' => get_option('date_format'),
                'child_of' => $child_of, 'exclude' => '',
                'title_li' => $title_li, 'echo' => 0,
                'authors' => '', 'sort_column' => $a['sort_column'],
                'link_before' => '', 'link_after' => '', 'walker' => '',
            );

            $output = '';
            $current_page = 0;

            // sanitize, mostly to keep spaces out
            $r['exclude'] = preg_replace('/[^0-9,]/', '', $r['exclude']);

            // Allow plugins to filter an array of excluded pages (but don't put a nullstring into the array)
            $exclude_array = ( $r['exclude'] ) ? explode(',', $r['exclude']) : array();

            /**
             * Filters the array of pages to exclude from the pages list.
             *
             * @since 2.1.0
             *
             * @param array $exclude_array An array of page IDs to exclude.
             */
            $r['exclude'] = implode(',', apply_filters('wp_list_pages_excludes', $exclude_array));

            // Query pages.
            $r['hierarchical'] = 0;
            $pages = get_pages($r);

            if (!empty($pages)) {
                if ($r['title_li']) {
                    $output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';
                }
                global $wp_query;
                if (is_page() || is_attachment() || $wp_query->is_posts_page) {
                    $current_page = get_queried_object_id();
                } elseif (is_singular()) {
                    $queried_object = get_queried_object();
                    if (is_post_type_hierarchical($queried_object->post_type)) {
                        $current_page = $queried_object->ID;
                    }
                }
                if (empty(trim($content))) {
                    $output .= walk_page_tree($pages, $r['depth'], $current_page, $r);
                } else {
                    foreach ($pages as $page) {
                        $r1 = str_replace("%%title%%", $page->post_title, $content);
                        $r2 = str_replace("%%url%%", get_page_link($page->ID), $r1);
                        $output .= $r2;
                    }
                }

                if ($r['title_li']) {
                    $output .= '</ul></li>';
                }
            }
            if ($r['echo']) {
                echo $output;
            } else {
                return $output;
            }
        }

    }

}
    