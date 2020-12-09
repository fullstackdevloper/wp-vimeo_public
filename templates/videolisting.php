<div class="wp_vimeo_container">
    <div class="wp_vimeo_content">
        <div class="wp_vimeo_header">
			<div class="wp_vimeo_row">
				<h2 class="wp_vimeo_title"><?php print get_the_title() ?></h2>
			</div>
            <div class="wp_vimeo_row">
                <div class="wp_vimeo_search wp_vimeo_fieldwrap">
                    <input type="text" id="wp_vimeo_search" placeholder="Search" name="wp_vimeo_search" class="wp_vimeo_input">
                </div>
                <div class="wp_vimeo_buttons wp_vimeo_fieldwrap">
                    <a class="wp_vimeo_btn wp_vimeo_btn_blue" onclick="wpVimeo.openModal('wp_vimeo_noteform');" href="javascript:void(0);"><?php _e('Add Note', 'wp-vimeo'); ?></a>
                    <a class="wp_vimeo_btn wp_vimeo_btn_purple" onclick="wpVimeo.openModal('wp_vimeo_videoform');" href="javascript:void(0);"><?php _e('Add Video', 'wp-vimeo'); ?></a>
                    <?php print $this->getView('_videouploadform'); ?>
                    <?php print $this->getView('_noteform'); ?>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="wp_vimeo_sort col-3 wp_vimeo_fieldwrap">
                        <label><?php _e('Sort By', 'wp-vimeo'); ?></label>
                        <select name="wp_vimeo_sortby" id="wp_vimeo_sortby" class="wp_vimeo_input">
                            <option value="">-Sort By-</option>
                            <option value="latest"><?php _e('Newest', 'wp-vimeo'); ?></option>
                            <option value="oldest"><?php _e('Oldest', 'wp-vimeo'); ?></option>
                        </select>
                    </div>
                    <div id="" class="wp_vimeo_filter col-3 wp_vimeo_fieldwrap">

                        <label><?php _e('Filter By Type', 'wp-vimeo'); ?></label>
                        <select name="wp_vimeo_sortby" id="wp_vimeo_filterby" class="wp_vimeo_input">
                            <option value="">-Filter By Type-</option>
                            <option value="videos"><?php _e('Videos', 'wp-vimeo'); ?></option>
                            <option value="notes"><?php _e('Notes', 'wp-vimeo'); ?></option>
                        </select>

                    </div>

                    <div id="" class="wp_vimeo_filter col-3 wp_vimeo_fieldwrap">
                        <label><?php _e('Filter By Tag', 'wp-vimeo'); ?></label>
                        <select name="wp_vimeo_sortbytag[]" id="wp_vimeo_sortbytag" multiple class="wp_vimeo_input chosen-select">
                        <option value="">-Filter By Tag-</option>
                        <option value="<?php print get_user_meta($user->ID, 'child_first_name', true); ?> <?php print get_user_meta($user->ID, 'vimeo_last_name', true); ?>"><?php print get_user_meta($user->ID, 'child_first_name', true); ?> <?php print get_user_meta($user->ID, 'vimeo_last_name', true); ?></option>
                        <option value="milestone"><?php _e('Milestone', 'wp-vimeo'); ?></option>
                        </select>
                    </div>
                    <div id="" class="wp_vimeo_filter wp_vimeo_col_3 wp_vimeo_fieldwrap">
                    <a href="<?php echo home_url('/notes-and-videos/'); ?>" class="reset_filter"><?php _e('Reset Filters', 'wp-vimeo'); ?></a>

                    </div>
                </div>

            </div>
        </div>
        <div class="wp_vimeo_listing" id="wp_vimeo_listing">
            <?php $this->displayFlash('wp-vimeo-video-edit-error'); ?>
			<?php if(isset($_GET['filterby'])){
					$arg = array(
								"action"=>'wp_vimeo_filter',
								"key"=>'',
								"sort_by"=>'',
								"filter_bytag"=>''
								);
				   if($_GET['filterby'] == 'wp_vimeo_video'){
					   $arg['filter_by']='videos';
				   }
				   if($_GET['filterby'] == 'wp_vimeo_notes'){
					   $arg['filter_by']='notes';

				   }
				  print $this->viewFilter($arg);
				}else{
					print $this->getView('_listing', ['arg' => $arg]);
				}

			?>
        </div>
    </div>
</div>