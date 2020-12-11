<div id="wp_vimeo_gallery">
    <div class='row'>
        <div class='col-md-12'>
            <div class="carousel slide media-carousel" id="media">
                <div class="carousel-inner">
                    <?php
                        $subscribers = get_users([ 'role__in' => [ 'author', 'subscriber' ] ] );
                        $i = 1;

                        foreach ($subscribers as $subscriber) {
                            $user_link = get_avatar_url( $subscriber->data->ID,960);
                            $user_thumb_link = get_avatar_url( $subscriber->data->ID,96);
							$child_first_name = get_user_meta($subscriber->data->ID, 'child_first_name', true);
                            
							if ($i == 1) {
                    ?>
                    <div class="item active">
                        <div class="row gallery_slide">
                    <?php
                        }
                    ?>
                            <div class="col-md-4">
                                <a class="thumbnail" href="javascript:;">
                                    <img width="150" height="150"  alt="" src="<?php echo $user_thumb_link;?>">
                                </a>
                                <span><?php echo $child_first_name; ?></span>
                            </div>
                    <?php if ($i % 9==0 && count($subscribers) !=$i) { ?>
                        </div>
                    </div>
                    <div class="item">
                        <div class="row gallery_slide">
                    <?php } ?>

                    <?php if (count($subscribers) == $i) { ?>
                        </div>
                    </div>
                    <?php } ?>

                    <?php  $i++; } 	?>
                </div>
                <a data-slide="prev" href="#media" class="left carousel-control">‹</a>
                <a data-slide="next" href="#media" class="right carousel-control">›</a>
            </div>
        </div>
    </div>
</div>