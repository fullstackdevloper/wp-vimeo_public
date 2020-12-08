<?php $query = $this->getVimeoPosts($arg); ?>
<?php foreach ($query->posts as $key => $post): ?>
<div class="wp_vimeo_single_post">
    <div class="wp_vimeo_post_header">
        <h3 class="wp_vimeo_title"><?php print $post->post_title; ?></h3>
        <p class="wp_vimeo_date"><?php echo get_the_date('', $post); ?></p>
    </div>
    <div class="wp_vimeo_content wp_vimeo_row">
	<div class="wp_vimeo_col_12">
	<?php $videoLink = get_post_meta($post->ID, 'wp_vimeo_id', true); ?>
	<?php if ($videoLink): ?>
	<iframe src="<?php echo wp_sprintf('https://player.vimeo.com/video/%s', $videoLink); ?>" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
	<div class="text_center"><?php echo wpautop($post->post_content); ?></div>
	<?php $videoLink = get_post_meta($post->ID, 'tag_option', true);

        $video_tags = explode(",", $videoLink);

        // Hide if no tags
        if (empty($video_tags[0])) {
            echo '';
        } else {
            echo '<div class="video_tag">';
            foreach ($video_tags as $video_tag) {
                echo '<span class="tag_span">'.$video_tag.'</span>';
            }
            echo '</div>';
        }

    ?>
	<?php else : ?>
	<div class="text_center"><?php echo wpautop($post->post_content); ?></div>
	<?php endif; ?>
	</div>

    </div>
    <div class="wp_vimeo_buttons wp_vimeo_fieldwrap">
        <a class="wp_vimeo_btn wp_vimeo_btn_black" onclick="wpVimeo.openModal('wp_vimeo_edit_video_<?php echo $post->ID; ?>');" href="javascript:void(0);"><?php _e('Edit', 'wp-vimeo'); ?></a>
        <div id="wp_vimeo_edit_video_<?php echo $post->ID; ?>" class="wp_vimeo_modal">
            <div class="wp_vimeo_modal_content">
                <a href="javascript:void();" class="wp_vimeo_close" onclick="wpVimeo.closepopup(this);">
                    <svg width="12" height="12" viewport="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <line x1="1" stroke="#000" y1="11" x2="11" y2="1" stroke-width="2"></line>
                        <line x1="1" y1="1" stroke="#000" x2="11" y2="11" stroke-width="2"></line>
                    </svg>
                </a>
                <form class="wp_vimeo_videoform wp_vimeo_form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="wp_vimeo_editvideo"/>
                    <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
                    <input type="hidden" name="wp_vimeopost_id" value="<?php echo $post->ID; ?>"/>
                    <div class="wp_vimeo_row">
                        <div class="wp_vimeo_col_12 wp_vimeo_note_title wp_vimeo_fieldwrap">
							<label>Title</label>
                            <input type="text" placeholder="Title" wp_vimeo_validation="required" value="<?php echo $post->post_title; ?>" name="wp_vimeo_video[title]" class="wp_vimeo_input">
                        </div>
                    </div>
					<div class="wp_vimeo_row">
					<div class="wp_vimeo_col_8 wp_vimeo_note_title wp_vimeo_fieldwrap">
						<label><?php _e('Tags', 'wp-vimeo'); ?></label>
						<?php 
								$videovimotags = get_post_meta($post->ID, 'tag_option', true);
								$vimeo_tags = explode(",", $videovimotags);
								
								$complete_name = get_user_meta($user->ID, 'child_first_name', true).' '.get_user_meta($user->ID, 'vimeo_last_name', true);
						?>
						<select id="multi_tag" data-placeholder="Select multiple tags" name="wp_vimeo_video[tag_option][]" multiple class="wp_vimeo_input chosen-select">
						<option value="<?php print $complete_name; ?>" <?php if(in_array($complete_name,$vimeo_tags)){ echo 'selected="selected"';} ?>><?php print $complete_name; ?></option>
						
						<option value="Milestone" <?php if(in_array('Milestone',$vimeo_tags)){ echo 'selected="selected"';} ?>><?php _e('Milestone', 'wp-vimeo'); ?></option>
						</select>
					</div>
					<div class="wp_vimeo_col_4 wp_vimeo_note_title wp_vimeo_fieldwrap">
						<a href="javascript:;" class="deselect">Deselect All</a>
					</div>
					
					
                </div>
                    <div class="wp_vimeo_row">
                        <div class="wp_vimeo_note_content wp_vimeo_col_12 wp_vimeo_note_title wp_vimeo_fieldwrap">
                           	<label>Description</label>

						<div class="wp_vimeo_note_content wp_vimeo_fieldwrap">
							<?php wp_editor($post->post_content, 'wp_vimeo_note_content_edit_'.$post->ID, ['textarea_name' => 'wp_vimeo_video[caption]', 'textarea_rows' => 10, 'textarea_class'=>'wp_vimeo_input', 'textarea_wp_vimeo_validation'=>'required']); ?>
							</div>
                        </div>

                    </div>
                    <div class="wp_vimeo_row wp_vimeo_modal_btns">
                        <a class="wp_vimeo_btn" onclick="wpVimeo.closepopup(this);" href="javascript:void(0);"><?php _e('Cancel', 'wp-vimeo'); ?></a>
                        <button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue"><?php _e('Update', 'wp-vimeo'); ?></button>
                    </div>
                </form>
            </div>
        </div>
        <a class="wp_vimeo_btn wp_vimeo_btn_red wp_vimeo_delete_video" title="<?php if (get_post_meta($post->ID, 'wp_vimeo_id', true) !='') {
        echo 'Video';
    } else {
        echo 'Note';
    } ?>" href="javascript:void(0);"><?php _e('Delete', 'wp-vimeo'); ?></a>
        <form method="post" class="wp_vimeo_profile_delete_form" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="wp_vimeo_delete_video"/>
            <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
            <input type="hidden" name="wp_vimeopost_id" value="<?php echo $post->ID; ?>"/>
        </form>
    </div>
</div>
<?php endforeach; ?>
<?php $this->wpVimeoPagination($query, $arg); ?>
<?php if (!$query->found_posts): ?>
<div class="wp_vimeo_nodata"><?php _e("No videos or notes found. Please try again or add a note or a video to your records.", 'wp_vimeo'); ?></div>
<script>

jQuery(document).ready(function(){
	
	jQuery("#multi_tag").val().trigger("chosen:updated");
	
});

</script>
<?php endif; ?>
