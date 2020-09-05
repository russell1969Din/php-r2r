$(document).ready(function() {
 	var zaujemOInformacie = 'Mám záujem o ďalšie informácie. ';
 	var zaujemOObhliadku = 'Mám záujem o obhliadku nehnuteľnosti. ';
 	var navrhujemCenu = 'Navrhujem cenu ... EUR. ';
 	//console.log($("#language").val());
 	if($("#language").val() == 'en') {
		zaujemOInformacie = 'I am interested in more information. ';
	 	zaujemOObhliadku = 'I am interested in property watch out. ';
	 	navrhujemCenu = 'I am placing bid of ... EUR. ';	 		
 	}
 	if($("#language").val() == 'de') {
		zaujemOInformacie = 'Ich interessiere mich für weitere Informationen. ';
	 	zaujemOObhliadku = 'Ich interessiere mich für eine Immobilienbesichtigung. ';
	 	navrhujemCenu = 'Preisvorschlag ... EUR. ';	 		
 	}

 	$("#dalsieInfo").click(function(){
		if(this.checked) {$("#text").val($("#text").val()+zaujemOInformacie);}
		else {$("#text").val($("#text").val().replace(zaujemOInformacie,""));}
	});
	$("#obhliadka").click(function(){
		if(this.checked) {$("#text").val($("#text").val()+zaujemOObhliadku);}
		else {$("#text").val($("#text").val().replace(zaujemOObhliadku,""));}
	});
    $("#navrhujem").click(function(){
		if(this.checked) {$("#text").val($("#text").val()+navrhujemCenu);}
		else {$("#text").val($("#text").val().replace(navrhujemCenu,""));}
	});

	if( $(".carousel_script_class").length > 0 ){
		$("#mycarousel").jcarousel();
	}

  	if ($('.detail_hslide').length > 0) {
		hs.graphicsDir = '/storage/js/highslide/graphics/';
		hs.align = 'center';
		hs.transitions = ['expand', 'crossfade'];
		hs.fadeInOut = true;
		hs.dimmingOpacity = 0.8;
		hs.wrapperClassName = 'borderless floating-caption';
		hs.captionEval = 'this.thumb.alt';
		hs.marginLeft = 15; // make room for the thumbstrip
		hs.marginBottom = 80 // make room for the controls and the floating caption
		hs.numberPosition = 'caption';
		hs.lang.number = '%1/%2';
	 
		hs.addSlideshow({
			//slideshowGroup: 'group1',
			interval: 5000,
			repeat: false,
			useControls: true,
		overlayOptions: {
			opacity: .75,
			position: 'bottom center',
			hideOnMouseOut: true
		}
		});
	 
		// Add the simple close button
		hs.registerOverlay({
			html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
			position: 'top right',
			fade: 2 // fading the semi-transparent overlay looks bad in IE
		});
	}

	if ($('.detail_fancybox').length > 0) {
		$('a[rel=photogallery]').fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'titlePosition' 	: 'over',
			'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
			    return '<span id="fancybox-title-over"><span>' + (currentIndex + 1) + ' / ' + currentArray.length + '</span><span> ' + title + '</span></span>';
			}
		});
	}

	// The slider being synced must be initialized first
	if ($('.flexslider_script_class').length > 0) {
		$('#carousel').flexslider({
			animation: "slide",
			controlNav: true,
			animationLoop: false,
			slideshow: false,
			itemWidth: 118,
			itemMargin: 5,
			asNavFor: '#slider'
		});

		$('#slider').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			sync: "#carousel"
		});
	}

	if ($('.flexslider_script_class').length > 0) {
		$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
			event.preventDefault();
			$(this).ekkoLightbox();
		});
	}

});

function mapDialog(gpsX,gpsY,address) {
	var kdeSaNachadza = 'Kde sa nehnuteľnosť nachádza?';
	
	if($("#lng").val()=='en') kdeSaNachadza = 'Where is the property located?';
	if($("#lng").val()=='de') kdeSaNachadza = 'Wo befindet sich die Immobilie?';
	//dodatocne doplnenie jazyka - cez toto IDcko, pretoze #lng pri primarnom jazyku vracia prazdny string a tak neviem zistit aktualny jazyk
	//#lang je vo sablone module.copyright.html daneho webu (lang posielany z kontrolera v cmsV3 -> modCopyright)
	if($("#currentLang").data("lang") == "ro_RO") kdeSaNachadza = 'Unde este situată proprietatea?';

	var canvas='map_canvas'+Math.random();
	$('<div id="dialog"><div id="'+canvas+'" style="width:500px;height:500px;"></div></div>').dialog({
		open:function(){drawMap(gpsX,gpsY,address,canvas);},
		modal:true,
		width:530,
		height:555,
		title:kdeSaNachadza,
		resizable: false
	});
}

/*function drawMap(gpsX,gpsY,address,canvas) {
	gpsX=gpsX.toString().replace(",",".");
	gpsY=gpsY.toString().replace(",",".");

	var latlng = new google.maps.LatLng(gpsX,gpsY);
	var myOptions = {
		zoom: 14,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById(canvas),
	myOptions);
	if(gpsX!=0 && gpsY!=0)
	{
		var marker = new google.maps.Marker({
			position: latlng,
			map: map
		});
	}
	else
	{
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});
		  }
		});
	}
}*/

function drawMap(gpsX,gpsY,address,canvas) {

	gpsX=gpsX.toString().replace(",",".");
	gpsY=gpsY.toString().replace(",",".");

	map = new OpenLayers.Map(canvas);
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat( gpsY, gpsX )
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            map.getProjectionObject() // to Spherical Mercator Projection
          );
          
    var zoom=16;

    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);
    
    markers.addMarker(new OpenLayers.Marker(lonLat));
    
    map.setCenter (lonLat, zoom);
    
}

function mamZaujemForm() {
	var zadajteMeno = "zadajte meno";
	var zadajteEmail = "zadajte e-mail";
	var zadajteSpravnyEmail = "zadajte správny e-mail";
	var zadajteText = "zadajte text";

	if($("#language") == 'en') {
		zadajteMeno = "enter your name";
		zadajteEmail = "enter your e-mail";
		zadajteSpravnyEmail = "enter correct e-mail address";
		zadajteText = "enter your message";
	}

	var regex = /([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+/;

	if($("#name").val()=="") {alert(zadajteMeno);return false;}
	else if($("#posta").val()=="") {alert(zadajteEmail);return false;}
	else if(!regex.test($("#posta").val())) {alert(zadajteSpravnyEmail);return false;}
	else if($("#text").val()=="") {alert(zadajteText);return false;}  
	return true;
}
