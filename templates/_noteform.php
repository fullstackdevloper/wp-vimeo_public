<div id="wp_vimeo_noteform" class="wp_vimeo_modal">
    <div class="wp_vimeo_modal_content">
        <a href="javascript:void();" class="wp_vimeo_close" onclick="wpVimeo.closepopup(this);">
            <svg width="12" height="12" viewport="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <line x1="1" stroke="#000" y1="11" x2="11" y2="1" stroke-width="2"></line>
                <line x1="1" y1="1" stroke="#000" x2="11" y2="11" stroke-width="2"></line>
            </svg>
        </a>
        <form class="wp_vimeo_noteform wp_vimeo_form" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <input type="hidden" name="action" value="wp_vimeo_addnote"/>
            <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
            <div class="wp_vimeo_row">
                <div class="wp_vimeo_note_title wp_vimeo_fieldwrap">
                    <input type="text" wp_vimeo_validation="required" placeholder="Title" name="wp_vimeo_note[title]" class="wp_vimeo_input">
                </div>
                <div class="wp_vimeo_note_date wp_vimeo_fieldwrap">
                    <input type="text" placeholder="Date" name="wp_vimeo_note[date]" class="wp_vimeo_input wp_vimeo_datepicker">
                </div>
            </div>
            <div class="wp_vimeo_row">
                <div class="wp_vimeo_note_content wp_vimeo_fieldwrap">
                    <?php wp_editor("", 'wp_vimeo_note_content', ['textarea_name' => 'wp_vimeo_note[content]', 'textarea_rows' => 10]); ?>
                </div>
            </div>
            <div class="wp_vimeo_row wp_vimeo_modal_btns">
				<div class="note_btn_left">
					<a class="wp_vimeo_btn" onclick="wpVimeo.noteTemplate('wp_vimeo_note_content');" href="javascript:void(0);"><?php _e('Note Template', 'wp-vimeo'); ?></a>
					<a class="wp_vimeo_btn" onclick="wpVimeo.lessonTemplate('wp_vimeo_note_content');" href="javascript:void(0);"><?php _e('Lesson Template', 'wp-vimeo'); ?></a>
					<a class="wp_vimeo_btn" onclick="wpVimeo.noteClear('wp_vimeo_note_content');" href="javascript:void(0);"><?php _e('clear', 'wp-vimeo'); ?></a>
				</div>
				<div class="note_btn_right">
					<a class="wp_vimeo_btn" onclick="wpVimeo.closepopup(this);" href="javascript:void(0);"><?php _e('Cancel', 'wp-vimeo'); ?></a>
					<button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue"><?php _e('Save', 'wp-vimeo'); ?></button>
				</div>

            </div>
        </form>
    </div>
</div>