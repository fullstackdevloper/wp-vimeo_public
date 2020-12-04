<div class="wp_vimeo_authentication_wrap">
    <div class="wp_vimeo_row">
         <div class="wp_vimeo_login_wrap wp_vimeo_col_12">
            <h2 class="wp_vimeo_login_text"><?php print apply_filters('wp-vimeo-login-text', __("Login with Username", 'wp_vimeo')); ?></h2>
            <?php $this->displayFlash('wp-vimeo-login-error'); ?>
            <form class="wp_vimeo_login_form wp_vimeo_form" id="wp_vimeo_login_form" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
                <input type="hidden" name="action" value="wp_vimeo_login">
                <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
                <div class="wp_vimewo_fieldwrap">
                    <input type="text" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_login[user_login]" placeholder="Username"/>
                </div>
                <div class="wp_vimewo_fieldwrap">
                    <input type="password" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_login[user_password]" placeholder="Password"/>
                </div>
                <div class="wp_vimeo_row wp_vimeo_btns_wrap">
                    <button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue wp_vimeo_btn_large"><?php _e('Login', 'wp-vimeo'); ?></button>
                </div>
				<div class="wp_vimewo_fieldwrap">
                    <a class="reset_password" href="<?php echo home_url(); ?>/reset-password"><?php _e('Reset Password', 'wp-vimeo'); ?></a>
					
					<a class="signup" href="<?php echo home_url(); ?>/signup"><?php _e('Create an account', 'wp-vimeo'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>