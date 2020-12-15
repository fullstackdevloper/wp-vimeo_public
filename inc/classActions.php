<?php

/*
 * Wp Vimeo actions
 * @package wp-vimeo
 * @since   1.0.0
 */

use Vimeo\Vimeo;
use Vimeo\Exceptions\VimeoUploadException;

class WpVimeoActions
{

    /**
     * WpVimeoActions Constructor.
     */
    public function __construct()
    {
        /**
         * post actions
         */
        foreach ($this->postActions() as $key => $action) {
            add_action("admin_post_nopriv_{$action['name']}", [$this, $action['callback']]);
            add_action("admin_post_{$action['name']}", [$this, $action['callback']]);
        }

        /**
         * ajax action
         */
        foreach ($this->ajaxActions() as $key => $action) {
            add_action("wp_ajax_{$action['name']}", [$this, $action['callback']]);
            add_action("wp_ajax_nopriv_{$action['name']}", [$this, $action['callback']]);
        }
    }

    /**
     * all post actions in plugin
     *
     * @return array an array of all the post actions
     */
    private function postActions()
    {
        return [
            ['name' => 'wp_vimeo_addnote', 'callback' => 'addNotePost'],
            ['name' => 'wp_vimeo_addvideo', 'callback' => 'uploadVideo'],
            ['name' => 'wp_vimeo_register', 'callback' => 'wpVimeoRegisterUser'],
            ['name' => 'wp_vimeo_login', 'callback' => 'wpVimeoLoginUser'],
            ['name' => 'wp_vimeo_upload_pic', 'callback' => 'wpVimeoUploadUserPic'],
            ['name' => 'wp_vimeo_update_info', 'callback' => 'wpVimeoUpdateUser'],
            ['name' => 'wp_vimeo_delete_video', 'callback' => 'deleteVideo'],
            ['name' => 'wp_vimeo_editvideo', 'callback' => 'updateVideo'],
            ['name' => 'wp_vimeo_newpassword', 'callback' => 'setPassword']
        ];
    }

    /**
     * get all the registered ajax actions
     * @return array set of ajax actions
     */
    private function ajaxActions()
    {
        return [
            ['name' => 'wp_vimeo_filter', 'callback' => 'processFilter'],
            ['name' => 'get_note_detail', 'callback' => 'noteDetail'],
            ['name' => 'wp_vimeo_reset', 'callback' => 'passwordReset']
        ];
    }

    /**
     * add vimeo notes in custom post type
     */
    public function addNotePost()
    {
        $postData = filter_input_array(INPUT_POST);

        if (array_key_exists('wp_vimeo_note', $postData)) {
            $title = $postData['wp_vimeo_note']['title'];
            $content = $postData['wp_vimeo_note']['content'];
            $date = $postData['wp_vimeo_note']['date'];
            $array = [
                'post_title' => $title,
                'post_content' => $content,
                'post_date' => $date,
                'post_status' => 'publish',
                'post_type' => 'wp_vimeo_video'
            ];

            //insert the post
            $postOutput = wp_insert_post($array);
            if ($postOutput) {
                wp_redirect($postData['redirect']);
                exit();
            }
        }
    }

