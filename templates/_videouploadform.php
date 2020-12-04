<div id="wp_vimeo_videoform" class="wp_vimeo_modal">
    <div class="wp_vimeo_modal_content">
        <a href="javascript:void();" class="wp_vimeo_close" onclick="wpVimeo.closepopup(this);">
            <svg width="12" height="12" viewport="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <line x1="1" stroke="#000" y1="11" x2="11" y2="1" stroke-width="2"></line>
                <line x1="1" y1="1" stroke="#000" x2="11" y2="11" stroke-width="2"></line>
            </svg>
        </a>
        <form class="wp_vimeo_videoform wp_vimeo_form" method="post" enctype="multipart/form-data" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <input type="hidden" name="action" value="wp_vimeo_addvideo"/>
            <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
            <div class="wp_vimeo_row">
                <div class="wp_vimeo_col_6 wp_vimeo_note_title wp_vimewo_fieldwrap">
                    <input type="text" placeholder="Title" wp_vimeo_validation="required" name="wp_vimeo_video[title]" class="wp_vimeo_input">
                </div>
                <div class="wp_vimeo_col_6 wp_vimeo_note_date wp_vimewo_fieldwrap">
                    <input type="text" placeholder="Date" name="wp_vimeo_video[date]" class="wp_vimeo_input wp_vimeo_datepicker">
                </div>
            </div>
            <div class="wp_vimeo_row">
                <div class="wp_vimeo_col_6 wp_vimeo_search wp_vimewo_fieldwrap">
                    <input type="file" name="wp_vimeo_video[file]" wp_vimeo_validation="required" class="wp_vimeo_file_input">
                </div>
                <div class="wp_vimeo_col_6 wp_vimeo_note_title wp_vimewo_fieldwrap">
                    <input type="text" placeholder="Video Caption" name="wp_vimeo_video[caption]" class="wp_vimeo_input">
                </div>
                
            </div>
            <div class="wp_vimeo_row">
                <span class="wp_vimeo_col_6 wp_vimeo_note_title wp_vimewo_fieldwrap"><?php _e('Upload a file with max size of', 'wp-vimeo'); ?> <?php echo $this->getMaxUploadSize(); ?> MB</span>
				
				<div class="wp_vimeo_col_6 wp_vimeo_note_title wp_vimewo_fieldwrap">
				<label><?php _e('Tags', 'wp-vimeo'); ?></label>
                    <select data-placeholder="Select multiple tags" name="wp_vimeo_video[milstone_option][]" multiple class="wp_vimeo_input chosen-select">
					<option value="<?php print get_user_meta($user->ID, 'child_first_name', true); ?> <?php print get_user_meta($user->ID, 'vimeo_last_name', true); ?>"><?php print get_user_meta($user->ID, 'child_first_name', true); ?> <?php print get_user_meta($user->ID, 'vimeo_last_name', true); ?></option>
					<option value="Milestone"><?php _e('Milestone', 'wp-vimeo'); ?></option>
					</select>
                </div>
				
            </div>
            <div class="wp_vimeo_row wp_vimeo_modal_btns">
                <a class="wp_vimeo_btn" onclick="wpVimeo.closepopup(this);" href="javascript:void(0);"><?php _e('cancel', 'wp-vimeo'); ?></a>
                <button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue"><?php _e('Save', 'wp-vimeo'); ?></button>
            </div>
        </form>
    </div>
</div>