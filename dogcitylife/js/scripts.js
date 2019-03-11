Array.prototype.unique = function() {
  var a = this.concat();
  for (var i = 0; i < a.length; ++i) {
    for (var j = i + 1; j < a.length; ++j) {
      if (a[i] === a[j]) a.splice(j, 1);
    }
  }
  return a;
};

function navigate(lat, lng) {
  // If it's an iPhone..
  if (navigator.platform.indexOf("iPhone") !== -1 || navigator.platform.indexOf("iPod") !== -1) {
    function iOSversion() {
      if (/iP(hone|od|ad)/.test(navigator.platform)) {
        // supports iOS 2.0 and later: <http://bit.ly/TJjs1V>
        var v = navigator.appVersion.match(/OS (\d+)_(\d+)_?(\d+)?/);
        return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
      }
    }
    var ver = iOSversion() || [0];

    if (ver[0] >= 6) {
      protocol = "maps://";
    } else {
      protocol = "http://";
    }
    window.location = protocol + "maps.apple.com/maps?daddr=" + lat + "," + lng + "&amp;ll=";
  } else {
    window.open("http://maps.google.com?daddr=" + lat + "," + lng + "&amp;ll=");
  }
}

function showPosition(position) {
  $("input#lat").val(position.coords.latitude);
  $("input#lng").val(position.coords.longitude);

  $(".search_header form").submit();
}

function vyhledavani_ajax(page) {
  page = page || 1;

  $(".loader").show();
  var data = $("#sidebar_form, .search_header form").serialize();
  $("#search_cont").load(window.location.pathname + "?" + data + "&page=" + page + " #search_cont", function() {
    $(".loader").hide();
  });

  $.get(window.location.pathname + "?" + data + "&page=" + page, function(response) {
    num = $(response)
      .find(".search_line .results")
      .html();
    $(".search_line .results").html(num);
    response = $(response)
      .find("#search_cont")
      .html();
    $("#search_cont")
      .empty()
      .append(response);
    // if ($(window).width() <= 890) {
    // $(".sidebar .inner").fadeToggle();
    // }
  });
}

function zoomDisable() {
  $("head meta[name=viewport]").remove();
  $("head").prepend('<meta name="viewport" content="width=device-width, initial-scale=0.8, user-scalable=0" />');
}
function zoomEnable() {
  $("head meta[name=viewport]").remove();
  $("head").prepend('<meta name="viewport" content="width=device-width, initial-scale=0.8, user-scalable=1" />');
}

function applePie() {
  var is_pad = navigator.userAgent.match(/(iPhone|iPod|iPad)/i);
  if (is_pad && window.innerHeight < window.innerWidth) {
    return true;
  } else {
    return false;
  }
}

$(window).resize(function() {
  var item_width = $(".detail_gallery .num2").height();
  item_width = item_width - 20;
  $(".detail_gallery #map").css("height", item_width + "px");

  if (applePie() && $(".fancybox-overlay").length) {
    $("body").css({ position: "fixed" });
  } else if ($(".fancybox-overlay").length) {
    $("body").css({ position: "" });
  } else {
    $("body").css({ position: "" });
  }
});