    /**
     * process the video and upload to vimeo
     * @global type $wpVimeoSettings
     *
     * @return mixed response from API
     */
    public function uploadVideo()
    {
        global $wpVimeoSettings;

        $loginedin = get_current_user_id();

        $child_lname = get_user_meta($loginedin, 'child_first_name', true);

        $vimeo_folder_id = get_user_meta($loginedin, 'wp_vimeo_folder_id');

        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $video_pic = array();

        if (array_key_exists('wp_vimeo_video', $postData)) {
            $name = $postData['wp_vimeo_video']['title'];
            $caption = $postData['wp_vimeo_video']['caption'];
            $date = $postData['wp_vimeo_video']['date'];
            $tag_option = $postData['wp_vimeo_video']['tag_option'];

            $tag_string = implode(",", $tag_option);

            $file_name = $_FILES['wp_vimeo_video']['tmp_name']['file'];

            /* Instantiate the library with your client id, secret and access token (pulled from dev site)*/

            $lib = new Vimeo($wpVimeoSettings['vimeo_client_id'], $wpVimeoSettings['vimeo_client_secret'], $wpVimeoSettings['vimeo_access_token']);

            $uri = $lib->upload($file_name, array(
                'name' => $name,
                'description' => $caption,
                'privacy' => array("view"=>"anybody"),
            ));

            if ($uri) {
                $video_data = $lib->request($uri . '?fields=link');

                $video_id = str_replace('/videos/', '', $uri);

                if (isset($vimeo_folder_id[0])) {
                    $lib->request($vimeo_folder_id[0].'/videos/'.$video_id, '', 'PUT');

                    $video_pic = $lib->request('/videos/'.$video_id.'/pictures', '', 'POST');

                    foreach ($tag_option as $tag_op) {
                        $video_tag[] = ['name'=>ucwords($tag_op)];
                    }
                    $lib->request('/videos/'.$video_id.'/tags/', $video_tag, 'PUT');
                }

                $thum_url = home_url().'/wp-content/plugins/wp-vimeo/assets/img/profile_placeholder.png';

                foreach ($video_pic['body']['sizes'] as $thumbnail) {
                    if ($thumbnails['width']==200 && $thumbnails['height']==150) {
                        $thum_url = $thumbnails['link_with_play_button'];
                    }
                }

                $videoLink = $video_data['body']['link'];

                if ($videoLink) {
                    //save post in wordpress
                    $array = [
                        'post_title' => $name,
                        'post_content' => $caption,
                        'post_date' => $date,
                        'post_status' => 'publish',
                        'post_type' => 'wp_vimeo_video',
                        'meta_input' => [
                            'wp_vimeo_link' => $videoLink,
                            'wp_vimeo_short_link' => $uri,
                            'wp_vimeo_id' => $video_id,
                            'wp_vimeo_thumb' => $thum_url,
                            'tag_option' => $tag_string,
                        ]
                    ];

                    //insert the post
                    $postOutput = wp_insert_post($array);
                    if ($postOutput) {
                        wp_redirect($postData['redirect']);
                        exit();
                    } else {
                        //error to upload video
                        WpVimeo()->engine->setFlash('wp-vimeo-upload-error', __("Unable to create your video. Please try again"));
                        wp_redirect($postData['redirect']);
                        exit();
                    }
                } else {
                    WpVimeo()->engine->setFlash('wp-vimeo-upload-error', __("Unable to create your video. Please try again"));
                    wp_redirect($postData['redirect']);
                    exit();
                }
            } else {
                //error to upload video
                WpVimeo()->engine->setFlash('wp-vimeo-upload-error', __("Some error to upload your video. Please try again"));
                wp_redirect($postData['redirect']);
                exit();
            }
        } else {
            WpVimeo()->engine->setFlash('wp-vimeo-upload-error', __("Invalid Data. Please try again"));
            wp_redirect($postData['redirect']);
            exit();
        }
    }

