if ($("#currentLang").length > 0) {
	var currentLang = $("#currentLang").data("lang");
}else{
	var currentLang = "sk_Sk";
};

(function(d, s, id) {	
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/" + currentLang + "/sdk.js#xfbml=1&version=v2.3";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$(document).ready(function() {

	$(document).on('DOMNodeInserted', '.ajaxWindowDialog', function(e) {
	  	//console.log("node inserted: %s", e.target.nodeName);
	  	if ( e.target.nodeName == "FORM" ) {
	  		grecaptcha.render("g-recaptcha", {sitekey: "6LcH904UAAAAAIX0L0wuqn3c_kgJk1Zpe3EzRFRv", "theme": "light"});
	  	}
	});

	var curr_price_from = $("#price_from").val();
	var curr_price_to = $("#price_to").val();
	var curr_area_from = $("#txt-rozloha1").val();
	var curr_area_to = $("#txt-rozloha2").val();		
	$(".autodialog").click(function(e){
		//e.preventDefault();	
		link = $(this);		
		var lnk = link.attr("href"); var userid = link.data("makler"); lnk = lnk.replace('#','');
		$(link.attr("href")).dialog({			
			title: link.attr("title"),	width: 180,	height: 200,
			modal: true, bgiframe: true, open: function() {
			$(".ui-widget-overlay").fadeTo(400, 0.5); var thisdialog = $(this);
			$(".ui-dialog-content").html('Načítavam obsah...');
			$(".ui-dialog-content").load('/nehnutelnost/qrcode/makler/'+userid);				
			$(".ui-widget-overlay").click(function(){thisdialog.dialog("close")})
			},beforeclose: function() {	$(".ui-widget-overlay").fadeTo(800, 0);	}			
		});	
		return false;
	});

	var maxPrice = 500000;
	var maxArea = 5000;
	if ($('input#setMaxPrice').length) {
		maxPrice = $('input#setMaxPrice').val();
	}

	if ($('input#setMaxArea').length) {
		maxArea = $('input#setMaxArea').val();
	}

	$("#slider-cena").slider({
		min: 0,
		max: maxPrice,
		step: 10000,
		values: [curr_price_from, curr_price_to],
		range: true,
		stop: function(event, ui) {
			updateSearch(this);
			//alert('stopped');
		},
		slide: function(event, ui) {
			$(this).parent().removeClass("group").addClass("groupe");
			//$("#txt-cena").val(ui.value);
			//document.getElementById('txt-cena').innerHTML = ui.values[0] + '  - '+ui.values[1] + ' €';
			//$("#txt-cena").val($("#slider-cena").slider("value"));
			$("#price_from").val(ui.values[0]);
			$("#price_to").val(ui.values[1]);
			$(".cena-od").html(ui.values[0]);
			$(".cena-do").html(ui.values[1]);
			$('.txt-cena1').value = ui.values[0] ;
			$('.txt-cena2').value = ui.values[1] ;
		}
	});

	$("#slider-rozloha").slider({
		min: 0,
		max: maxArea,
		step: 1,
		values: [curr_area_from, curr_area_to],
		range: true,
		stop: function(event, ui) {
		},
		slide: function(event, ui) {
			// $(this).parent().removeClass("group").addClass("groupe");
			// $("#txt-rozloha").val(ui.value);
			// $('#txt-rozloha1').value = ui.values[0] ;
			// $('#txt-rozloha2').value = ui.values[1] ;
			// $("#txt-rozloha").val($("#slider-rozloha").slider("value"));

			$("#txt-rozloha1").val(ui.values[0]);
			$("#txt-rozloha2").val(ui.values[1]);
			$('#txt-rozloha1').value = ui.values[0] ;
			$('#txt-rozloha2').value = ui.values[1] ;
		}
	});

	$(".ui-slider-horizontal .ui-slider-handle:last-child").attr('class','ui-slider-horizontal ui-slider-handle druhy');
	// rozbaluje lave menu pri kliknuti na + a -
	$('.list ul.menu li.sub a span').click(function(e){
		console.log('dsadsada');
		li = $(this).parent().parent()
		if(li.hasClass('sub')) {
			e.preventDefault()
			ul = li.find('ul').first()

			if(li.hasClass('active')) {
				ul.slideUp('slow', function(){
					li.removeClass('active')
				})
			} else {
				ul.slideDown('slow', function(){
					li.addClass('active')
				})
			}
		}
	});	

	//Gallery TPL11 +
	if($(".gallery_script_class").length > 0){
		$('a[rel=photogallery]').fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'titlePosition' 	: 'over',
			'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
			    return '<span id="fancybox-title-over">' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
			}
		});
	}

	if($(".w2bslikebox_script_class").length > 0){
		$(".w2bslikebox").hover(function() {
			$(this).stop().animate ({right: "0"}, "medium");}, 
			function() {$(this).stop().animate({right: "-300"}, "medium");},
			500);
		
		$(".w2bslikebox").toggle(function() {
		  $(this).stop().animate ({right: "0"}, 500);
		}, function() {
		  $(this).stop().animate({right: "-300"}, 500);
		});
	}
	
	if($(".gpbadgebox_script_class").length > 0){
		$(".gpbadgebox").hover(function() {
			$(this).stop().animate ({right: "0"}, "medium");}, 
			function() {$(this).stop().animate({right: "-228"}, "medium");},
			500);
	
		$(".gpbadgebox").toggle(function() {
		  $(this).stop().animate ({right: "0"}, 500);
		}, function() {
		  $(this).stop().animate({right: "-228"}, 500);
		});
	}

	//Slideshow TPL11 - TPL16
	if($(".slideshow_script_class").length > 0){

		$(window).load(function() {
			$('.slideshow2').cycle({
				timeout: 8000,
				fx:      'fade', 
				cleartype:true,
				pause:   0,
				pauseOnPagerHover: 0,
				pager:   '.pager',
			});
		});

		$(window).resize(function(){
			$('.slideshow2').cycle('destroy');
			$('.slideshow2').attr('style', '');
			$('.cycle2').attr('style', '');

			$('.slideshow2').cycle({
				timeout: 8000,
				fx:      'fade', 
				cleartype:true,
				pause:   0,
				pauseOnPagerHover: 0,
				pager:   '.pager',
			});

		});

		if ($(window).width() < 769) {
			var imageHeight = $('.slideimage img').height();
			$('.slideshow2').css({'maxHeight': imageHeight + 'px'});
		};

		$(window).resize(function(){
			imageHeight = $('.slideimage img').height();
			if ($(window).width() < 769) {
				$('.slideshow2').css({'maxHeight': imageHeight + 'px'});
			}else{
				$('.slideshow2').css("maxHeight", "");
			}
		});

	}

	//Slideshow TPL17 + TPL18
	if($(".responsiveSlideshow_script_class").length > 0){
		$('.responsive-slider').responsiveSlider({
			autoplay: true,
			interval: 5000,
			transitionTime: 600,
			parallax: true
		});
	}

	if($(".tpl13").length > 0) {
        $(".search_mid, .search_right .slctr:eq(0)").hide();

		if($(".search_open").length > 0) {
			$(".search_open").click(function(){
				if($(".searchTop").hasClass("search_opened")) {
					$(".searchTop").removeClass("search_opened");
					$(".search_mid, .search_right .slctr:eq(0)").hide();
				} else {
					$(".searchTop").addClass("search_opened");
					$(".search_mid, .search_right .slctr:eq(0)").show();
				}
			});
		}
    }

    if($(".newsticker").length > 0){
    	$('.slideshow').cycle({
			timeout: 5000,				
			fx:      'scrollLeft',
			pager:   '.pager',
			cleartype:false,
			pause:   0,
			pauseOnPagerHover: 0
		});
    }

    if($(".ticker_script_class").length > 0){
    	$('.slide').cycle({ 
		    fx:    'scrollLeft', 
		    delay: 10000,
			next:   '.arrow_right', 
			prev:   '.arrow_left'
		});
    }

    //$('.wrapper').after('<link rel="stylesheet" type="text/css" href="https://realityexport.sk/storage/template/css_v2/uni/bootstrap/bootstrap.min.css">');

    $(".formular_config").submit(function(e){
	    var regex = /([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+/;
	    var scrollAction = false;

	    if($("input.email").val() == '' || !regex.test($("input.email").val())) {var scrollAction = true;}

	    if (scrollAction) {
	        var object_position = $("input.email").parent('.forminput').parent('.formline').offset();

	        $("input.email").parent('.forminput').children('span.error').text('Zadajte prosím platný email');

	        $('body,html').animate({
	            scrollTop: object_position.top
	        }, 800);
	        return false;
	    }
    });

    $('.cookieDisclaimer').slideDown("slow");


    //Back To Top Button
	// browser window scroll (in pixels) after which the "back to top" link is shown
	var offset = 300,
	//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
	offset_opacity = 1200,
	//duration of the top scrolling animation (in ms)
	scroll_top_duration = 700,
	//grab the "back to top" link
	$back_to_top = $('.module_back_to_top');

	//hide or show the "back to top" link
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('visible') : $back_to_top.removeClass('visible fade_out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.removeClass('fade_out');
		} else {
			$back_to_top.addClass('fade_out');
		}
	});

	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});
});

