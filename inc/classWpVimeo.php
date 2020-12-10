<?php

class WpVimeo {

    /**
     * WpVimeo version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class.
     *
     * @var WpVimeo
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Suffix for script i.e minified or not
     * @var $suffix String
     */
    private $suffix;

    /**
     * WpVimeo core functions
     *
     * @var WpVimeoCore
     * @since 1.0.0
     */
    public $engine;

    /**
     * Main WpVimeo Instance.
     *
     * Ensures only one instance of IsLayouts is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return WpVimeo.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * WpVimeo Constructor. 
     */
    function __construct() {
        global $wpVimeoSettings;
        $wpVimeoSettings = get_option('wp_vimeo_options', true);
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

        $this->engine = new WpVimeoCore();

        //$this->suffix = (defined('WP_DEBUG') && true === WP_DEBUG) ? "" : '.min';
    }

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     */
    private function init_hooks() {
        register_activation_hook(WP_VIMEO_PLUGIN_FILE, array($this, 'wp_vimeo_plugin_install'));
        add_action('init', array($this, 'init'), 0);

		add_action('init', array($this, 'remove_admin_bar'), 0);
        /* register front end scripts */
        add_action('wp_enqueue_scripts', array($this, 'wpVimeoScripts'), 0);

        /* register admin scripts */
        add_action('admin_enqueue_scripts', array($this, 'wpVimeoAdminScripts'), 0);

        // User Avatar override.
        add_filter('get_avatar', array($this, 'avatar_override'), 10, 6);
        add_filter('pre_get_avatar_data', array($this, 'pre_avatar_override'), 10, 2);
    }

    /*
     * WpVimeo instalation hook
     */

    public function wp_vimeo_plugin_install() {
        
    }

    /**
     * Init plugin when WordPress Initialises.
     */
    public function init() {
        $this->engine->registerVideoPostType();
    }

    /**
     * Define WpVimeo Constants.
     */
    private function define_constants() {
        $this->define('WP_VIMEO_ABSPATH', dirname(WP_VIMEO_PLUGIN_FILE) . '/');
        $this->define('WP_VIMEO_BASENAME', plugin_basename(WP_VIMEO_PLUGIN_FILE));
        $this->define('WP_VIMEO_URL', plugins_url(basename(WP_VIMEO_ABSPATH)));
        $this->define('WP_VIMEO_VERSION', $this->version.rand(1, 9999));
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        include_once WP_VIMEO_ABSPATH . '/vendor/autoload.php';
        include_once WP_VIMEO_ABSPATH . '/inc/classWpVimeoCore.php';
        include_once WP_VIMEO_ABSPATH . '/inc/classWpVimeoShortcodes.php';
        include_once WP_VIMEO_ABSPATH . '/inc/classActions.php';
        /* add admin files */
        if (is_admin()) {
            include_once WP_VIMEO_ABSPATH . '/admin/ClassAdminOptions.php';
        }
    }