    /**
     * Register a new user
     */
    public function wpVimeoRegisterUser()
    {
        global $wpVimeoSettings;
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (array_key_exists('wp_vimeo_register', $postData)) {
            $registerData = $postData['wp_vimeo_register'];

            $username = $registerData['username'];
            $password = $registerData['password'];
            $email = $registerData['email'];
            $description = $registerData['description'];
            $dob = $registerData['dob'];
            $displayName = $registerData['name'];
            $lastname = $registerData['lastname'];
            $child_first_name = $registerData['child_first_name'];

            if (username_exists($username) == null && email_exists($email) == false) {
                $user_id = wp_create_user($username, $password, $email);
                if (!is_wp_error($user_id)) {
                    wp_update_user(['ID' => $user_id, 'display_name' => $displayName.' '.$lastname]);
                    update_user_meta($user_id, 'vimeo_first_name', $displayName, false);
                    update_user_meta($user_id, 'vimeo_last_name', $lastname, false);
                    update_user_meta($user_id, 'description', $description, false);
                    update_user_meta($user_id, 'wp_vimeo_dob', $dob, false);
                    update_user_meta($user_id, 'child_first_name', $child_first_name, false);

                    $lib = new Vimeo($wpVimeoSettings['vimeo_client_id'], $wpVimeoSettings['vimeo_client_secret'], $wpVimeoSettings['vimeo_access_token']);

                    $user_vimeo_info = $lib->request('/me', '', 'GET');

                    if (isset($user_vimeo_info['body']['uri'])) {
                        $user_uri = $user_vimeo_info['body']['uri'];

                        $video_userfolder = $lib->request($user_uri.'/projects', ['name'=>$username], 'POST');

                        if (isset($video_userfolder['body']['uri'])) {
                            update_user_meta($user_id, 'wp_vimeo_folder_id', $video_userfolder['body']['uri'], false);
                        }
                    }
                    $credentials = [
                        'user_login' => $username,
                        'user_password' => $password
                        ];
                    $isloggedIn = wp_signon($credentials);

                    wp_redirect(home_url('/dashboard/'));
                    exit();
                } else {
                    $error_string = $user_id->get_error_message();
                    WpVimeo()->engine->setFlash('wp-vimeo-register-error', $error_string);
                    wp_redirect($postData['redirect']);
                    exit();
                }
            } else {
                WpVimeo()->engine->setFlash('wp-vimeo-register-error', __("Username or Email already exists."));
                wp_redirect($postData['redirect']);
                exit();
            }
        }
    }

    /**
     * login user with username/email and password
     */
    public function wpVimeoLoginUser()
    {
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (array_key_exists('wp_vimeo_login', $postData)) {
            $loginData = $postData['wp_vimeo_login'];
            $credentials = [
                'user_login' => $loginData['user_login'],
                'user_password' => $loginData['user_password']
            ];
            $isloggedIn = wp_signon($credentials);
            if (!is_wp_error($isloggedIn)) {
                wp_redirect(home_url('/dashboard/'));
                exit();
            } else {
                $error_string = $isloggedIn->get_error_message();
                WpVimeo()->engine->setFlash('wp-vimeo-login-error', $error_string);
                wp_redirect($postData['redirect']);
                exit();
            }
        }
    }

    /**
    * user password reset
    */
    public function passwordReset()
    {
        $credentials = filter_input(INPUT_POST, 'user_reset', FILTER_SANITIZE_STRING);

        if ($credentials !='') {
            if (username_exists($credentials) != null || email_exists($credentials) == true) {
                $content= WpVimeo()->engine->getView('newpassword', ['userfield' => $credentials]);
                wp_send_json(['status' => 'success', 'content' => $content]);
            } else {
                wp_send_json(['status' => 'fail', 'message' => 'User does not exist!']);
            }
        }
    }

