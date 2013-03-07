var $ = jQuery;

function beforeSendHandler(){
    if($('#aj_action').val() == 'addAvatar'){
        $('span', '#uploadAvatar').hide();
        $('#uploadAvatar').append('<div class="btn_comm2"><div class="progress"></div></div>');
    }
}
function processResponse(responseText, statusText, xhr, $form){
    if(statusText == 'success') {
        if($('.progress').length){
            $('.progress').parent().remove();
        }

        if(responseText.succ){
            $('#profile_required_image').text('').css('visibility', 'hidden');

            $('#uploadAvatar').fadeOut('fast', function(){
                $('#uploadAvatar').html(responseText.succ);
            });
            $('#uploadAvatar').fadeIn('slow');
        } else {
            if(responseText.fail){
                $('#profile_required_image').text(responseText.fail).css('visibility', 'visible');
            }
            $('span', '#uploadAvatar').show();
        }
    }
}
function progressHandlingFunction(event, position, total, percentComplete){
    $('.progress').css('width', percentComplete+'%');
}


(function($) {
    $(document).ready(function() {
        $('#uploatAvatar').live('change', function(){
            $('#aj_action').val('addAvatar');
            $('#form_box_upload').submit();
        })
        $('#delAvatar').live('click', function(){
            $('#aj_action').val('delAvatar');
            $('#form_box_upload').submit();
            return false;
        })

        $('#form_box_upload').ajaxForm({
            dataType: 'json',
            beforeSend: beforeSendHandler,
            uploadProgress: progressHandlingFunction,
            success: processResponse
        });

        $('#f_submit').click(function(e){
            e.preventDefault();
            $('#form_box_all').submit();
        })


        $('.delete-ad-img').live('click', function(){
            var id = $(this).attr('id').split('-');
            var box = $(this).parent();
            $.post(
                document.location.href,
                {
                    aj_action: 'delAd',
                    id: id[1],
                    pos: id[2]
                },
                function(res){
                    if(res){
                        box.fadeOut('fast', function(){
                            box.html(res);
                        });
                        box.fadeIn('slow');
                    }
                }
            )
            return false;
        })
        $('.add-ad-img').live('change', function(){
            var box = $(this).parent().parent();
            var id = $(this).attr('id').split('-');

            box.append('<input type="hidden" name="aj_action" value="addAd" />');
            box.append('<input type="hidden" name="pos" value="'+id[1]+'" />');

            box.wrap('<form action="'+document.location.href+'" method="post" enctype="multipart/form-data"/>');

            var form = box.parent();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSend: function(){
                    $('span', box).hide();
                    box.append('<div class="btn_comm2"><div class="progress"></div></div>');
                },
                uploadProgress: function(event, position, total, percentComplete){
                    $('.progress', box).css('width', percentComplete+'%');
                },
                success: function(res){
                    if($('.progress', box).length){
                        $('.progress', box).parent().remove();
                    }
                    if(res){
                        if(res.succ){
                            $('#profile_required_image').text('').css('visibility', 'hidden');
                            box.fadeOut('fast', function(){
                                box.html(res.succ);
                            });
                            box.fadeIn('slow');
                        } else {
                            if(res.fail){
                                $('#profile_required_image').text(res.fail).css('visibility', 'visible');
                            }
                            $('span', box).show();
                        }

                    }
                    box.find('input[type=hidden]').remove();
                    box.unwrap();
                }
            })
        })

        $('#am_country').selectmenu({
            change: function(e){
                updateState($(this).val())
            }
        })

        function updateState(country){
            $('#am_state').selectmenu('disable');
            $.post(
                document.location.href,
                {
                    aj_action: 'changeCountry',
                    country: country
                },
                function(res){
                    if(res){
                        $('#am_state').html(res)
                        $('#am_state').selectmenu('enable');
                        $('#am_state').selectmenu('destroy').selectmenu({maxHeight: 380});
                    }
                }
            )
        }

		$('a[rel=external]').on('click',function(e){open(this.href);e.preventDefault();}).attr('title', 'Opens in a new window');
		
		$('li:first-child').addClass('first-item'); $('li:last-child').addClass('last-item');		

		//custom select
		$('.simu_select').selectmenu({
			//width: 210,
			maxHeight: 380
		});
	
	
		//user panel
		$('.btn_person').click( function(){
			$(this).parent().toggleClass('user_panel_on');
			return false;
		});
		
		$('.user_panel').mouseleave( function(){
			$('.user_panel').removeClass('user_panel_on');
			return false;
		});
		
		$('.user_panel .dropbox li').click( function(){
			$('.user_panel').removeClass('user_panel_on');
		});
		
		$('#ad_slider .btn_switch').click( function(){
			$('#ad_slider').hide(0);
			$('#ad_map').show(0);
			am_initialize();
			return false;
		});
		
		$('#ad_map .btn_switch').click( function(){
			$('#ad_map').hide(0);
			$('#ad_slider').show(0);
			am_initialize();
			return false;
		});
		
		
		//passwrod
		$('#show_pass').bind('change', function () {
			if ($(this).is(':checked')){
				var val = $('#input_password_real').val();
				$('#input_password_real').hide(0);
				$('#input_password').show(0).val(val);
			}
			else{
				var val = $('#input_password').val();
				$('#input_password').hide(0);
				$('#input_password_real').show(0).val(val);
			}
		
		});
		
		$("#form_register").submit(function() {
			if ($('#show_pass').is(':checked')){
				var val = $('#input_password').val();
				$('#input_password_real').val(val);
			}
			else{
				var val = $('#input_password_real').val();
				$('#input_password').val(val);
			}
			return true;
		});
			
		
		//flag select
		//language
		$('.language_box').superfish();
		
		$('.language_box .dropbox li').click( function(){
			$('.language_box .dropbox').hide();
		});
		
		//slideshow
		$('.ad_slider ul').cycle({
			fx: 'fade',
			//easing: "easeInOutExpo",
			speed: 1000, 
			timeout: 7000,
			cleartypeNoBg: true,
			pager:'.sli_dots',
			prev: '.sli_prev',
			next: '.sli_next'
		});
		
		
		//simulate checkbox in index
		$("input[type=checkbox]").uniform();
		
		//clear field
		function ClearField(){
			var fields = $('input[type="text"],input[type="password"],textarea');
			fields.each(function(){
				var this_field = $(this);
				var default_value = this_field.val();
				var default_original_value = this_field.data('default');
				if(default_original_value!=default_value){
					this_field.css({"color":"#544c43"});
				}else{
					this_field.focus(function(){
						if(this_field.val() == default_value){
							this_field.val("").css({"color":"#544c43"});
						}
					 });
					 this_field.blur(function(){
						if(this_field.val() == ""){
							this_field.val(default_value).css({"color":"#ccc"});
						}
					 });
				}
			});
		}
		ClearField();
		
		//pop boxes
		$(".fancybox").fancybox({
			'titlePosition'		: 'inside',
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'overlayOpacity'	: 0.7,
			'overlayColor'		: '#000',
			'padding'			: 0,
			'modal'				: false,
			'showCloseButton'	: false,
			'enableEscapeButton': true
		});
	
	
		$('.close_pop').click( function(){
			$.fancybox.close();
			return false;
		});
		
		
		//ad form icon toggle on
		$('.object_style li').click( function(){
			$(this).toggleClass('on');
			var rel = $(this).attr('rel');
			var val = $('#'+rel).val();
			var val_slug = $(this).attr('title');
			if(val!='')
				$('#'+rel).val('');
			else
				$('#'+rel).val(val_slug);
			return false;
		});
        
    });
})(jQuery);