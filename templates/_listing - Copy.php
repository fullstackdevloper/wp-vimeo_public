<?php foreach($postData = $this->getVimeoPosts($arg) as $key => $post): ?>

<div class="wp_vimeo_single_post">

    <div class="wp_vimeo_post_header">

        <h3 class="wp_vimeo_title"><?php print $post->post_title; ?></h3>

        <p class="wp_vimeo_date"><?php echo get_the_date('', $post); ?></p>

    </div>

    <div class="wp_vimeo_content">

        <?php $videoLink = get_post_meta($post->ID, 'wp_vimeo_id', true); ?>
vimeo
        <?php if($videoLink): ?>

            <iframe src="<?php echo wp_sprintf('https://player.vimeo.com/video/%s', $videoLink); ?>" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>

<p>

        <?php else : ?>

            <p><?php print $post->post_content; ?></p>

        <?php endif; ?>

    </div>

    <div class="wp_vimeo_buttons wp_vimewo_fieldwrap">vimeo

        <a class="wp_vimeo_btn wp_vimeo_btn_black" onclick="wpVimeo.openModal('wp_vimeo_edit_video_<?php echo $post->ID; ?>');" href="javascript:void(0);"><?php _e('Edit', 'wp-vimeo'); ?></a>

        <div id="wp_vimeo_edit_video_<?php echo $post->ID; ?>" class="wp_vimeo_modal">
vimeo
            <div class="wp_vimeo_modal_content">

                <a href="javascript:void();" class="wp_vimeo_close" onclick="wpVimeo.closepopup(this);">

                    <svg width="12" height="12" viewport="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">

                        <line x1="1" stroke="#000" y1="11" x2="11" y2="1" stroke-width="2"></line>

                        <line x1="1" y1="1" stroke="#000" x2="11" y2="11" stroke-width="2"></line>

                    </svg>

                </a>

                <form class="wp_vimeo_videoform wp_vimeo_form" method="post" enctype="multipart/form-data" action="<?php echo admin_url( 'admin-post.php' ); ?>">

                    <input type="hidden" name="action" value="wp_vimeo_editvideo"/>

                    <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>

                    <input type="hidden" name="wp_vimeopost_id" value="<?php echo $post->ID; ?>"/>

                    <div class="wp_vimeo_row">

                        <div class="wp_vimeo_col_12 wp_vimeo_note_title wp_vimewo_fieldwrap">

                            <input type="text" placeholder="Title" wp_vimeo_validation="required" value="<?php echo $post->post_title; ?>" name="wp_vimeo_video[title]" class="wp_vimeo_input">

                        </div>

                    </div>

                    <div class="wp_vimeo_row">

                        <div class="wp_vimeo_col_12 wp_vimeo_note_title wp_vimewo_fieldwrap">

                            <input type="text" placeholder="video caption" wp_vimeo_validation="required" value="<?php echo $post->post_content; ?>" name="wp_vimeo_video[caption]" class="wp_vimeo_input">

                        </div>



                    </div>

                    <div class="wp_vimeo_row wp_vimeo_modal_btns">

                        <a class="wp_vimeo_btn" onclick="wpVimeo.closepopup(this);" href="javascript:void(0);"><?php _e('cancel', 'wp-vimeo'); ?></a>

                        <button type="submit" class="wp_vimeo_btn wp_vimeo_btn_blue"><?php _e('Update', 'wp-vimeo'); ?></button>

                    </div>

                </form>

            </div>

        </div>

        <a class="wp_vimeo_btn wp_vimeo_btn_red wp_vimeo_delete_video" href="javascript:void(0);"><?php _e('Delete', 'wp-vimeo'); ?></a>

        <form method="post" class="wp_vimeo_profile_delete_form" action="<?php echo admin_url( 'admin-post.php' ); ?>">

            <input type="hidden" name="action" value="wp_vimeo_delete_video"/>

            <input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>

            <input type="hidden" name="wp_vimeopost_id" value="<?php echo $post->ID; ?>"/>

        </form>

    </div>

</div>

<?php endforeach; ?>

<?php if(empty($postData)): ?>

<div class="wp_vimeo_nodata"><?php _e("No video/notes found. please try again or add some data", 'wp_vimeo'); ?></div>



<?php endif; ?>