//Vytvorí dialóg
function makeDialog(text) {
	$('<div id="dialog"><p>'+text+'</p></div>').dialog({
		modal:true,
		resizable:false,
		draggable:false,
		height: 100,
		buttons:{
		"OK":function(){$(this).dialog("close");}
		}
	});
	return false;
}

function ajaxWindow(url,title,width,height,classa) {
	$('<div class="dialog"></div>').dialog({
		modal:true,
		resizable:false,
		draggable:false,
		position:['center',50],
		width: width,
		height: height,
		title: title,
		dialogClass: classa,
		open:function(){
			$(this).append('<div class="loading" style="display:block">Nahrávam ...</div>');
			$(this).load(url);
			},
		close:function(){
			$(this).dialog('destroy').remove();

		},
	    create:function () {
        $(this).closest(".ui-dialog")
            .addClass("ajaxWindowDialog");
    	}
	});
}

function cookieDisclaimerClose(){
	$('.cookieDisclaimer').slideUp("slow", function() {	
		$('.cookieDisclaimer').remove();
  	});
	var dateOfMyCookie = new Date();
	dateOfMyCookie.setTime(dateOfMyCookie.getTime() + (365 * 24 * 60 * 60 * 1000));
	$.cookie.set("cookieDisclaimerConfirmed", 1,{expires:dateOfMyCookie});
}

