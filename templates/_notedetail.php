<div id="note_readmore_popup" class="wp_vimeo_modal">
    <div class="wp_vimeo_modal_content">
        <a href="javascript:void();" class="wp_vimeo_close" onclick="wpVimeo.closepopup(this);">
            <svg width="12" height="12" viewport="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <line x1="1" stroke="#000" y1="11" x2="11" y2="1" stroke-width="2"></line>
                <line x1="1" y1="1" stroke="#000" x2="11" y2="11" stroke-width="2"></line>
            </svg>
        </a>
        <div class="wp_vimeo_col_12 testDetails">			<h3><?php echo $post->post_title; ?></h3>
            <div class="post_content"><?php echo wpautop($post->post_content); ?></div>
		</div>
    </div>
</div>