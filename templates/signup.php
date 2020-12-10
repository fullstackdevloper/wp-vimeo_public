<div class="wp_vimeo_authentication_wrap">
    <div class="wp_vimeo_row">
        <div class="wp_vimeo_register_wrap wp_vimeo_col_12">
            <h2 class="wp_vimeo_register_text"><?php print apply_filters('wp-vimeo-register-text', __("Create an Account", 'wp_vimeo')); ?></h2>
            <?php $this->displayFlash('wp-vimeo-register-error'); ?>
            <form class="wp_vimeo_register_form wp_vimeo_form" id="wp_vimeo_register_form" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
                <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
                <input type="hidden" name="action" value="wp_vimeo_register">
                <div class="wp_vimeo_row">
                    <div class="wp_vimeo_col_6">

                        <div class="wp_vimeo_fieldwrap">
                            <input type="text" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_register[name]" placeholder="First Name"/>
                        </div>

                        <div class="wp_vimeo_fieldwrap">
                            <input type="text" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_register[lastname]" placeholder="Last Name"/>
                        </div>

                        <div class="wp_vimeo_fieldwrap">
                            <input type="text" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_register[child_first_name]" placeholder="Child First Name"/>
                        </div>
                        <div class="wp_vimeo_fieldwrap">
                            <input type="text" placeholder="Child's Date of Birth" name="wp_vimeo_register[dob]" class="wp_vimeo_input wp_vimeo_datepicker">
                        </div>
                        <div class="wp_vimeo_fieldwrap">
                            <textarea rows="5" cols="50" name="wp_vimeo_register[description]" placeholder="Please tell us a bit about your child." class="wp_vimeo_input_textarea"></textarea>
                        </div>
                    </div>
                    <div class="wp_vimeo_col_6">
                        <div class="wp_vimeo_fieldwrap">
                            <input type="text" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_register[username]" placeholder="Username"/>
                        </div>

                        <div class="wp_vimeo_fieldwrap">
                            <input type="text" wp_vimeo_validation="email" class="wp_vimeo_input" name="wp_vimeo_register[email]" placeholder="Email Address"/>
                        </div>
                        <div class="wp_vimeo_fieldwrap">
                            <input type="password" id="wp_vimeo_password" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_register[password]" placeholder="Password"/>
                        </div>
                        <div class="wp_vimeo_fieldwrap">
                            <input type="password" wp_vimeo_validation="equalto" data-matching-field='#wp_vimeo_password' class="wp_vimeo_input" name="wp_vimeo_register[confirm_password]" placeholder="Re-enter Password"/>
                        </div>
                        <div class="wp_vimeo_fieldwrap">
                            <?php
                                global $wpVimeoSettings;
                                $googleRecaptchaKey = $wpVimeoSettings['google_recaptcha_key'];
                            ?>
                            <div class="g-recaptcha" data-sitekey="<?php _e($googleRecaptchaKey) ?>"></div>
                        </div>
                    </div>
                </div>



                <div class="wp_vimeo_row wp_vimeo_btns_wrap">
                    <button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue wp_vimeo_btn_large"><?php _e('Register', 'wp-vimeo'); ?></button>
                </div>
            </form>
        </div>

    </div>
</div>