<div id="wp_vimeo_gallery">

	 <div class="wp_vimeo_slides wp_vimeo_child_gallery wp_vimeo_videos_wrap wp_vimeo_col_12">
            
             <?php
                        $subscribers = get_users([ 'role__in' => [ 'author', 'subscriber' ] ] );
                        $i = 1;

                        foreach ($subscribers as $subscriber) {
							
                            $user_thumb_link = get_avatar_url( $subscriber->data->ID,96);
							$child_first_name = get_user_meta($subscriber->data->ID, 'child_first_name', true);
                      if ($i == 1) {      
                    ?>
					<div class="wp_vimeo_single_slide">
					<?php
                        }
                    ?>
                
				<div class='child_gallery_h'>
					<div class='child_gallery_thr'>
					    <a class="thumbnail" href="javascript:;">
                                    <img width="150" height="150"  alt="" src="<?php echo $user_thumb_link;?>">
						</a>
						<span><?php echo $child_first_name; ?></span>
					</div>
										
					</div>
                    
					<?php if ($i % 9==0 && count($subscribers) !=$i) { ?>
                        </div>
                    <div class="wp_vimeo_single_slide">
                    <?php } ?>
                    
					<?php if (count($subscribers) == $i) { ?>
                        </div>
                    
                    <?php } ?>
                
						<?php $i++; }?>
        </div>	

  </div>  