    /**
     * register and enque front end styles and scripts.
     *
     * @since 1.0.0
     */
    public function wpVimeoScripts() {
        global $post;
        wp_register_script( 'wp_vimeo_slider', WP_VIMEO_URL . '/assets/js/slick.min.js', array( 'jquery' ), WP_VIMEO_VERSION, true );
			
		
        wp_enqueue_script('wp_vimeo_script', WP_VIMEO_URL . "/assets/js/wp-vimeo{$this->suffix}.js", array('jquery', 'jquery-ui-datepicker', 'wp_vimeo_slider'), WP_VIMEO_VERSION);
		
		wp_enqueue_script('wp_vimeo_chosen', WP_VIMEO_URL . "/assets/js/chosen.jquery.min.js", array('jquery'), WP_VIMEO_VERSION, true);
		
		wp_enqueue_script('wp_vimeo_gallery', WP_VIMEO_URL . "/assets/js/slider.js", array('jquery'), WP_VIMEO_VERSION, true);
		
		wp_enqueue_script('wp_vimeo_api', "https://www.google.com/recaptcha/api.js", array('jquery'), WP_VIMEO_VERSION, true);
	
		wp_register_style( 'wp_vimeo_slider', WP_VIMEO_URL . '/assets/css/slick.min.css', false, WP_VIMEO_VERSION);
		
        wp_register_style( 'wp_vimeo_slider_theme', WP_VIMEO_URL . '/assets/css/slick-theme.css', false, WP_VIMEO_VERSION);
        
		wp_enqueue_style('e2b-admin-ui-css', WP_VIMEO_URL.'/assets/css/jquery-ui.css', false, WP_VIMEO_VERSION, false);
		
		wp_enqueue_style('wp_vimeo_slider_chosen', WP_VIMEO_URL.'/assets/css/chosen.min.css', false, WP_VIMEO_VERSION, false);
		
		wp_enqueue_style('wp_vimeo_gallery', WP_VIMEO_URL.'/assets/css/slide.css', false, WP_VIMEO_VERSION, false);
		wp_enqueue_style('wp_vimeo_lightgallery', WP_VIMEO_URL.'/assets/css/lightgallery.css', false, WP_VIMEO_VERSION, false);
		
        wp_enqueue_style('wp_vimeo_style', WP_VIMEO_URL . "/assets/css/wp-vimeo{$this->suffix}.css", array('wp_vimeo_slider', 'wp_vimeo_slider_theme'), WP_VIMEO_VERSION);
        if(isset($post->ID)){
			  wp_localize_script('wp_vimeo_script', 'wp_vimeo', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                "permalink" => get_permalink($post->ID),
                '_wpnonce' => wp_create_nonce('km_nonce_' . $post->ID),
				)
			);
		}
      
    }

    public function wpVimeoAdminScripts() {
        wp_enqueue_script('wp_vimeo_admin_script', WP_VIMEO_URL . '/admin/js/wp-vimeo.js', array('jquery'), WP_VIMEO_VERSION);
        wp_enqueue_style('wp_vimeo_admin_style', WP_VIMEO_URL . '/admin/css/wp-vimeo.css', array(), WP_VIMEO_VERSION);
    }

    /**
     * Define constant if not already set.
     *
     * @param string      $name  Constant name.
     * @param string|bool $value Cnstantonstant value.
     */
    private function define($name, $value) {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Override an Avatar with a User Profile Picture.
     *
     * Overrides an avatar with a profile image
     *
     * @param string $avatar SRC to the avatar.
     * @param mixed  $id_or_email The ID or email address.
     * @param int    $size Size of the image.
     * @param string $default URL to the default image.
     * @param string $alt Alternative text.
     * @param array  $args Misc. args for the avatar.
     *
     * @return string Avatar.
     */
    public function avatar_override($avatar, $id_or_email, $size, $default, $alt, $args = array()) {
        global $pagenow;
        if ('options-discussion.php' === $pagenow) {
            return $avatar; // Stop overriding gravatars on options-discussion page.
        }

        // Get user data.
        if (is_numeric($id_or_email)) {
            $user = get_user_by('id', (int) $id_or_email);
        } elseif (is_object($id_or_email)) {
            $comment = $id_or_email;
            if (empty($comment->user_id)) {
                $user = get_user_by('id', $comment->user_id);
            } else {
                $user = get_user_by('email', $comment->comment_author_email);
            }
            if (!$user) {
                return $avatar;
            }
        } elseif (is_string($id_or_email)) {
            $user = get_user_by('email', $id_or_email);
        } else {
            return $avatar;
        }

        if (!$user) {
            return $avatar;
        }
        $user_id = $user->ID;
        
        // Build classes array based on passed in args, else set defaults - see get_avatar in /wp-includes/pluggable.php.
        $classes = array(
            'avatar',
            sprintf('avatar-%s', esc_attr($size)),
            'photo',
        );
        if (isset($args['class'])) {
            if (is_array($args['class'])) {
                $classes = array_merge($classes, $args['class']);
            } else {
                $args['class'] = explode(' ', $args['class']);
                $classes = array_merge($classes, $args['class']);
            }
        }

        // Get custom filter classes.
        $classes = (array) apply_filters('wp_vimeo_avatar_classes', $classes);

        // Determine if the user has a profile image.
        $custom_avatar = $this->wpVimeoProfilePic(
                $user_id, array(
                    'size' => array($size, $size),
                    'attr' => array(
                        'alt' => $alt,
                        'class' => implode(' ', $classes),
                    ),
                    'echo' => false,
                )
        );
        //print_r($custom_avatar); die;
        if (!$custom_avatar) {
            return $avatar;
        }
        return $custom_avatar;
    }

    /**
     * Overrides an avatar with a profile image
     *
     * @param array $args Arguments to determine the avatar dimensions.
     * @param mixed $id_or_email The ID or email address.
     *
     * @return array $args Overridden URL or default if none can be found
     * */
    public function pre_avatar_override($args, $id_or_email) {
        
        // Get user data.
        if (is_numeric($id_or_email)) {
            $user = get_user_by('id', (int) $id_or_email);
        } elseif (is_object($id_or_email)) {
            $comment = $id_or_email;
            if (empty($comment->user_id)) {
                $user = get_user_by('id', $comment->user_id);
            } else {
                $user = get_user_by('email', $comment->comment_author_email);
            }
            if (!$user) {
                return $args;
            }
        } elseif (is_string($id_or_email)) {
            $user = get_user_by('email', $id_or_email);
        } else {
            return $args;
        }
        if (!$user) {
            return $args;
        }
        $user_id = $user->ID;

        // Get the post the user is attached to.
        $size = $args['size'];

        $profile_post_id = absint(get_user_option('wp_vimeo_profile_attachment', $user_id));
        if (0 === $profile_post_id) {
            return $args;
        }
        $avatar_image = wp_get_attachment_url($profile_post_id);
        /*$post_thumbnail_id = get_post_thumbnail_id($profile_post_id);

        // Attempt to get the image in the right size.
        $avatar_image = get_the_post_thumbnail_url($profile_post_id, array($size, $size));
        if (empty($avatar_image)) {
            return $args;
        }*/
        $args['url'] = $avatar_image;
        
        return $args;
    }
    
    /**
    * Template tag for outputting a profile image.
    *
    * @param int   $user_id The user ID for the user to retrieve the image for.
    * @param mixed $args    Arguments for custom output.
    *   size - string || array (see get_the_post_thumbnail).
    *   attr - string || array (see get_the_post_thumbnail).
    *   echo - bool (true or false) - whether to echo the image or return it.
    */
    public function wpVimeoProfilePic( $user_id, $args = array() ) {
           $profile_post_id = absint( get_user_option( 'wp_vimeo_profile_attachment', $user_id ) );
           
           $defaults = array(
                   'size' => 'thumbnail',
                   'attr' => '',
                   'echo' => true,
           );
           $args     = wp_parse_args( $args, $defaults );
           extract( $args ); // phpcs:ignore
           //echo wp_get_attachment_url($profile_post_id);die;
           $post_thumbnail_id = get_post_thumbnail_id( $profile_post_id );
           //echo $post_thumbnail_id; die;
           // Return false or echo nothing if there is no post thumbnail.
           if ( ! $post_thumbnail_id ) {
                   if ( $echo ) {
                           echo '';
                   } else {
                           return false;
                   }
                   return;
           }

           // Implode Classes if set and array - dev note: edge case.
           if ( is_array( $attr ) && isset( $attr['class'] ) ) {
                   if ( is_array( $attr['class'] ) ) {
                           $attr['class'] = implode( ' ', $attr['class'] );
                   }
           }

           $post_thumbnail = wp_get_attachment_image( $profile_post_id, $size, false, $attr );

           /**
            * Filter outputted HTML.
            *
            * Filter outputted HTML.
            *
            * @param string $post_thumbnail       img tag with formed HTML.
            * @param int    $profile_post_id      The profile in which the image is attached.
            * @param int    $profile_thumbnail_id The thumbnail ID for the attached image.
            * @param int    $user_id              The user id for which the image is attached.
            */
           $post_thumbnail = apply_filters( 'wp_vimeo_userpic_html', $post_thumbnail, $profile_post_id, $post_thumbnail_id, $user_id );
           if ( $echo ) {
                   echo wp_kses_post( $post_thumbnail );
           } else {
                   return $post_thumbnail;
           }
   }   /**   * hide admin bar   */   public function remove_admin_bar() {			show_admin_bar(false);		}
}
