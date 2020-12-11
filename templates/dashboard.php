<div class="wp_vimeo_dashboard">
    <div class="wp_vimeo_row wp_vimeo_profilehead">

        <div class="wp_vimeo_col_3 wp_vimeo_profile_pic">
            <div class="wp_vimeo_dp_wrap">
                <?php print get_avatar($user->ID); ?>
            </div>
            <div class="wp_vimeo_p_upload">
                <form method="post" class="wp_vimeo_profile_upload_form" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="wp_vimeo_upload_pic"/>
                    <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
                    <input type="file" style="display: none" id="wp_vimeo_profile_field" name="wp_vimeo_profile_pic">
                </form>
                <button type="submit" id="wp_vimeo_toggle_dp" class="wp_vimeo_btn wp_vimeo_btn_blue wp_vimeo_btn_large"><?php _e("Upload Child's Photo", 'wp-vimeo'); ?></button>
            </div>
        </div>
        <div class="wp_vimeo_col_9">
            <div class="wp_vimeo_profile_info">
                <a class="wp_vimeo_edit_profile" href="javascript:void(0);"></a>
                <span class="wp_vimeo_p_name"><?php print get_user_meta($user->ID, 'child_first_name', true); ?> <?php print get_user_meta($user->ID, 'vimeo_last_name', true); ?> </span>
                <span class="wp_vimeo_p_age"><?php print $this->getUserAge(); ?></span>
                <div class="wp_vimeo_bio_wrap">
                    <span class="wp_vimeo_p_bio_heading"><?php print apply_filters('wp-vimeo_bio_head', 'Bio'); ?></span>
                    <p class="wp_vimeo_p_bio_txt">
                        <?php print get_user_meta($user->ID, 'description', true); ?>
                    </p>
                </div>
            </div>
            <div class="wp_vimeo_profile_form">
                <form method="post" class="wp_vimeo_profile_upload_form" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                    <input type="hidden" name="action" value="wp_vimeo_update_info"/>
                    <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>

                    <div class="wp_vimeo_row">
                        <label>Child's Name</label>
                    </div>
					<div class="wp_vimeo_row">
                        <div class="wp_vimeo_col_12 wp_vimeo_note_title wp_vimeo_fieldwrap wp_vimeo_edit_bio_name">
                            <input type="text" wp_vimeo_validation="required" value="<?php print get_user_meta($user->ID, 'child_first_name', true); ?>" placeholder="Child First Name" name="wp_vimeo_profile[child_first_name]" class="wp_vimeo_input">
                            <input type="text" wp_vimeo_validation="required" value="<?php print get_user_meta($user->ID, 'vimeo_last_name', true); ?>" placeholder="Family Last Name" name="wp_vimeo_profile[lastname]" class="wp_vimeo_input">
                        </div>
                    </div>

                    <div class="wp_vimeo_row">
                        <label>Date of Birth</label>
                    </div>
					<div class="wp_vimeo_row wp_vimeo_edit_bio">
                        <div class="wp_vimeo_col_12 wp_vimeo_note_date wp_vimeo_fieldwrap">
                            <input type="text" placeholder="Child's Date of Birth" name="wp_vimeo_profile[dob]" value="<?php print get_user_meta($user->ID, 'wp_vimeo_dob', true); ?>" class="wp_vimeo_input wp_vimeo_datepicker">
                        </div>
                    </div>

                    <div class="wp_vimeo_row">
                        <label>Bio</label>
                    </div>
                    <div class="wp_vimeo_note_description wp_vimeo_fieldwrap">
                        <textarea rows="5" name="wp_vimeo_profile[description]" placeholder="Please tell us a bit about your child" class="wp_vimeo_input_textarea"><?php print get_user_meta($user->ID, 'description', true); ?></textarea>
                    </div>
                    <div class="wp_vimeo_row wp_vimeo_modal_btns">
                        <a class="wp_vimeo_btn wp_vimeo_cancel_profile_edit" href="javascript:void(0);"><?php _e('Cancel', 'wp-vimeo'); ?></a>
                        <button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue"><?php _e('Save', 'wp-vimeo'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="wp_vimeo_row wp_vimeo_profile_upload">
        <div class="wp_vimeo_col_3 wp_vimeo_upload_video">
            <h3 class="wp_vimeo_p_upload_txt"><?php print apply_filters('wp-vimeo-profile-video-heading', __('Upload Video', 'wp_vimeo')); ?></h3>
            <a class="wp_vimeo_btn wp_vimeo_btn_blue" onclick="wpVimeo.openModal('wp_vimeo_videoform');" href="javascript:void(0);"><?php _e('Upload Video', 'wp-vimeo'); ?></a>
            <?php print $this->getView('_videouploadform'); ?>
        </div>
        <div class="wp_vimeo_col_9 wp_vimeo_create_note">
            <h3 class="wp_vimeo_p_note_txt"><?php print apply_filters('wp-vimeo-profile-video-heading', __('Create Note', 'wp_vimeo')); ?></h3>
            <form class="wp_vimeo_noteform wp_vimeo_form" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                <input type="hidden" name="action" value="wp_vimeo_addnote"/>
                <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
				<div class="row">
				<div class="wp_vimeo_col_6 float_left">
					<div class="wp_vimeo_note_title wp_vimeo_fieldwrap">
                    <input type="text" wp_vimeo_validation="required" placeholder="Title" name="wp_vimeo_note[title]" class="wp_vimeo_input">
					</div>
				</div>
				<div class="wp_vimeo_col_6 float_left">
					<div class="wp_vimeo_note_date wp_vimeo_fieldwrap">
                    <input type="text" placeholder="Date" name="wp_vimeo_note[date]" class="wp_vimeo_input wp_vimeo_datepicker">
					</div>
				</div>
				</div>

                <div class="wp_vimeo_row">
                    <div class="wp_vimeo_note_content wp_vimeo_fieldwrap">
                        <?php wp_editor("", 'wp_vimeo_note_content', ['textarea_name' => 'wp_vimeo_note[content]', 'textarea_rows' => 10]); ?>
                    </div>
                </div>
                <div class="wp_vimeo_row wp_vimeo_dashboard_note">
                    <div class="note_btn_left">
                        <a class="wp_vimeo_btn" onclick="wpVimeo.noteTemplate('wp_vimeo_note_content');" href="javascript:void(0);"><?php _e('Note Template', 'wp-vimeo'); ?></a>
                        <a class="wp_vimeo_btn" onclick="wpVimeo.lessonTemplate('wp_vimeo_note_content');" href="javascript:void(0);"><?php _e('Lesson Template', 'wp-vimeo'); ?></a>
                        <a class="wp_vimeo_btn" onclick="wpVimeo.noteClear('wp_vimeo_note_content');" href="javascript:void(0);"><?php _e('Clear', 'wp-vimeo'); ?></a>
                    </div>
                    <div class="note_btn_right">
                        <button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue"><?php _e('Save', 'wp-vimeo'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="wp_vimeo_profile_videos">
        <div class="wp_vimeo_row wp_vimeo_p_v_head">
            <h3 class="wp_vimeo_heading wp_vimeo_col_10"><?php _e("Videos", 'wp_vimeo'); ?></h3>
            <a class="wp_vimeo_col_2" href="<?php print home_url('/notes-and-videos/').'?filterby=wp_vimeo_video'; ?>"><?php _e("view all videos", 'wp_vimeo'); ?></a>
        </div>
        <div class="wp_vimeo_slides wp_vimeo_slides_dashboard wp_vimeo_videos_wrap wp_vimeo_col_12">
            <?php $videoPosts = $this->getVimeoPosts(['meta_query' => [['key' => 'wp_vimeo_id', 'compare' => 'EXISTS']]]);?>
            <?php foreach ($videoPosts->posts as $key => $NotePost) : ?>
                <div class="wp_vimeo_single_slide">
                    <h5 class="wp_vimeo_slide_title"><?php print $NotePost->post_title;  ?></h5>
					<?php $thumlink = $this->getVimeoThumb(get_post_meta($NotePost->ID, 'wp_vimeo_id', true));
					?>
					<a href="javascript:;" onclick="wpVimeo.openVideoFrame(<?php print get_post_meta($NotePost->ID, 'wp_vimeo_id', true); ?>);">

					<img id="vimeo-<?php print get_post_meta($NotePost->ID, 'wp_vimeo_id', true); ?>" alt="Video Thumbnail" src="<?php echo $thumlink; ?>" />

					</a>
                    <!--<iframe src="https://player.vimeo.com/video/<?php print get_post_meta($NotePost->ID, 'wp_vimeo_id', true); ?>" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>-->
                </div>
            <?php endforeach; ?>
        </div>
        <?php if(!$videoPosts->found_posts): ?>
            <div class="wp_vimeo_nodata"><?php _e("No videos found. Please upload one.", 'wp_vimeo'); ?></div>
        <?php endif; ?>
    </div>
    <div class="wp_vimeo_profile_notes">
        <div class="wp_vimeo_row wp_vimeo_p_v_head">
            <h3 class="wp_vimeo_heading wp_vimeo_col_10"><?php _e("Notes", 'wp_vimeo'); ?></h3>
            <a class="wp_vimeo_col_2" href="<?php print home_url('/notes-and-videos/'.'?filterby=wp_vimeo_notes'); ?>"><?php _e("view all notes", 'wp_vimeo'); ?></a>
        </div>

        <div class="wp_vimeo_slides wp_vimeo_slides_dashboard wp_vimeo_notes_wrap wp_vimeo_col_12">
            <?php $notePosts = $this->getVimeoPosts(['meta_query' => [['key' => 'wp_vimeo_id', 'compare' => 'NOT EXISTS']]]) ?>
            <?php foreach ($notePosts->posts as $key => $NotePost) : ?>
                <div class="wp_vimeo_single_slide">
                    <h5 class="wp_vimeo_slide_title"><?php print $NotePost->post_title;  ?></h5>
                    <div class="wp_vimeo_slide_desc">
                        <?php print wpautop($NotePost->post_content); ?>
                    </div>
                    <a href="javascript:void(0);" onclick="wpVimeo.noteDescription(<?php echo $NotePost->ID; ?>);" class="wp_vimeo_slide_btn"><?php _e('read more', 'wp_vimeo'); ?></a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if(!$notePosts->found_posts): ?>
            <div class="wp_vimeo_nodata"><?php _e("No notes found. Please create one.", 'wp_vimeo'); ?></div>
        <?php endif; ?>
    </div>
</div>
<div id="wp_vimeo_video_view" class="wp_vimeo_modal">
    <div class="wp_vimeo_modal_view">
        <a href="javascript:void();" class="wp_vimeo_close" onclick="wpVimeo.closepopup('wp_vimeo_video_view');">
            <svg width="10" height="10" viewport="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <line x1="1" stroke="#000" y1="11" x2="11" y2="1" stroke-width="2"></line>
                <line x1="1" y1="1" stroke="#000" x2="11" y2="11" stroke-width="2"></line>
            </svg>
        </a>
        <div id="video_frame_append"></div>
    </div>
</div>