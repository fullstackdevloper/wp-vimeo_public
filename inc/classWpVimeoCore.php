<?php

/*
 * Wp Vimeo core methods
 * @package wp-vimeo
 * @since   1.0.0
 */

class WpVimeoCore {

    /**
     * class constructor 
     * @global type $activityHubSetting
     */
    function __construct() {
        
    }

    /**
     * get the content from view file
     * @param String $viewname view file name
     * @param Array $data Data to send into view file
     * @throws ApiException on a non 2xx response
     * @return HTML
     */
    public function getView($viewname, array $data = []) {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }
        $user = wp_get_current_user()->data;
        global $wpVimeoSettings;
        
        ob_start();
        $viewpath = get_stylesheet_directory() . "/wp-vimeo/{$viewname}.php";
        if (!file_exists($viewpath)) {
            $viewpath = WP_VIMEO_ABSPATH . "templates/{$viewname}.php";
        }
        require($viewpath);
        $html = ob_get_clean();
        return $html;
    }

    /**
     * register custom post type for videos
     */
    public function registerVideoPostType() {
        $labels = array(
            'name' => _x('Videos', 'post type general name', 'wp-vimeo'),
            'singular_name' => _x('Video', 'post type singular name', 'wp-vimeo'),
            'menu_name' => _x('Videos/Notes', 'admin menu', 'wp-vimeo'),
            'name_admin_bar' => _x('Video', 'add new on admin bar', 'wp-vimeo'),
            'add_new' => _x('Add New', 'book', 'wp-vimeo'),
            'add_new_item' => __('Add New Video', 'wp-vimeo'),
            'new_item' => __('New Video', 'wp-vimeo'),
            'edit_item' => __('Edit Video', 'wp-vimeo'),
            'view_item' => __('View Video', 'wp-vimeo'),
            'all_items' => __('All Videos', 'wp-vimeo'),
            'search_items' => __('Search Video', 'wp-vimeo'),
            'parent_item_colon' => __('Parent Video:', 'wp-vimeo'),
            'not_found' => __('No Video found.', 'wp-vimeo'),
            'not_found_in_trash' => __('No Video found in Trash.', 'wp-vimeo')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'video'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor') // 
        );

        register_post_type('wp_vimeo_video', $args);
    }
    
    /**
     * get the vimeo posts
     * 
     * @param array $arg arguments
     * @return mixed
     */
    public function getVimeoPosts($arg = []) {
        global $current_user;
        $page = get_query_var('paged');
        $defaults = [
            'author'        =>  $current_user->ID,
            'numberposts' => 10,
            'orderby' => 'date',
            'post_type' => 'wp_vimeo_video',
            'paged' => $page
        ];
        $args = wp_parse_args( $arg, $defaults );
        
        return new WP_Query( $args );
        //return get_posts($args);
    }
    
    /**
     * get the max upload size on server
     * @staticvar type $max_size
     * @return Number max allowed uload size
     */
    public function getMaxUploadSize() {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = $this->parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size / (1024 * 1024);
    }
    
    /**
     * parse upload size
     * @param string $size
     * @return Numeric
     */
    public function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
    
    /**
     * set flash message to display error or success message
     * @param string $transient
     * @param mixed $value
     * @param Int $expiration
     */
    public function setFlash($transient, $value, $expiration = null) {
        set_transient( $transient, $value, $expiration );
    }
    
    /**
     * check if system has saved flash for a key
     * 
     * @param string $transient transiet ID
     * @return boolean
     */
    public function hasFlash($transient) {
        $message = get_transient($transient);
        
        return strlen($message) > 0;
    }
    /**
     * get the stored messages
     * @param type $transient
     * 
     * @return string message storred in cache
     */
    public function getFlash($transient) {
        $message = get_transient($transient);
        delete_transient($transient);
        
        return $message;
    }
    
    /**
     * dispay message string
     * @param string $transiet transiet key
     */
    public function displayFlash($transiet) {
        if($this->hasFlash($transiet)) {
            print wp_sprintf('<div class="wp_vimeo_error">%s</div>', $this->getFlash($transiet));
        }
    }
    
    /**
     * get the user dob in years
     * @return string
     */
    public function getUserAge() {
        $user = wp_get_current_user()->data;
        $dob = get_user_meta($user->ID, 'wp_vimeo_dob', true);
        $from = new DateTime($dob);
        $to   = new DateTime('today');
        return wp_sprintf('%s years old', $from->diff($to)->y);
    }
    
    /**
     * display pagination for wp vimeo posts
     * @param mixed $wp_query WP_query object $query
     * @param array $arguments
     */
    public function wpVimeoPagination($wp_query, $arguments) {
        /** Stop execution if there's only 1 page */
        if( $wp_query->max_num_pages <= 1 )
        return;

        $prev_arrow = is_rtl() ? 'next' : 'prev';
        $next_arrow = is_rtl() ? 'prev' : 'next';
        $total = $wp_query->max_num_pages;
        //$total="10";
        $big = 999999999; // need an unlikely integer
        if ($total > 1) {
            if (!$current_page = get_query_var('paged'))
                $current_page = 1;
            if (get_option('permalink_structure')) {
                $format = '/%#%/';
            } else {
                $format = '&paged=%#%';
            }
            echo $this->paginationLinks(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => $format,
                'current' => max(1, get_query_var('paged')),
                'total' => $total,
                'mid_size' => 3,
                'type' => 'list',
                'prev_text' => $prev_arrow,
                'next_text' => $next_arrow,
            ));
        }
    }
    
    public function paginationLinks($args = '') {
        $defaults = array(
            'base' => '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
            'format' => '?page=%#%', // ?page=%#% : %#% is replaced by the page number
            'total' => 1,
            'current' => 0,
            'show_all' => false,
            'prev_next' => true,
            'prev_text' => __('&laquo; Previous'),
            'next_text' => __('Next &raquo;'),
            'end_size' => 1,
            'mid_size' => 2,
            'type' => 'plain',
            'add_args' => false, // array of query args to add
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        );
        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);
        // Who knows what else people pass in $args
        $total = (int) $total;
        if ($total < 2)
            return;
        $current = (int) $current;
        $end_size = 0 < (int) $end_size ? (int) $end_size : 1; // Out of bounds? Make it the default.
        $mid_size = 0 <= (int) $mid_size ? (int) $mid_size : 2;
        $add_args = is_array($add_args) ? $add_args : false;
        $r = '';
        $page_links = array();
        $n = 0;
        $dots = false;
        $prev_link = '<p class="prev-div" ><span class="prev-click">prev</span></p>';
        if ($prev_next && $current && 1 < $current):
            $link = str_replace('%_%', 2 == $current ? '' : $format, $base);
        $link = str_replace('%#%', $current - 1, $link);
        if ($add_args)
            $link = add_query_arg($add_args, $link);

        $link.= $add_fragment;
        /**
         * Filter the paginated links for the given archive pages.
         *
         * @since 3.0.0
         *
         * @param string $link The paginated link URL.
         */
        $prev_link = '<a class="prev-div" href="'.esc_url(apply_filters('paginate_links', $link)).
        '">prev</a>';
        endif;
        for ($n = 1; $n <= $total; $n++):
            if ($n == $current):
                $page_links[] = "<a href='javascript:void(0);' class='page-numbers wp_vimeo_current_page'>".$before_page_number.number_format_i18n($n).$after_page_number.
            "</a>";
        $dots = true;
        else :
            if ($show_all || ($n <= $end_size || ($current && $n >= $current - $mid_size && $n <= $current + $mid_size) || $n > $total - $end_size)):
                $link = str_replace('%_%', 1 == $n ? '' : $format, $base);
        $link = str_replace('%#%', $n, $link);
        if ($add_args)
            $link = add_query_arg($add_args, $link);
        $link.= $add_fragment;
        /** This filter is documented in wp-includes/general-template.php */
        $page_links[] = "<a class='page-numbers' href='".esc_url(apply_filters('paginate_links', $link)).
        "'>".$before_page_number.number_format_i18n($n).$after_page_number.
        "</a>";
        $dots = true;
        elseif($dots && !$show_all):
            $page_links[] = '<span class="page-numbers dots">'.__('&hellip;').
        '</span>';
        $dots = false;
        endif;
        endif;
        endfor;
        $next_link = '<p class="next-div" >next</p>';
        if ($prev_next && $current && ($current < $total || -1 == $total)):
            $link = str_replace('%_%', $format, $base);
        $link = str_replace('%#%', $current + 1, $link);
        if ($add_args)
            $link = add_query_arg($add_args, $link);
        $link.= $add_fragment;
        /** This filter is documented in wp-includes/general-template.php */
        //$page_links[] = '<a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $next_text . '</a>';
        $next_link = '<a class="next-div" href="'.esc_url(apply_filters('paginate_links', $link)).
        '">next</a>';
        endif;
        switch ($type):
        case 'array':
            return $page_links;
            break;
        case 'list':
            $r.= '<div class="pagination">';
            $r.= $prev_link;
            $r.= "<ul class='page-numbers'>\n\t<li>";
            $r.= join("</li>\n\t<li>", $page_links);
            $r.= "</li>\n</ul>\n";
            $r.= $next_link;
            $r.= '<div class="clear"></div></div>';
            break;
        default:
            $r = join("\n", $page_links);
            break;
            endswitch;
            return $r;
    }	/**	 * Gets a vimeo thumbnail url	 * @param mixed $id A vimeo id (ie. 1185346)	 * @return thumbnail's url	*/	function getVimeoThumb($id) {		$data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");		$data = json_decode($data);		return $data[0]->thumbnail_medium;	}
	
	/**
	 * view all video OR notes
     * filter the videos and notes
     */
    public function viewFilter($arg=[]) {
        $postData = $arg;
        $arguments = [];
        $sortBy = $postData['sort_by'];
        $filterBy = $postData['filter_by'];
        $filter_bytag = $postData['filter_bytag'];
        $keyword = $postData['key'];
		
        if(strlen($keyword)) {
            $arguments['s'] = $keyword;
        }
        /* filter post data */
        if($filterBy) {
            if($filterBy == 'videos') {
                $arguments['meta_query'] = [
                    [   
                        'key' => 'wp_vimeo_id', 
                        'compare' => 'EXISTS'
                    ]
                ];
            }
            if($filterBy == 'notes') {
                $arguments['meta_query'] = [
                    [   
                        'key' => 'wp_vimeo_id', 
                        'compare' => 'NOT EXISTS'
                    ]
                ];
            }
        }
        if($sortBy) {
            if($sortBy == 'latest') {
                $arguments['orderby'] = 'post_date';
                $arguments['order'] = 'ASC'; 
            }
            
            if($sortBy == 'oldest') {
                $arguments['orderby'] = 'post_date';
                $arguments['order'] = 'DESC'; 
            }
        }
        
        $content = WpVimeo()->engine->getView('_listing', ['arg' => $arguments]);

        return $content;
    }
}
