    <div class="wp_vimeo_row">
         <div class="wp_vimeo_login_wrap wp_vimeo_col_12">
            <p><?php $this->displayFlash('wp-vimeo-newpass-error'); ?></p>
            <form class="wp_vimeo_login_form wp_vimeo_form" id="wp_vimeo_newpass_form" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
                <input type="hidden" name="action" value="wp_vimeo_newpassword">
                <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
                <input type="hidden" name="wp_vimeo_newpassword[username]" value="<?php echo $userfield; ?>"/>
                <div class="wp_vimeo_fieldwrap">
                    <input type="password" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_newpassword[user_newpass]" placeholder="New Password"/>
                </div>
				<div class="wp_vimeo_fieldwrap">
                    <input type="password" wp_vimeo_validation="required" class="wp_vimeo_input" name="wp_vimeo_newpassword[user_confirmpass]" placeholder="Confirm Password"/>
                </div>
                <div class="wp_vimeo_row wp_vimeo_btns_wrap">
                    <button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue wp_vimeo_btn_large"><?php _e('Submit', 'wp-vimeo'); ?></button>
                </div>
            </form>
        </div>
    </div>
