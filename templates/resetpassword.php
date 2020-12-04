<div class="wp_vimeo_authentication_wrap">
    <div class="wp_vimeo_row">
         <div class="wp_vimeo_login_wrap wp_vimeo_col_12">
            <h2 class="wp_vimeo_login_text"><?php print apply_filters('wp-vimeo-login-text', __("Reset Password", 'wp_vimeo')); ?></h2>
			<div class="password_form">
            <p class="error_msg"><?php $this->displayFlash('wp-vimeo-reset-error'); ?></p>
            <form class="wp_vimeo_login_form wp_vimeo_form" id="wp_vimeo_reset_form" method="post">
                <input type="hidden" name="action" value="wp_vimeo_reset">
                <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
                <div class="wp_vimewo_fieldwrap">
                    <input type="text" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_reset[user_reset]" id="user_reset" placeholder="Username OR Email address"/>
                </div>
                <div class="wp_vimeo_row wp_vimeo_btns_wrap">
                    <button type="button" onclick="wpVimeo.resetpassword();" class="wp_vimeo_btn wp_vimeo_btn_blue wp_vimeo_btn_large"><?php _e('Reset', 'wp-vimeo'); ?></button>
                </div>
            </form>
			</div>
        </div>
    </div>
</div>