function postForm(where) {
	$(".loading").show();

	$.ajax({
		type: "POST",
		url: $(where).attr("action"),
		data: $(where).serialize(),
		success: function(response) {
		try
		{
		//Ak je odpoved cez JSON, tak formulár je v poriadku
		//a vykonám akciu podla JSON pola
		var respJSON=$.parseJSON(response);

		if(respJSON["action"]=="ajaxWindow")
			ajaxWindow(respJSON["url"],'');
		else if(respJSON["action"]=="redirect")
			window.location=respJSON["url"];
		else if(respJSON["action"]=="run")
			eval(respJSON["function"]);
		else if(respJSON["close"]) return;
			$(".dialog").dialog("close");
		}
		catch(e)
		{
			//Inak zobrazím odpoved (teda znovu formulár)
			$(where).parent().html(response);
		}
		},
		error: function(url) {
			alert("Pri odosielaní formuláru nastala chyba. Skúste ešte raz");
		}
	});
	return false;
}

var fixHelper = function(e, ui) {
	ui.children().each(function() {
		var newWidth = $(this).width();
		$(this).width(newWidth);
	});
	return ui;
};


//---SORTABLE---BANNERS//
//plati len pre tabulku s tbody a thead
//fixne sirku stlpcov pri presuvani      
$(function() {
	$( "#sortableBanners  tbody" ).sortable({helper: fixHelper});
	$( "#sortableBanners  tbody" ).disableSelection({helper: fixHelper});
	$( "#sortableBanners  tbody").sortable({                    
		axis: 'y',
		update: function (event, ui) {
			var data = $(this).sortable('serialize');
			var stringPost = data;
			var ordering = new Array();
			$("#sortableBanners  tbody tr").each(function(){                           
				var banner_id = $(this).data("banner_id");

				tmp = parseInt(banner_id);
				ordering.push(tmp);
			});

			var orderingString = ordering.join(",");
			$("#ordering").val(orderingString);
		}
	});
});