    /**
    * user get new password
    */
    public function setPassword()
    {
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if (array_key_exists('wp_vimeo_newpassword', $postData)) {
            $loginData = $postData['wp_vimeo_newpassword'];

            $username = $loginData['username'];
            $confpass = $loginData['user_confirmpass'];
            $userinfo_name = get_user_by('login', $username);
            $userinfo_email = get_user_by('email', $username);


            if (isset($userinfo_name->ID) || isset($userinfo_email->ID)) {
                wp_set_password($confpass, $userinfo_name->ID);
                wp_redirect('/');
                exit();
            } else {
                WpVimeo()->engine->setFlash('wp-vimeo-newpass-error', __("User not found."));
                wp_redirect($postData['redirect']);
                exit();
            }
        }
    }
    /**
     * upload user Pic
     */
    public function wpVimeoUploadUserPic()
    {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $uploadedfile = $_FILES['wp_vimeo_profile_pic'];

        $upload_overrides = array(
            'test_form' => false
        );

        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            $url = $movefile['url'];
            $file = $movefile['file'];
            $filename = basename($url);
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment($attachment, $file);
            $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
            wp_update_attachment_metadata($attach_id, $attach_data);
            $user = wp_get_current_user();
            $userId = $user->data->ID;
            update_user_meta($userId, 'wp_vimeo_profile_attachment', $attach_id);
            wp_redirect($postData['redirect']);
            exit();
        } else {
            /*
             * Error generated by _wp_handle_upload()
             * @see _wp_handle_upload() in wp-admin/includes/file.php
             */
            WpVimeo()->engine->setFlash('wp-vimeo-login-error', $movefile['error']);
            wp_redirect($postData['redirect']);
            exit();
        }
    }

    /**
     * update user information
     */
    public function wpVimeoUpdateUser()
    {
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (array_key_exists('wp_vimeo_profile', $postData)) {
            $profileinfo = $postData['wp_vimeo_profile'];
            $dob = $profileinfo['dob'];
            $description = $profileinfo['description'];
            $vimeo_last_name = $profileinfo['lastname'];
            $child_first_name = $profileinfo['child_first_name'];
            $user = wp_get_current_user();
            $userId = $user->data->ID;
            update_user_meta($userId, 'description', $description, false);
            update_user_meta($userId, 'wp_vimeo_dob', $dob, false);
            update_user_meta($userId, 'child_first_name', $child_first_name, false);
            update_user_meta($userId, 'vimeo_last_name', $vimeo_last_name, false);

            wp_redirect($postData['redirect']);
            exit();
        }
    }

    /**
     * filter the videos and notes
     */
    public function processFilter()
    {
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $arguments = [];
        $sortBy = $postData['sort_by'];
        $filterBy = $postData['filter_by'];
        $filter_bytag = $postData['filter_bytag'];
        $keyword = $postData['key'];

        if (strlen($keyword)) {
            $arguments['s'] = $keyword;
        }
        /* filter post data */
        if ($filterBy) {
            if ($filterBy == 'videos') {
                $arguments['meta_query'] = [
                    [
                        'key' => 'wp_vimeo_id',
                        'compare' => 'EXISTS'
                    ]
                ];
            }
            if ($filterBy == 'notes') {
                $arguments['meta_query'] = [
                    [
                        'key' => 'wp_vimeo_id',
                        'compare' => 'NOT EXISTS'
                    ]
                ];
            }
        }
        /* filter post data by tag */
        if (is_array($filter_bytag) && count($filter_bytag)>0) {
            $arg_arr = array();
            if (count($filter_bytag)>1 && $filter_bytag[0] !='') {
                $arguments['meta_query'] = [
                                        'relation' => 'OR',
                                        array(
                                            'key' => 'tag_option',
                                             'value' => $filter_bytag[0],
                                             'compare' => 'LIKE',
                                        ),
                                        array(
                                            'key' => 'tag_option',
                                             'value' => $filter_bytag[1],
                                             'compare' => 'LIKE',
                                        )
                                       ];
            } elseif (count($filter_bytag)>0 && $filter_bytag[0] !='') {
                $arguments['meta_query'] = [
                                        'relation' => 'OR',
                                        array(
                                            'key' => 'tag_option',
                                             'value' => $filter_bytag[0],
                                             'compare' => 'LIKE',
                                        )
                                       ];
            }
        }
        /* sort by date */
        if ($sortBy) {
            if ($sortBy == 'latest') {
                $arguments['orderby'] = 'post_date';
                $arguments['order'] = 'ASC';
            }

            if ($sortBy == 'oldest') {
                $arguments['orderby'] = 'post_date';
                $arguments['order'] = 'DESC';
            }
        }

        $content = WpVimeo()->engine->getView('_listing', ['arg' => $arguments]);

        wp_send_json(['status' => 'success', 'content' => $content]);
    }


    /**
     * delete video/note from database and vimeo
     */
    public function deleteVideo()
    {
        $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $postId = $postData['wp_vimeopost_id'];
        if ($postId) {
            $post = get_post($postId);
            $postVideoId = get_post_meta($postId, 'wp_vimeo_id', true);
            if ($postVideoId) {
                //delete from vimeo
                global $wpVimeoSettings;
                $lib = new Vimeo($wpVimeoSettings['vimeo_client_id'], $wpVimeoSettings['vimeo_client_secret'], $wpVimeoSettings['vimeo_access_token']);
                $response = $lib->request('/videos/'.$postVideoId, [], 'DELETE');
            }
            wp_delete_post($postId);
            wp_redirect($postData['redirect']);
            exit();
        }
    }

    /**
     * update video
     */
    public function updateVideo()
    {
        $postData = filter_input_array(INPUT_POST);

        if (array_key_exists('wp_vimeo_video', $postData)) {
            $newTitle = $postData['wp_vimeo_video']['title'];
            $newDate = $postData['wp_vimeo_video']['date'];
            $newcaption = $postData['wp_vimeo_video']['caption'];
            $tag_option = $postData['wp_vimeo_video']['tag_option'];
            $postId = $postData['wp_vimeopost_id'];

            $post = get_post($postId);
            $postVideoId = get_post_meta($postId, 'wp_vimeo_id', true);
            if ($post) {
                if ($postVideoId) {
                    global $wpVimeoSettings;
                    $lib = new Vimeo($wpVimeoSettings['vimeo_client_id'], $wpVimeoSettings['vimeo_client_secret'], $wpVimeoSettings['vimeo_access_token']);

                    $response = $lib->request('/videos/'.$postVideoId, [
                        'description' => $newcaption,
                        'name' => $newTitle,
                    ], 'PATCH');

                    foreach ($tag_option as $tag_op) {
                        $lib->request('/videos/488470225/tags/'.$tag_op, '', 'DELETE');

                        $video_tag[] = ['name'=>ucwords($tag_op)];
                    }

                    $tag_remove = $lib->request('/videos/'.$postVideoId.'/tags/', $video_tag, 'PUT');

                    update_post_meta($postId, 'tag_option', implode(',', $tag_option));

                    if ($response['status'] == 200) {
                    } else {
                        WpVimeo()->engine->setFlash('wp-vimeo-video-edit-error', 'Some error to update video.Please try again');
                        wp_redirect($postData['redirect']);
                        exit();
                    }
                }
				$updatedate = date('Y-m-d H:i:s', strtotime($newDate));

                $isupdated = wp_update_post([
                    'ID' => $postId,
                    'post_title' => $newTitle,
                    'post_content' => $newcaption,
                    'post_date' => $updatedate,
                ]);
                if (is_wp_error($isupdated)) {
                    $error_string = $isupdated->get_error_message();
                }
                wp_redirect($postData['redirect']);
                exit();
            } else {
                WpVimeo()->engine->setFlash('wp-vimeo-video-edit-error', 'Some error to update video.Please try again');
                wp_redirect($postData['redirect']);
                exit();
            }
        }
    }

    /**
     * get single note detail
     */
    public function noteDetail()
    {
        $noteId = filter_input(INPUT_POST, 'note_id', FILTER_SANITIZE_STRING);
        $post = get_post($noteId);
        if ($post) {
            $content = WpVimeo()->engine->getView('_notedetail', ['post' => $post]);

            wp_send_json(['status' => 'success', 'content' => $content]);
        } else {
            $content = WpVimeo()->engine->getView('_listing', ['arg' => $arguments]);

            wp_send_json(['status' => 'fail', 'message' => 'invalid post']);
        }
    }
}

return new WpVimeoActions();
