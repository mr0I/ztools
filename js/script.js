jQuery(document).ready(function($) {

  // Constants
  var max_tags_num = 5;
  var max_tags_chars = 3;
  var max_editor_words = 7000;
  var edit_planet_page = $('input#edit_url_val').val();
  // Constants
  // jq translates
  var max_tags_num_message = $('input#max_tags_num_message').val();
  var duplicate_tags = $('input#duplicate_tags').val();
  var min_tags_letters = $('input#min_tags_letters').val();
  var success_post_submit_message = $('input#success_post_submit_message').val();
  var error_send_info = $('input#error_send_info').val();
  var success_post_edit_message = $('input#success_post_edit_message').val();
  var success_delete_post = $('input#success_delete_post').val();
  var error_delete_post = $('input#error_delete_post').val();
  var submit_btn_text1 = $('input#submit_btn_text1').val();
  var submit_btn_text2 = $('input#submit_btn_text2').val();
  var edit_btn_text1 = $('input#edit_btn_text1').val();
  var edit_btn_text2 = $('input#edit_btn_text2').val();
  // jq translates



  $(".chosen-select").chosen();

  try{
    var tags = ($('#planet_tags').val()).split(',');
  }catch(e){
  }

  $('#planet_tags_input').keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
      var str = $(this).val();
      if (str.length >= max_tags_chars) {
        if ($('.tags_list_item').size() >= max_tags_num) {
          alert(max_tags_num_message);
          return;
        }
        if ($.inArray(str, tags) == -1) {
          tags.push(str);
        }else{
          alert(duplicate_tags);
          return;
        }
        var old_val = $('#planet_tags').val();
        var new_val = old_val + str + ',';
        $('#planet_tags').val(new_val);
        $(this).val('');
        $('<li class="tags_list_item"><span>' + str + '</span><a href="#"><i class="fa fa-times"></i></a></li>').appendTo('#tags_list');
      }else{
        alert(min_tags_letters);
        return;
      }
    }
  });
  $(document).on('click', '.tags_list_item a', function(event) {
   event.preventDefault();
   var deleted_str = $(this).parent().find('span').text();
   var old_val = $('#planet_tags').val();
   var new_val = old_val.replace(deleted_str+',' , '');
   $('#planet_tags').val(new_val);
   $(this).parent().remove();
   tags.splice( $.inArray(deleted_str, tags), 1 );
 });

  var content_words_len = 0;
  var content_txt;
  $(document).on('keyup' , '#planet_content_div' , function(){
    var content_words = $.trim($(this).find('p').text()).split(' ');
    content_words_len = content_words.length;
    $('.planet_content_char_counter').css('display', 'inline-block').text('('+ content_words_len + '/' + max_editor_words + ')');
    if (content_words_len > max_editor_words) {
      $('.planet_content_char_counter').css('color', 'red');
    }else{
      $('.planet_content_char_counter').css('color', '#6c757d');
    }
  });
  $(document).on('focusout' , '#planet_content_div' , function(){
    content_txt = $(this).html();
  });


  $('#frm_planet_submit').click(function(event) {
    event.preventDefault();
    var post_title = $('#planet_title').val();
    var post_tags = $('#planet_tags').val();
    var post_cats = $('#cat').val();
    var post_url = $('#planet_url').val();
    var captcha = $('#planet_captcha').val();
    var nonce = $('#planet_nonce').val();
    var data = {
      action: 'submit_planet_frm',
      security : PlanetAjax.security,
      post_title: post_title,
      post_content: content_txt,
      content_len: content_words_len,
      post_tags: post_tags,
      post_cats: post_cats,
      post_url: post_url,
      nonce: nonce,
      max_editor_chars: max_editor_words,
    };

    $(this).attr('disabled', 'true').css('cursor', 'not-allowed').text(submit_btn_text2);
    $.ajax({
      url: PlanetAjax.ajaxurl,
      type: 'POST',
      data: data,
      success: function (res) {
        var res= $.parseJSON( res );
        if (res.res === 1) {
         Toastnotify.create({
          text: success_post_submit_message,
          type:'dark',
          duration : 3000,
          important:false
        });
         $('.vh').css('background-color', '#00C851');
         $('#refresh_captcha').click();
         $.fn.reset_planet_frm();
         window.location.replace('https://sisoog.com/my_planet/');
       }else if(res.res === 0){
        $('#planet_frm_err_notify').slideDown('slow');
      // show errors
      if (res.err['title'] != undefined) {
        $('#title_err').css('display', 'block');
      }else{
        $('#title_err').css('display', 'none');
      }
      if (res.err['content'] != undefined) {
        $('#content_err').css('display', 'block');
      }else{
        $('#content_err').css('display', 'none');
      }
      if (res.err['contentLength'] != undefined) {
        $('#content_err2').css('display', 'block');
      }else{
        $('#content_err2').css('display', 'none');
      }
      if (res.err['url'] != undefined) {
        $('#url_err').css('display', 'block');
      }else{
        $('#url_err').css('display', 'none');
      }
      if (res.err['captcha'] != undefined) {
        $('#captcha_err').css('display', 'block');
      }else{
        $('#captcha_err').css('display', 'none');
      }
    }
  }, error:function (err) {
    Toastnotify.create({
      text:error_send_info,
      type:'dark',
      duration : 3000,
      important:false
    });
    $('.vh').css('background-color', '#f44');
  },complete:function () {
    $('#frm_planet_submit').removeAttr('disabled').css('cursor', 'pointer').text(submit_btn_text1);
  }
});

  });


  $('#frm_planet_edit').click(function(event) {
    event.preventDefault();
  //var content_len = $('#planet_content_div').find('p').text().length;
  var content_words = $.trim($('#planet_content_div').find('p').text()).split(' ');
  var content_words_len = content_words.length;

  var post_id = $('#planet_postId').val();
  var post_title = $('#planet_title').val();
  //alert(content_len);
  if (content_txt == undefined) {
    var post_content = $('#planet_content_temp').html();
  }else{
    var post_content = content_txt;
  }
  var post_tags = $('#planet_tags').val();
  var post_cats = $('#cat').val();
  var post_url = $('#planet_url').val();
  var captcha = $('#planet_captcha').val();
  var nonce = $('#planet_nonce').val();
  var data = {
    action: 'edit_planet_frm',
    security : PlanetAjax.security,
    post_id: post_id,
    post_title: post_title,
    post_content: post_content,
    content_len: content_words_len,
    post_tags: post_tags,
    post_cats: post_cats,
    post_url: post_url,
    nonce: nonce,
    max_editor_chars: max_editor_words,
  };

  $(this).attr('disabled', 'true').css('cursor', 'not-allowed').text(edit_btn_text2);
  $.ajax({
    url: PlanetAjax.ajaxurl,
    type: 'POST',
    data: data,
    success: function (res) {
      var res= $.parseJSON( res );
      if (res.res === 1) {
       Toastnotify.create({
        text: success_post_edit_message,
        type:'dark',
        duration : 3000,
        important:false
      });
       $('.vh').css('background-color', '#448AFF');
       $('#refresh_captcha').click();
       window.location.replace('https://sisoog.com/my_planet');
       }else if(res.res === 0){
        $('#planet_frm_err_notify').slideDown('slow');
      // show errors
      if (res.err['title'] != undefined) {
        $('#title_err').css('display', 'block');
      }else{
        $('#title_err').css('display', 'none');
      }
      if (res.err['content'] != undefined) {
        $('#content_err').css('display', 'block');
      }else{
        $('#content_err').css('display', 'none');
      }
      if (res.err['contentLength'] != undefined) {
        $('#content_err2').css('display', 'block');
      }else{
        $('#content_err2').css('display', 'none');
      }
      if (res.err['url'] != undefined) {
        $('#url_err').css('display', 'block');
      }else{
        $('#url_err').css('display', 'none');
      }
      if (res.err['captcha'] != undefined) {
        $('#captcha_err').css('display', 'block');
      }else{
        $('#captcha_err').css('display', 'none');
      }
    }
  }, error:function (err) {
    Toastnotify.create({
      text:error_send_info,
      type:'dark',
      duration : 3000,
      important:false
    });
    $('.vh').css('background-color', '#f44');
  },complete:function () {
    $('#frm_planet_edit').removeAttr('disabled').css('cursor', 'pointer').text(edit_btn_text1);
  }
});
});



  $('span.close_modal').click(function(event) {
    $('#myModal').css('display', 'none');
  });
  $('#myModal').click(function(event) {
    $(this).css('display', 'none');
  });
  $('.close_modal_btn').click(function(event) {
   event.preventDefault();
   $('#myModal').css('display', 'none');
 });

  $('.remove_planet').click(function(event) {
   event.preventDefault();
   var postId = $(this).data('id');
   $('#delete_planet').attr('data-id', postId);
   $('#myModal').fadeIn(400);
 });

  $('#delete_planet').click(function(event) {
   event.preventDefault();
   $('.planet_loading').css('display', 'block');

   var postId = $(this).data('id');
   var nonce = $('#remove_planet_nonce').val();
   var data = {
    action: 'planet_remove',
    security : PlanetAjax.security,
    post_id : postId,
    nonce : nonce
  }
  $.ajax({
    url: PlanetAjax.ajaxurl,
    type: 'POST',
    data: data,
    success: function (res) {
      var res= $.parseJSON( res );
      if (res.res === 1) {
       Toastnotify.create({
        text:success_delete_post,
        type:'dark',
        duration : 3000,
        important:false
      });
       $('.vh').css('background-color', '#00C851');
       $('.planet_loading').css('display', 'none');
       location.reload();
     }else if(res.res === 0){
       Toastnotify.create({
        text:error_delete_post,
        type:'dark',
        duration : 3000,
        important:true
      });
       $('.vh').css('background-color', '#f44');
     }
   }, error:function (err) {
     Toastnotify.create({
      text:error_send_info,
      type:'dark',
      duration : 3000,
      important:false
    });
     $('.vh').css('background-color', '#f44');
   },complete:function () {
   }

 });
});


  $('.edit_planet').click(function(event) {
    event.preventDefault();
    $('.planet_loading').css('display', 'block');

    var postId = $(this).data('id');
    var data = {
      action: 'edit_planet',
      security : PlanetAjax.security,
      post_id : postId
    }
    $.ajax({
      url: PlanetAjax.ajaxurl,
      type: 'POST',
      data: data,
      success: function (res) {
        var res= $.parseJSON( res );
        if (res.res == 1) {
          window.location.replace(edit_planet_page+"?post_id="+postId);
          setTimeout(function() {
            $('.planet_loading').css('display', 'none');
          }, 3000);
        }
      }, error:function (err) {
        Toastnotify.create({
          text:error_send_info,
          type:'dark',
          duration : 3000,
          important:false
        });
        $('.vh').css('background-color', '#f44');
      },complete:function () {
      }
    });
  });


  $.fn.reset_planet_frm = function(){
    $('#planet_title').val('');
    $('#planet_url').val('');
    $('#planet_tags').val('');
    $('#planet_tags_input').val('');
    $('#planet_pic').val('');
    $('#planet_captcha').val('');
    $('.tags_list_item').remove();
    $('.search-choice-close').click();
    $('#planet_content_div').find('p').text('');
    $('#title_err').css('display', 'none');
    $('#content_err').css('display', 'none');
    $('#content_err2').css('display', 'none');
    $('#url_err').css('display', 'none');
    $('#captcha_err').css('display', 'none');
    $('#planet_frm_err_notify').slideUp('fast');
  }

});







