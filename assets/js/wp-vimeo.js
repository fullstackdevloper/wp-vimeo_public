/*
 *
 * activityhub front end Javascript
 *
 * @since 1.0.0
 *
 */
var wpVimeo;
(function ($) {
  var $this;
  wpVimeo = {
    settings: {},
    initialize: function () {
      $this = wpVimeo;
      $(document).ready(function () {
        $this.onInitMethods();
      });
    },
    onInitMethods: function () {
      $this.initDatePicker();
      $this.initValidation();
      $this.Events();
      $this.initSlickSlider();
      $this.initSlickSliderDashboard();
    },
    Events: function () {
      $(document).on("change", "#wp_vimeo_profile_field", function () {
        $(this).parents("form").submit();
      });

      $(document).on("click", "#wp_vimeo_toggle_dp", function () {
        $("#wp_vimeo_profile_field").click();
      });

      $(document).on("click", ".wp_vimeo_edit_profile", function () {
        $(".wp_vimeo_profile_info").hide();
        $(".wp_vimeo_profile_form").show();
      });

      $(document).on("click", ".wp_vimeo_cancel_profile_edit", function () {
        $(".wp_vimeo_profile_info").show();
        $(".wp_vimeo_profile_form").hide();
      });

      $(document).on(
        "change",
        "#wp_vimeo_filterby, #wp_vimeo_sortby, #wp_vimeo_sortbytag",
        function () {
          $this.processFilters();
        }
      );

      $(document).on("keyup", "#wp_vimeo_search", function (event) {
        let typingTimer;
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function () {
          $this.processFilters();
        }, 2000);
      });

      $(document).on("click", ".wp_vimeo_delete_video", function () {
        var tit = $(this).attr("title");
        if (confirm("Are you sure to delete this " + tit + "?")) {
          $(this).next("form").submit();
        }
      });
    },
    processFilters: function () {
      let isProcessing = false;
      if (isProcessing === false) {
        isProcessing = true;
        //formData.set('action', 'km_reset_password');
        let postData = {
          action: "wp_vimeo_filter",
          key: $("#wp_vimeo_search").val(),
          sort_by: $("#wp_vimeo_sortby").val(),
          filter_by: $("#wp_vimeo_filterby").val(),
          filter_bytag: $("#wp_vimeo_sortbytag").val(),
        };

        $this.ajaxCall(wp_vimeo.ajax_url, postData, function (response) {
          if (response.status == "success") {
            $("#wp_vimeo_listing").html(response.content);
          } else if (response.status == "fail") {
            alert(response.message);
          }

          isProcessing = false;
        });
      }
    },
    initDatePicker: function () {
      $(".wp_vimeo_datepicker").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        yearRange: "1980:c",
      });
    },
    initSlickSliderDashboard: function () {
      $(".wp_vimeo_slides_dashboard").slick({
        dots: false,
        arrows: true,
        infinite: false,
        cssEase: "linear",
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
          {
              breakpoint: 1024,
              settings: {
                  slidesToShow: 3,
                  slidesToScroll: 3,
              }
          },
          {
              breakpoint: 600,
              settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
          },
          {
              breakpoint: 480,
              settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
              }
          }
      ]
      });
    },
    initSlickSlider: function () {
      $(".wp_vimeo_slides_desktop").slick({
        dots: false,
        arrows: true,
        infinite: false,
        cssEase: "linear",
        slidesToShow: 3,
        slidesToScroll: 1,
      });
    },
    initSlickSliderMobile: function () {
      $(".wp_vimeo_slides_mobile").slick({
        dots: false,
        arrows: true,
        infinite: false,
        cssEase: "linear",
        slidesToShow: 1,
        slidesToScroll: 1,
      });
    },
    initSlickSlider: function () {
      $(".wp_vimeo_child_gallery").slick({
        dots: false,
        arrows: true,
        infinite: false,
        cssEase: "linear",
        slidesToShow: 1,
        slidesToScroll: 1,
		responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }

		]
      });
    },
    noteDescription: function (noteId) {
      $this.ajaxCall(
        wp_vimeo.ajax_url,
        { action: "get_note_detail", note_id: noteId },
        function (response) {
          if (response.status == "success") {
            if ($("#note_readmore_popup").length) {
              $("#note_readmore_popup").remove();
            }

            $("body").append(response.content);
            $this.openModal("note_readmore_popup");
          } else if (response.status == "fail") {
            alert(response.message);
          }

          isProcessing = false;
        }
      );
    },
    resetpassword: function () {
      isProcessing = false;
      var username = $("#user_reset").val();
      if (username == "") {
        $(".error_msg").html("This is required!");
      }
      $this.ajaxCall(
        wp_vimeo.ajax_url,
        { action: "wp_vimeo_reset", user_reset: username },
        function (response) {
          if (response.status == "success") {
            $(".password_form").html(response.content);
          } else if (response.status == "fail") {
            $(".error_msg").html(response.message);
          }

          isProcessing = false;
        }
      );
    },
    initValidation: function () {
      $(document).on("submit", "form.wp_vimeo_form", function (e) {
        let errors = false;
        $(".wp_vimeo_error").remove();
        $(this)
          .find(".wp_vimeo_input, .wp_vimeo_file_input")
          .each(function () {
            let validationRule = $(this).attr("wp_vimeo_validation");
            let value = $(this).val();
            if (validationRule == "required") {
              if (!value) {
                $(this)
                  .parent()
                  .append(
                    '<span class="wp_vimeo_error">required field.</span>'
                  );
                errors = true;
              }
            }
            if (validationRule == "email") {
              if (value) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (!regex.test(value)) {
                  $(this)
                    .parent()
                    .append(
                      '<span class="wp_vimeo_error">please enter a valid email address.</span>'
                    );
                  errors = true;
                }
              } else {
                $(this)
                  .parent()
                  .append(
                    '<span class="wp_vimeo_error">required field.</span>'
                  );
                errors = true;
              }
            }
            if (validationRule == "equalto") {
              let matchingvalue = $($(this).data("matching-field")).val();
              if (value != matchingvalue) {
                $(this)
                  .parent()
                  .append(
                    '<span class="wp_vimeo_error">password and confirm password should same.</span>'
                  );
                errors = true;
              }
            }
          });
        if (errors) {
          e.preventDefault();
        }
      });
    },
    openModal: function (modalId) {
      $("#" + modalId).fadeIn();
    },
    noteTemplate: function (editorId) {
      let content = tinyMCE.get(editorId).getContent();
      let template = `
    <h2>Theme</h2>
    <p>What was your theme today? (e.g., strength or balance)</p>

    <h2>Skills</h2>
    <p>What skills did you work on?</p>
    <p>Did you make any progress?</p>

    <h2>Milestones</h2>
    <p>Did you reach any milestones?</p>

    <h2>Challenges or Difficulties</h2>
    <p>What challenges or difficulties did you encounter?</p>
    <p>What did you try and what was the result? Did it help?</p>
    `;
      if (!content || content === "") {
        return tinyMCE.get(editorId).setContent(template);
      }

      if (
        window.confirm(
          "This will overwrite your current note with a template. Are you sure you want to continue?"
        )
      ) {
        tinyMCE.get(editorId).setContent(template);
      }
    },
    lessonTemplate: function (editorId) {
      let content = tinyMCE.get(editorId).getContent();
      if (!content || content === "") {
        return tinyMCE.get(editorId).setContent("<h2>Lesson Plan</h2>");
      }

      if (
        window.confirm(
          "This will overwrite your current note with a template. Are you sure you want to continue?"
        )
      ) {
        tinyMCE.get(editorId).setContent("<h2>Lesson Plan</h2>");
      }
    },
    resetFilter: function () {
      location.reload();
    },
    noteClear: function (editorId) {
      tinyMCE.get(editorId).setContent("");
    },
    closepopup: function () {
      $(".wp_vimeo_modal").fadeOut();
    },
    ajaxCall: function (url, data, callback) {
      url = $this.addQueryVar(url, "permalink", wp_vimeo.permalink);
      url = $this.addQueryVar(url, "_wpnonce", wp_vimeo._wpnonce);
      $.ajax({
        url: url, // server url
        type: "POST", //POST or GET
        data: data, // data to send in ajax format or querystring format
        datatype: "json",
        async: true,
        crossDomain: true,
        beforeSend: function (xhr) {
          $("body").append('<div class="wp_vimeo_loader"></div>');
        },
        success: function (data) {
          $(".wp_vimeo_loader").remove();
          callback(data); // return data in callback
        },
        complete: function () {
          $(".wp_vimeo_loader").remove();
        },

        error: function (xhr, status, error) {
          $(".wp_vimeo_loader").remove();
        },
      });
    },
    addQueryVar: function (uri, key, value) {
      var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
      var separator = uri.indexOf("?") !== -1 ? "&" : "?";
      if (uri.match(re)) {
        return uri.replace(re, "$1" + key + "=" + value + "$2");
      } else {
        return uri + separator + key + "=" + value;
      }
    },
    openVideoFrame: function (videoId) {
      $("#video_frame_append").html(
        '<iframe src="https://player.vimeo.com/video/' +
          videoId +
          '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen width="660" height="500"></iframe>'
      );
      $("#wp_vimeo_video_view").fadeIn();
    },
  };

  wpVimeo.initialize();
})(jQuery);

jQuery(document).ready(function(){
	jQuery('.deselect').on('click',function(){
		jQuery("#multi_tag").find('option').prop('selected', jQuery(this).hasClass('select')).parent().trigger('chosen:updated');
	});
});