//---SORTABLE---MODULES//
//plati len pre tabulku s tbody a thead
//fixne sirku stlpcov pri presuvani      
$(function() {
	$( "#sortableModules  tbody" ).sortable({helper: fixHelper});
	$( "#sortableModules  tbody" ).disableSelection({helper: fixHelper});
	$( "#sortableModules  tbody").sortable({                    
		axis: 'y',
		update: function (event, ui) {
			var data = $(this).sortable('serialize');
			var stringPost = data;
			var ordering = new Array();
			$("#sortableModules  tbody tr").each(function(){                           
				var banner_id = $(this).data("module_id");

				tmp = parseInt(banner_id);
				ordering.push(tmp);
			});

			var orderingString = ordering.join(",");
			$("#ordering").val(orderingString);
		}
	});
});



 //---SORTABLE---GALLERY//
 //plati len pre tabulku s tbody a thead
var element = 'image';
var wrapper = '#sortableGallery';           
$(function() {
	$( wrapper + " tbody" ).sortable({helper: fixHelper});
	$( wrapper + " tbody" ).disableSelection({helper: fixHelper});
	$( wrapper + " tbody").sortable({
		//axis: 'y',
		update: function (event, ui) {
			var data = $(this).sortable('serialize');
			var stringPost = data;
			var imageOrdering = new Array();
			$(wrapper + " tbody tr").each(function(){                           
				var image_id = $(this).data(element + "_id");

				tmp = parseInt(image_id);
				imageOrdering.push(tmp);
			});

			var imageOrderingString = imageOrdering.join(",");
			$("#imageOrdering").val(imageOrderingString);
		}
	});
});

 //---SORTABLE---SLIDESHOW//
 //plati len pre tabulku s tbody a thead
$(function() {
	$( "#sortableSlideshow  tbody" ).sortable({helper: fixHelper});
	$( "#sortableSlideshow  tbody" ).disableSelection({helper: fixHelper});
	$( "#sortableSlideshow  tbody").sortable({                    
		axis: 'y',
		update: function (event, ui) {
			var data = $(this).sortable('serialize');
			var stringPost = data;
			var ordering = new Array();
			$("#sortableSlideshow  tbody tr").each(function(){                           
				var slide_id = $(this).data("slide_id");

				tmp = parseInt(slide_id);
				ordering.push(tmp);
			});

			var orderingString = ordering.join(",");
			$("#ordering").val(orderingString);
		}
	});
});     

 //---SORTABLE---LABELS WEB CONFIG//
 //plati len pre tabulku s tbody a thead
$(function() {
	$( "#sortableLabels  tbody" ).sortable({helper: fixHelper});
	$( "#sortableLabels  tbody" ).disableSelection({helper: fixHelper});
	$( "#sortableLabels  tbody").sortable({                    
		axis: 'y',
		update: function (event, ui) {
			var data = $(this).sortable('serialize');
			var stringPost = data;
			var ordering = new Array();
			$("#sortableLabels  tbody tr").each(function(){                           
				var label_id = $(this).data("label_id");

				//tmp = parseInt(label_id);
				ordering.push(label_id);
			});

			var orderingString = ordering.join(",");
			$(".labelsInputOrdering").val(orderingString);
		}
	});   
	$(".labelsInputReset").click(function(){
		$(".labelsInputOrdering").attr("value","");
		alert("Zmena bude vykonaná až po potvrdení tlačidlom ULOŽIŤ!!!");
	 	//$(".formular_config").submit();
	});  
});     


$(window).load(function() {
	if($(".dynamicBanners_script_class").length > 0){
		$("div#makeMeScrollable").smoothDivScroll({ 
			autoScroll: "onstart", 						
			autoScrollDirection: "endlessloopright", 
			autoScrollStep: 1, 
			autoScrollInterval: 20,	
			startAtElementId: "startAtMe", 
			visibleHotSpots: "always"
		});
	}

	if($('.web_admin_container').length > 0) {
		var adminPosition = $('.web_admin_container').offset();

		$('body,html').animate({
	            scrollTop: adminPosition.top-70
	        }, 800);
	}
});

if($(".dynamic_banners_script").length > 0) {
	$(window).load(function() {
		$("div#makeMeScrollable").smoothDivScroll({ 
			autoScroll: "onstart", 						
			autoScrollDirection: "endlessloopright", 
			autoScrollStep: 1, 
			autoScrollInterval: 20,	
			startAtElementId: "startAtMe", 
			visibleHotSpots: "always"
		});
	});
}