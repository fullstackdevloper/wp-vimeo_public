<?php
/*
 * Wp Vimeo Shortcodes
 * @package wp-vimeo
 * @since   1.0.0
 */

class WpVimeoShortcode
{

    /**
     * class constructor
     */
    public function __construct()
    {
        add_action('init', [$this, 'initShortcodes']);
    }

    /**
     * init all shortcodes for plugin
     */
    public function initShortcodes()
    {
        /**
         * Shortcode to display the list of videos uploaded in user account
         * [activityhub_sessions]
         */
        add_shortcode('wp-vimeo-listing', [$this, 'wpVimeoListing']);
        add_shortcode('wp-vimeo-signup', [$this, 'signupForm']);
        add_shortcode('wp-vimeo-login', [$this, 'loginForm']);
        add_shortcode('wp-vimeo-reset', [$this, 'resetForm']);
        add_shortcode('wp-vimeo-childGallery', [$this, 'childGallery']);

        /**
         * Shortcode to display the list of videos uploaded in user account
         * [activityhub_sessions]
         */
        add_shortcode('wp-vimeo-dashboard', [$this, 'wpVimeoDashboard']);
    }

    /**
     * wp vimeo shortcode to display the listing
     */
    public function wpVimeoListing()
    {
        // if user is logged in
        if (is_user_logged_in()) {
            $filterBy = filter_input(INPUT_GET, 'filterby', FILTER_SANITIZE_STRING);
            $arguments = [];
            if ($filterBy) {
                if ($filterBy == 'videos') {
                    $arguments['meta_query'] = [
                        [
                            'key' => 'wp_vimeo_id',
                            'compare' => 'EXISTS'
                        ]
                    ];
                }
                if ($filterBy == 'notes') {
                    $arguments['meta_query'] = [
                        [
                            'key' => 'wp_vimeo_id',
                            'compare' => 'NOT EXISTS'
                        ]
                    ];
                }
            }
            return WpVimeo()->engine->getView('videolisting', ['arg' => $arguments]);
        } else {
            // display a unauthentication message
            $html = wp_sprintf('<div class="wp_vimeo_auth_message">%s</div>', apply_filters('wp-vimeo-unauthentication-message', __('Please login to view videos.', 'wp-vimeo')));
            $html.= WpVimeo()->engine->getView('login');

            return $html;
        }
    }

    /**
     * Wp Vimeo shortcode to display the user dashboard
     */
    public function wpVimeoDashboard()
    {
        if (is_user_logged_in()) {
            return WpVimeo()->engine->getView('dashboard');
        } else {
            // display a unauthentication message
            $html = wp_sprintf('<div class="wp_vimeo_auth_message">%s</div>', apply_filters('wp-vimeo-unauthentication-message-dashboard', __('Please login to view your profile.', 'wp-vimeo')));
            $html.= WpVimeo()->engine->getView('login');

            return $html;
        }
    }

    /**
    * Wp Vimeo shortcode to display the user register form
    */
    public function signupForm()
    {
        if (is_user_logged_in()) {
            return WpVimeo()->engine->getView('dashboard');
        } else {
            /* display a unauthentication message*/
            $html = wp_sprintf('<div class="wp_vimeo_auth_message">%s</div>', apply_filters('wp-vimeo-unauthentication-message-dashboard', __('Please login to view your profile.', 'wp-vimeo')));
            $html.= WpVimeo()->engine->getView('signup');
            return $html;
        }
    }

    /**
    * Wp Vimeo shortcode to append user login form
    */
    public function loginForm()
    {
        if (is_user_logged_in()) {
            return WpVimeo()->engine->getView('dashboard');
        } else {
            /* display a unauthentication message*/
            $html = wp_sprintf('<div class="wp_vimeo_auth_message">%s</div>', apply_filters('wp-vimeo-unauthentication-message-dashboard', __('Please login to view your profile.', 'wp-vimeo')));
            $html.= WpVimeo()->engine->getView('login');
            return $html;
        }
    }

    /**
    * Wp Vimeo shortcode to reset password form
    */
    public function resetForm()
    {
        if (is_user_logged_in()) {
            return WpVimeo()->engine->getView('dashboard');
        } else {
            /* display a unauthentication message*/
            $html = wp_sprintf('<div class="wp_vimeo_auth_message">%s</div>', apply_filters('wp-vimeo-unauthentication-message-dashboard', __('Reset your password via username or email.', 'wp-vimeo')));
            $html.= WpVimeo()->engine->getView('resetpassword');
            return $html;
        }
    }

    /**
    * Wp Vimeo shortcode to display the children profile gallery
    */
    public function childGallery()
    {
        return WpVimeo()->engine->getView('childgallery');
    }
}

return new WpVimeoShortcode();