$(document).ready(function() {
  var ajaxUrl = $("#ajaxUrl").val();

  $(".sidebar .inner").fadeToggle(0);

  $('.objednavkovy_form .multiple input[type="checkbox"]').checkboxradio();

  $("body").on("click", ".fb_login.disabled", function(e) {
    e.preventDefault();
  });

  $(".detail_gallery .num2 img").on("load", function() {
    var item_width = $(".detail_gallery .num2").height();
    item_width = item_width - 20;
    $(".detail_gallery #map").css("height", item_width + "px");
  });

  $("input#podminky_fb").change(function() {
    if ($(this).is(":checked")) {
      $(".fb_login").removeClass("disabled");
    } else {
      $(".fb_login").addClass("disabled");
    }
  });

  $("input, textarea, select").on({
    touchstart: function() {
      zoomDisable();
    }
  });
  $("input, textarea, select").on({
    touchend: function() {
      setTimeout(zoomEnable, 500);
    }
  });

  $(".navigate").click(function(e) {
    e.preventDefault();
    navigate($(this).attr("data-lat"), $(this).attr("data-lng"));
  });

  if ($(".search_line").length) {
    if ($("body").css("zoom") == "0.8") {
      $("html, body").animate(
        {
          scrollTop: $(".search_line").offset().top - ($(".search_line").offset().top / 100) * 20
        },
        1000
      );
    } else {
      $("html, body").animate(
        {
          scrollTop: $(".search_line").offset().top
        },
        1000
      );
    }
  }

  if ($(".detail_title").length) {
    if ($("body").css("zoom") == "0.8") {
      $("html, body").animate(
        {
          scrollTop: $(".detail_title").offset().top - ($(".detail_title").offset().top / 100) * 20
        },
        1000
      );
    } else {
      $("html, body").animate(
        {
          scrollTop: $(".detail_title").offset().top
        },
        1000
      );
    }
  }

  if ($(".cms_page").length) {
    if ($("body").css("zoom") == "0.8") {
      $("html, body").animate(
        {
          scrollTop: $(".cms_page").offset().top - ($(".cms_page").offset().top / 100) * 20
        },
        1000
      );
    } else {
      $("html, body").animate(
        {
          scrollTop: $(".cms_page").offset().top
        },
        1000
      );
    }
  }

  var item_width = $(".detail_gallery .num2").height();
  item_width = item_width - 20;
  $(".detail_gallery #map").css("height", item_width + "px");

  $(".sidebar .title").click(function(e) {
    e.preventDefault();
    // if($(window).width() <= 890){
    $(".sidebar .inner").fadeToggle();
    // }
  });

  /*$('.flexslider').flexslider({
		animation: "slide",
		directionNav: false,
		slideshow: false,
		touch: true,
		start: function(){},
		after: function(slider){
			$('.flex_counter span').text(slider.currentSlide+1);
		},
	});*/

  $(".slides_js").slidesjs({
    callback: {
      complete: function(number) {
        // Do something awesome!
        // Passes slide number at end of animation
        $(".flex_counter span").text(number);
      }
    }
  });

  $(".profile_box .remove_account").click(function(e) {
    e.preventDefault();
    if (confirm($("#user_delete_message").val())) {
      $(".loader").show();

      var data = {};

      //test if not in database
      var request = jQuery.ajax({
        type: "POST",
        url: ajaxUrl + "?ajax_remove_account=post",
        data: data
      });

      request.done(function(msg) {
        $(".loader").hide();
        location.reload();
      });
    } else {
      // Do nothing!
    }
  });

  $(".profile_box .remove_newsletter").click(function(e) {
    e.preventDefault();
    $(".loader").show();

    var data = {};

    //test if not in database
    var request = jQuery.ajax({
      type: "POST",
      url: ajaxUrl + "?ajax_remove_newsletter=post",
      data: data
    });

    request.done(function(msg) {
      $(".loader").hide();
      $(".profile_box .remove_newsletter").hide();
      $(".profile_box .activate_newsletter").show();
    });
  });

  $(".profile_box .activate_newsletter").click(function(e) {
    e.preventDefault();
    $(".loader").show();

    var data = {};

    //test if not in database
    var request = jQuery.ajax({
      type: "POST",
      url: ajaxUrl + "?ajax_activate_newsletter=post",
      data: data
    });

    request.done(function(msg) {
      $(".loader").hide();
      $(".profile_box .activate_newsletter").hide();
      $(".profile_box .remove_newsletter").show();
    });
  });

  $(".profile_box .detail_gallery .remove").click(function(e) {
    e.preventDefault();
    $(this)
      .parent()
      .remove();
  });

  $('.file_upload input[type="file"]').change(function(e) {
    var files = $(this)[0].files;
    var text = "";

    $.each(files, function(key, value) {
      if (key == files.length - 1) {
        text = text + value["name"] + "";
      } else {
        text = text + value["name"] + ", ";
      }
    });
    $(this)
      .parent()
      .children("span")
      .text(text);
  });

  $(".profile_title .save").click(function(e) {
    e.preventDefault();
    $(this)
      .parents("form")
      .submit();
  });

  $(".profile_title .edit").click(function(e) {
    e.preventDefault();
    $(this).hide();
    $(".profile_title .save, .profile_title .cancel").show();
    $(".profile_box textarea").removeAttr("disabled");
    $(".favorite_zarizeni .remove").show();
    $(".file_upload").show();
    $(".profile_box .detail_gallery .remove").show();
    $(".profile_box .input input").removeAttr("disabled");
  });

  $(".favorite_zarizeni .remove").click(function(e) {
    e.preventDefault();
    $(this)
      .parent()
      .remove();
  });

  $("body").on("click", ".ajax_add_favorite", function(e) {
    e.preventDefault();
    $(".loader").show();

    var cur_link = $(this);

    var data = { zarizeni_ID: jQuery(this).attr("data-id") };

    //test if not in database
    var request = jQuery.ajax({
      type: "POST",
      url: ajaxUrl + "?ajax_add_favorite=post",
      data: data
    });

    request.done(function(msg) {
      $(".loader").hide();
      if (msg == "true") {
        cur_link.removeClass("ajax_add_favorite");
        cur_link.addClass("active ajax_remove_favorite");
      }
    });
  });

  $("body").on("click", ".ajax_remove_favorite", function(e) {
    e.preventDefault();
    $(".loader").show();

    var cur_link = $(this);

    var data = { zarizeni_ID: jQuery(this).attr("data-id") };

    //test if not in database
    var request = jQuery.ajax({
      type: "POST",
      url: ajaxUrl + "?ajax_remove_favorite=post",
      data: data
    });

    request.done(function(msg) {
      $(".loader").hide();
      if (msg == "true") {
        cur_link.removeClass("active ajax_remove_favorite");
        cur_link.addClass("ajax_add_favorite");
      }
    });
  });

  $(".sidebar .remove_filters").click(function(e) {
    e.preventDefault();
    $(".sidebar .rating_input .rating").removeClass("rating0 rating1 rating2 rating3 rating4 rating5");
    $("input#hodnoceni").val(0);

    $("input#vzdalenost").val(1);
    $(".sidebar .vzdalenost_text span").text(1);
    $("#vzdalenost_slider").slider("value", 9);

    $("select#benefit option[selected]").attr("selected", false);
    $(".sidebar .benefity a.selected").removeClass("selected");

    $("select#typ_side option[selected]").attr("selected", false);
    $(".sidebar .zarizeni a.selected").removeClass("selected");

    vyhledavani_ajax();
  });

  $(".right_side").on("click", ".pagination a", function(e) {
    e.preventDefault();
    vyhledavani_ajax($(this).attr("data-page"));
  });

  $(".sidebar .rating_input .rating .point").click(function(e) {
    e.preventDefault();
    $(this)
      .parent()
      .removeClass("rating0 rating1 rating2 rating3 rating4 rating5");
    $(this)
      .parent()
      .addClass("rating" + $(this).attr("data-val"));
    $(this)
      .parent()
      .parent()
      .children("input")
      .val($(this).attr("data-val"));

    vyhledavani_ajax();
  });

  $(".sidebar .benefity .select_box").click(function(e) {
    e.preventDefault();
    $(this).toggleClass("selected");
    var sel = false;
    if ($(this).hasClass("selected")) {
      sel = true;
    }

    $(this)
      .parent()
      .children("select")
      .children('option[value="' + $(this).attr("data-val") + '"]')
      .attr("selected", sel);

    vyhledavani_ajax();
  });

  $(".sidebar .zarizeni .select_box").click(function(e) {
    e.preventDefault();
    $(this).toggleClass("selected");
    var sel = false;
    if ($(this).hasClass("selected")) {
      sel = true;
    }

    $(this)
      .parent()
      .children("select")
      .children('option[value="' + $(this).attr("data-val") + '"]')
      .attr("selected", sel);
    $('#typ option[value="' + $(this).attr("data-val") + '"]').attr("selected", sel);
    vyhledavani_ajax();
  });

  $(function() {
    var valMap = [];
    var last = parseFloat(0);
    for (var i = 0; i <= 108; i++) {
      if (i <= 9) {
        valMap[i] = parseFloat(last) + 0.1;
        if (i == 9) {
          valMap[i] = parseFloat(valMap[i]).toFixed(0);
        } else {
          valMap[i] = parseFloat(valMap[i]).toFixed(1);
        }

        last = valMap[i];
      } else {
        valMap[i] = parseFloat(last) + 1;
        valMap[i] = parseFloat(valMap[i]).toFixed(0);
        last = valMap[i];
      }
    }
    var vzdalenost_slider = $("#vzdalenost_slider").slider({
      range: "max",
      min: 0,
      max: valMap.length - 1,
      value: 108,
      slide: function(event, ui) {
        $("#vzdalenost").val(valMap[ui.value]);
        $(".vzdalenost_text span").text(valMap[ui.value]);
      },
      change: function(event, ui) {
        vyhledavani_ajax();
      }
    });
    //$( "#vzdalenost" ).val( $( "#slider-range-max" ).slider( "value" ) );
  });

  $("#list_review").on("click", ".pagination a", function(e) {
    e.preventDefault();
    $(".loader").show();
    $("#list_review").load(
      $(this).attr("href") + "&order=" + $("select#order").val() + " #list_review > *",
      function() {
        $(".loader").hide();
      }
    );
  });

  $(".recenze").on("change", "select#order", function(e) {
    e.preventDefault();
    $(".loader").show();
    if ($(this).hasClass("profile_review")) {
      $("#list_review").load(
        window.location.pathname + window.location.search + "&order=" + $(this).val() + " #list_review > *",
        function() {
          $(".loader").hide();
        }
      );
    } else {
      $("#list_review").load(window.location.pathname + "?order=" + $(this).val() + " #list_review > *", function() {
        $(".loader").hide();
      });
    }
  });

  $(".tooltip").tooltip({
    position: {
      my: "left-15 bottom-20",
      at: "center top",
      collision: "flipfit",
      using: function(position, feedback) {
        $(this).css(position);
        $(this).addClass(feedback.vertical);
        $(this).addClass(feedback.horizontal);
      }
    }
  });

  /*$(".header .select_box").click(function(e){
		e.preventDefault();
		$(this).toggleClass("selected");
		var sel = false;
		if($(this).hasClass("selected")){
			sel = true;
		}
		$('#typ option[value="' + $(this).attr("data-val") + '"]').attr("selected", sel);
		//$('#typ_side option[value="' + $(this).attr("data-val") + '"]').attr("selected", sel);
	});*/

  $(".footer .up").click(function(e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
  });

  $(".search_bar .gps").click(function(e) {
    e.preventDefault();
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      window.alert("Geolokace není dostupná");
    }
  });

  $(".search_bar button").click(function(e) {
    e.preventDefault();
    if ($("#search").val() != "") {
      $(".search_header form").submit();
    }
  });

  $(".detail_gallery .fancybox").fancybox({
    prevEffect: "none",
    nextEffect: "none",
    helpers: {
      title: {
        type: "outside"
      },
      thumbs: {
        width: 122,
        height: 80
      }
    }
  });

  $(".add_review .rating_input .point").click(function(e) {
    e.preventDefault();
    $(this)
      .parent()
      .removeClass("rating0 rating1 rating2 rating3 rating4 rating5");
    $(this)
      .parent()
      .addClass("rating" + $(this).attr("data-val"));
    $(this)
      .parent()
      .parent()
      .children("input")
      .val($(this).attr("data-val"));
  });

  $(".top_header .user_header").hover(function(e) {
    $(this)
      .children(".dropdown_menu")
      .stop(true, false)
      .fadeToggle(300);
  });

  $(".top_header .mobile_menu .hamburger").click(function(e) {
    e.preventDefault();
    $(this)
      .next()
      .stop(true, false)
      .fadeToggle(300);
    $(".top_header").toggleClass("borderless");
  });

  $(".login_fancybox").fancybox({
    wrapCSS: "fancybox-login",
    autoSize: true,
    scrolling: "auto",
    fitToView: true,
    padding: 0,
    margin: 0,
    width: "100%",
    height: "auto",
    maxWidth: "100%",
    afterShow: function() {
      if (applePie()) {
        $("body").css({ position: "fixed" });
      }
    },
    beforeShow: function() {
      $("body").css({ "overflow-y": "hidden" });
    },
    afterClose: function() {
      $("body").css({ "overflow-y": "visible" });
      if (applePie()) {
        $("body").css({ position: "" });
      }
      $.fancybox.close(true);
      $(".fancybox-overlay").hide();
    },
    afterLoad: function() {
      $("form.form_login").validate({
        rules: {
          password: "required",
          email: {
            required: true,
            email: true
          }
        },
        submitHandler: function(form) {
          //ajax login
          $(".popup_inner .message").hide();
          var data = { email: jQuery("#login_email").val(), password: jQuery("#login_password").val() };

          //test if not in database
          var request = jQuery.ajax({
            type: "POST",
            url: ajaxUrl + "?ajax_check_login=post",
            data: data
          });

          request.done(function(msg) {
            if (msg == "true") {
              window.location.replace(location.href);
              //location.reload();
            } else {
              $(".popup_inner .message").show();
            }
          });
          //form.submit();
        }
      });

      $(".register").fancybox({
        wrapCSS: "fancybox-login",
        autoSize: true,
        scrolling: "auto",
        fitToView: true,
        padding: 0,
        margin: 0,
        width: "100%",
        height: "auto",
        maxWidth: "100%",
        afterShow: function() {
          if (applePie()) {
            $("body").css({ position: "fixed" });
          }
        },
        beforeShow: function() {
          $("body").css({ "overflow-y": "hidden" });
        },
        afterClose: function() {
          $("body").css({ "overflow-y": "visible" });
          if (applePie()) {
            $("body").css({ position: "" });
          }
          $.fancybox.close(true);
          $(".fancybox-overlay").hide();
        },
        beforeLoad: function() {
          /*$.fancybox.close(true);
					$(".fancybox-overlay").hide();
					$.fancybox.open($(".register").attr("href"), {
						wrapCSS:'fancybox-login',
				autoSize : true,
				scrolling : 'auto',
				fitToView : false,
				padding: 0,
				margin: 0,
				width : '100%',
				height: "auto",
				maxWidth : '100%',
					});*/
        },
        afterLoad: function() {
          $.fancybox.reposition();

          $(".fancybox-overlay").show();
          $("form.register_form").validate({
            rules: {
              password: "required",
              password_again: {
                equalTo: "#password"
              },
              email: {
                required: true,
                email: true,
                remote: {
                  url: ajaxUrl,
                  type: "post",
                  data: {
                    ajax_check_email: "post"
                  },
                  complete: function(data) {
                    console.log(data);
                  }
                }
              }
            },
            messages: {
              email: {
                remote: $("#email_message").val()
              }
            }
          });
        }
      });
      $(".lost_password").fancybox({
        wrapCSS: "fancybox-login",
        autoSize: true,
        scrolling: "auto",
        fitToView: true,
        padding: 0,
        margin: 0,
        width: "100%",
        height: "auto",
        maxWidth: "100%",
        afterShow: function() {
          if (applePie()) {
            $("body").css({ position: "fixed" });
          }
        },
        beforeShow: function() {
          $("body").css({ "overflow-y": "hidden" });
        },
        afterClose: function() {
          $("body").css({ "overflow-y": "visible" });
          if (applePie()) {
            $("body").css({ position: "" });
          }
          $.fancybox.close(true);
          $(".fancybox-overlay").hide();
        },
        beforeLoad: function() {
          $.fancybox.close(true);
          $.fancybox.open($(".lost_password").attr("href"), {
            wrapCSS: "fancybox-login",
            autoSize: true,
            scrolling: "auto",
            fitToView: false,
            padding: 0,
            margin: 0,
            width: "100%",
            height: "auto",
            maxWidth: "100%"
          });
        },

        afterLoad: function() {
          $("form.lost_password_form").validate({
            submitHandler: function(form) {
              //ajax login
              $(".popup_inner .message").hide();
              var data = { email: jQuery("#lost_email").val() };

              //test if not in database
              var request = jQuery.ajax({
                type: "POST",
                url: ajaxUrl + "?ajax_lost_pass=post",
                data: data
              });

              request.done(function(msg) {
                if (msg == "false") {
                  $("#lost_password .popup_inner .message").show();
                } else {
                  $("#lost_password .popup_inner .message").addClass("success");
                  $("#lost_password .popup_inner .message").show();
                  $("#lost_password .popup_inner .message").text(msg);
                  setTimeout(location.reload.bind(location), 2000);
                }
              });
              //form.submit();
            }
          });
        }
      });
    }
  });

  $(".register_fancybox").fancybox({
    wrapCSS: "fancybox-login",
    autoSize: true,
    scrolling: "auto",
    fitToView: true,
    padding: 0,
    margin: 0,
    width: "100%",
    height: "auto",
    maxWidth: "100%",
    afterShow: function() {
      if (applePie()) {
        $("body").css({ position: "fixed" });
      }
    },
    beforeShow: function() {
      $("body").css({ "overflow-y": "hidden" });
    },
    afterClose: function() {
      $("body").css({ "overflow-y": "visible" });
      if (applePie()) {
        $("body").css({ position: "" });
      }
      $.fancybox.close(true);
      $(".fancybox-overlay").hide();
    },
    afterLoad: function() {
      $("form.register_form").validate({
        rules: {
          password: "required",
          password_again: {
            equalTo: "#password"
          },
          email: {
            required: true,
            email: true,
            remote: {
              url: ajaxUrl,
              type: "post",
              data: {
                ajax_check_email: "post"
              },
              complete: function(data) {
                console.log(data);
              }
            }
          }
        },
        messages: {
          email: {
            remote: $("#email_message").val()
          }
        }
      });
    }
  });

  if (window.location.hash) {
    if ($(window.location.hash).length > 0) {
      if (window.location.hash == "#login") {
        $(".login_fancybox").click();
      }

      if (window.location.hash == "#register") {
        $(".register_fancybox").click();
      }
    }
  }

  $(".kontakt_page form").validate();

  $(".objednavkovy_form form").validate();

  $(".custom_checkbox").checkboxradio();

  $("form.review_form").validate({
    rules: {
      obsluha: { required: true },
      dog_friendly: { required: true },
      jidlo: { required: true },
      prostredi: { required: true }
    },
    groups: {
      obsluha: "obsluha dog_friendly jidlo prostredi"
    },
    errorPlacement: function(error, element) {
      if (
        element.attr("name") == "obsluha" ||
        element.attr("name") == "dog_friendly" ||
        element.attr("name") == "jidlo" ||
        element.attr("name") == "prostredi"
      )
        error.insertAfter("#obsluha");
      else error.insertAfter(element);
    },
    ignore: "",
    submitHandler: function(form) {
      $(".loader").show();
      //ajax login
      var data = {
        zarizeni_ID: jQuery("#zarizeni_ID").val(),
        popis: jQuery("#review_text").val(),
        obsluha: jQuery("#obsluha").val(),
        dog_friendly: jQuery("#dog_friendly").val(),
        jidlo: jQuery("#jidlo").val(),
        prostredi: jQuery("#prostredi").val()
      };

      //test if not in database
      var request = jQuery.ajax({
        type: "POST",
        url: ajaxUrl + "?ajax_add_review=post",
        data: data
      });

      request.done(function(msg) {
        $(".loader").hide();
        if (msg == "true") {
          location.reload();
        } else {
        }
      });
    }
  });
});
