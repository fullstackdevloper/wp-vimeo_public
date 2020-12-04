<?php

class WpVimeoOptions {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    
    private $pages;
    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'wp_vimeo_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        
        $this->pages = get_pages();
    }

    /**
     * Add options page
     */
    public function wp_vimeo_plugin_page() {
        add_menu_page(
                'WP Vimeo', 'Wp Vimeo', 'manage_options', 'wp_vimeo_options', [$this, 'wp_vimeo_options_page'], WP_VIMEO_URL . '/admin/img/vimeo_icon.png'
        );
    }

    /**
     * Options page callback
     */
    public function wp_vimeo_options_page() {
        // Set class property
        $this->options = get_option('wp_vimeo_options');
        if(!is_array($this->options)) {
            $this->options = [];
        }
        echo '<div class="wrap">';
        echo wp_sprintf('<h1>%s</h1>', __('Wp Vimeo Settings', 'wp-vimeo'));
        echo '<form method="post" action="options.php">';
        // This prints out all hidden setting fields
        settings_fields('wp_vimeo_option_group');
        do_settings_sections('wp_vimeo_options');
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    /**
     * Register and add settings
     */
    public function page_init() {
        register_setting(
                'wp_vimeo_option_group', // Option group
                'wp_vimeo_options', // Option name
                [$this, 'sanitize'] // Sanitize
        );
        add_settings_section(
                'wp_vimeo_general', // ID
                'General Setting', // Title
                [$this, 'general_setting'], // Callback
                'wp_vimeo_options' // Page
        );
        add_settings_field(
                'wp_vimeo_api_client_id', __('Vimeo Client Id', 'activityhub'), [$this, 'wp_vimeo_api_client_id'], 'wp_vimeo_options', 'wp_vimeo_general'
        );
        add_settings_field(
                'wp_vimeo_api_client_secret', __('Vimeo Client Secret', 'activityhub'), [$this, 'wp_vimeo_api_secret'], 'wp_vimeo_options', 'wp_vimeo_general'
        );
        add_settings_field(
                'wp_vimeo_api_access_token', __('Vimeo Access Token', 'activityhub'), [$this, 'wp_vimeo_api_access_token'], 'wp_vimeo_options', 'wp_vimeo_general'
        );
        
        add_settings_field(
                'wp_vimeo_listing_page', __('Video Listing Page', 'kidcircle'), [$this, 'wp_vimeo_listing_page'], 'wp_vimeo_options', 'wp_vimeo_general'
        );
        
    }
    
    /**
     * Html option for My account redirect page
     */
    public function wp_vimeo_listing_page() {
        $pages = get_pages();
        ?>
        <select style="width:50%; min-width:300px;" name="wp_vimeo_options[listing_page]">
            <?php foreach ($pages as $key => $page): ?>
                <option <?php echo $this->displayValue('listing_page', true) == $page->ID ? 'selected' : ''; ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    
    /**
     * Vimeo client id Input box
     */
    public function wp_vimeo_api_client_id() {
        ?>
        <input type='text' style="width:50%; min-width:300px;" name='wp_vimeo_options[vimeo_client_id]' value='<?php $this->displayValue('vimeo_client_id'); ?>'>
        <?php
    }
    
    /**
     * Vimeo client secret Input box
     */
    public function wp_vimeo_api_secret() {
        ?>
        <input type='text' style="width:50%; min-width:300px;" name='wp_vimeo_options[vimeo_client_secret]' value='<?php $this->displayValue('vimeo_client_secret'); ?>'>
        <?php
    }
    
    /**
     * Vimeo client access token Input box
     */
    public function wp_vimeo_api_access_token() {
        ?>
        <input type='text' style="width:50%; min-width:300px;" name='wp_vimeo_options[vimeo_access_token]' value='<?php $this->displayValue('vimeo_access_token'); ?>'>
        <?php
    }
    
    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input) {
        return $input;
    }

    /**
     * Print the wp_vimeo api information
     */
    public function general_setting() {
        print sprintf('');
    }
    /**
     * display value from array
     * @param String $key
     * @param bolean $return
     * 
     * @return String value from options
     */
    private function displayValue($key, $return = false) {
        if (array_key_exists($key, $this->options)) {
            if ($return) {
                return $this->options[$key];
            }
            print $this->options[$key];
        }
    }
}

return new WpVimeoOptions();
