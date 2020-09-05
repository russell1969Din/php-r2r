//
function prepareJson(action) {
	//action: 0 - netahat Total Pocet
	//action: 1 - tahat Total Pocet
	var result = new Object();
	result["tag_id"] = [];

	$(".smartElement").each(function(){
		var thisName = $(this).data("name");
		if(thisName != "total") result[thisName] = $(".smart_"+thisName+"Callback").val().split(",");
	});

	$('.smartsearch input[type=checkbox]').each(function(){
		if($(this).is(':checked')) {
			if($(this).attr('id').indexOf("tag_") >= 0) {
				result["tag_id"].push($(this).val());
			} else {
				result[$(this).attr('id')] = Array($(this).val());
			}
		}
	});

	if( $('.smartsearch #price_from').val() != "" && $.isNumeric($('.smartsearch #price_from').val()) ) {
		result[$('.smartsearch #price_from').attr('name')] = Array($('.smartsearch #price_from').val());
	}
	if( $('.smartsearch #price_to').val() != "" && $.isNumeric($('.smartsearch #price_to').val()) ) {
		result[$('.smartsearch #price_to').attr('name')] = Array($('.smartsearch #price_to').val());
	}
	if( $('.smartsearch #txt-rozloha1').val() != "" && $.isNumeric($('.smartsearch #txt-rozloha1').val()) ) {
		result[$('.smartsearch #txt-rozloha1').attr('name')] = Array($('.smartsearch #txt-rozloha1').val());
	}
	if( $('.smartsearch #txt-rozloha2').val() != "" && $.isNumeric($('.smartsearch #txt-rozloha2').val()) ) {
		result[$('.smartsearch #txt-rozloha2').attr('name')] = Array($('.smartsearch #txt-rozloha2').val());
	}
    

    $('.smartsearch #price_from,.smartsearch #price_to,.smartsearch #txt-rozloha1,.smartsearch #txt-rozloha2').keyup(function(e){
    	result[$(this).attr('id')] = Array($(this).val());
    });
	
	resultJson = JSON.stringify(result);		
	$.get('/getsmartload?smartParams='+resultJson, function( data ) {	  	  	
	  	var resultObject = JSON.parse(data);	  	
	  	$("span#total").html(resultObject['elements']['total']['count']);
	});
}

$(function(){
	
	prepareJson();
	$("form.smartsearch").submit(function(){
		if($("input[name=debug]").length) {} else {
			$(".callbackValues").remove();
		}
	});

	var callback = $("#callback");
	var numbersKeyCodes = [8,48,49,50,51,52,53,54,55,56,57,96,97,98,99,100,101,102,103,104,105];
	$(".multiselect").each(function(){
		thisSelect = $(this); thisLabel = thisSelect.parent().parent().data('label');
		
		thisSelectedText = thisSelect.parent().parent().data('selectedtext');
		if(!thisSelectedText) thisSelectedText = thisSelect.closest('form').data('selectedtext');
		if(!thisSelectedText) thisSelectedText = "# zvolenĂ˝ch";
		
		thisCheckAllText = thisSelect.parent().parent().data('checkalltext');
		if(!thisCheckAllText) thisCheckAllText = thisSelect.closest('form').data('checkalltext');
		if(!thisCheckAllText) thisCheckAllText = "oznaÄŤiĹĄ vĹˇetko";
		
		thisUncheckAllText = thisSelect.parent().parent().data('uncheckalltext');
		if(!thisUncheckAllText) thisUncheckAllText = thisSelect.closest('form').data('uncheckalltext');
		if(!thisUncheckAllText) thisUncheckAllText = "zruĹˇiĹĄ vĹˇetko";

		thisSelectedList = thisSelect.parent().parent().data('selectedlist');
		thisSelectedList = parseInt(thisSelectedList);
		if(!thisSelectedList) {
			thisSelectedList = thisSelect.closest('form').data('selectedlist');
			thisSelectedList = parseInt(thisSelectedList);			
		}
		if(!thisSelectedList) thisUncheckAllText = "zruĹˇiĹĄ vĹˇetko";		

		var singleSelect = $(this).parent().parent().data('singleselect');

		$(this).multiselect({   			   		
	   		minWidth: 60,
	   		multiple: (typeof(singleSelect) != "undefined" && singleSelect ? false : true),
	   		click: function(event, ui){
	      		var thisElement = $(this); var thisId = thisElement.attr("id");

	      		if(thisId == 'smart_district') $(".ui-multiselect-checkboxes input[name=multiselect_smart_region]").closest("li").hide();
	      		
	      		sel = thisElement; 		
	      		if(ui.checked) {
	      			if (typeof(singleSelect) != "undefined" && singleSelect) {
	      				$("."+thisId+"Callback").val(ui.value);
	      			} else {
   						$("."+thisId+"Callback").val($("."+thisId+"Callback").val()+ui.value + ',');
	      			}
	      			if(thisId == 'smart_district') {
	      				
	      				$("#smart_region option").each(function( index ) {
	      					//console.log($(this).val() + "-" + $("#smart_region option[value="+$(this).val()+"]").data("district"));
	      					//console.log(ui.value);
	      					if(ui.value.replace(ui.value.substring(0,1),'')==$("#smart_region option[value="+$(this).val()+"]").data("district")) {
								$(".ui-multiselect-checkboxes input[name=multiselect_smart_region][value="+$(this).val()+"]").closest("li").hide();
								//console.log('zakryl som: '+$(this).val()+'.ui-multiselect-checkboxes input[value='+ui.value+']');
	      					}
	      					//value="+$(this).val()+'
	      				});

	      			}
	      		} else {
	      			var toReplace = $("."+thisId+"Callback").val();
	      			toReplace = toReplace.replace(ui.value + ",", "");
	      			$("."+thisId+"Callback").val(toReplace);
	      			
	      			if(thisId == 'smart_district') {
	      				//console.log('Zrusene: ' + ui.value.replace(ui.value.substring(0,1),'') +' - '+ui.value.substring(0,1));
	      			}
	      		}  
	      		/*Aktualny vyber: */ //console.log($(".smart_districtCallback").val());
                
                /*
	      		var pole = new Array();
	      		pole = $(".smart_districtCallback").val().split(",");
	      		$(pole).each(function(index, value){
	      			if(value) {
	      				//console.log('Item: ');

	      				var localityKey = "";
	      				var cleanValue = value.replace(value.substring(0,1),'');
	      				var localitySymbol = value.substring(0,1);
	      				if(localitySymbol=="d") localityKey = "district";
	      				if(localitySymbol=="c") localityKey = "county";
	      				if(localitySymbol=="s") localityKey = "state";

	      				$("#smart_region option").each(function( ix ) {
	      					//console.log($(this).val() + "-" + $("#smart_region option[value="+$(this).val()+"]").data("district"));
	      					//console.log(ui.value);
	      					
	      					
	      					if(cleanValue==$("#smart_region option[value="+$(this).val()+"]").data(localityKey)) {
								$(".ui-multiselect-checkboxes input[name=multiselect_smart_region][value="+$(this).val()+"]").closest("li").show();
								//console.log('zakryl som: '+$(this).val()+'.ui-multiselect-checkboxes input[value='+ui.value+']');
	      					}
	      					//value="+$(this).val()+'
	      				});

	      			}
	      		});
                */
				if(!$(".smart_districtCallback").val()) $(".ui-multiselect-checkboxes input[name=multiselect_smart_region]").closest("li").show();

	      		prepareJson();     		
	   		},
	   		checkAllText: thisCheckAllText, uncheckAllText: thisUncheckAllText,   		noneSelectedText: thisLabel, selectedText: thisSelectedText,
	   		selectedList: thisSelectedList, show: ['fade', 150], hide: ['fade', 150],
	   		checkAll: function(){		      
			      var thisElement = $(this); var thisId = thisElement.attr("id"); var valueSet = "";
			      $("#"+thisId).each(function(){
			      	  valueSet = valueSet + $(this).val() + ",";
			      });
			      $(".ui-multiselect-checkboxes input[name=multiselect_smart_region]").closest("li").show();
			      $("."+thisId+"Callback").val(valueSet); prepareJson();
			},
			uncheckAll: function(){
			      var thisElement = $(this); var thisId = thisElement.attr("id");		      
			      $(".ui-multiselect-checkboxes input[name=multiselect_smart_region]").closest("li").show();
			      $("."+thisId+"Callback").val(""); prepareJson();
			}			   	
		});

		$('.ui-multiselect-menu').mouseleave(function(){
			$('.smartElement select').each(function(){
				$(this).multiselect('close');
			});
		});
	});
	
	$('.smartSearch_reset').click(function(){
        $(".smartsearch select").multiselect('uncheckAll');
        
        $(".smartsearch select").multiselect().each(function(){
        	$(this).removeClass('selectedSmartElement');
        });

        $('.smartsearch input[type=checkbox]').each(function(){
		if($(this).is(':checked')) {
				$(this).prop( "checked", false );
			}
		});

        prepareJson();
        document.getElementById('txt-rozloha1').value = "";
		document.getElementById('txt-rozloha2').value = "";
		$("#slider-rozloha").slider("values", 0, "");
  		$("#slider-rozloha").slider("values", 1, "");

  		document.getElementById('price_from').value = "";
		document.getElementById('price_to').value = "";
		$("#slider-cena").slider("values", 0, "");
  		$("#slider-cena").slider("values", 1, "");
		
		document.getElementById('search').value = "";
    });

	/* Classou odlisena nadradena kategoria alebo ina vlastnost */
	$("input[name=multiselect_smart_category]").each(function(){
		//console.log($(this).val());
		if($(this).val().substring(0,1) == "c") $(this).next().addClass("boldLabel");
	});
	$("input[name=multiselect_smart_district]").each(function(){
		//console.log($(this).val());
		if($(this).val().substring(0,1) == "c" || $(this).val().substring(0,1) == "s") $(this).next().addClass("boldLabel");
	});

	/*Aktualny vyber: */ //console.log($(".smart_districtCallback").val());
	var pole = new Array();
	if($(".smart_districtCallback").length) pole = $(".smart_districtCallback").val().split(",");
	$(".ui-multiselect-checkboxes input[name=multiselect_smart_region]").closest("li").hide();
	$(pole).each(function(index, value){
		if(value) {      				
			var localityKey = "";
			var cleanValue = value.replace(value.substring(0,1),'');
			var localitySymbol = value.substring(0,1);
			if(localitySymbol=="d") localityKey = "district";
			if(localitySymbol=="c") localityKey = "county";
			if(localitySymbol=="s") localityKey = "state";
			$("#smart_region option").each(function( ix ) {	      					
				if(cleanValue==$("#smart_region option[value="+$(this).val()+"]").data(localityKey)) {
				$(".ui-multiselect-checkboxes input[name=multiselect_smart_region][value="+$(this).val()+"]").closest("li").show();
				//console.log("Zakryl som: .ui-multiselect-checkboxes input[name=multiselect_smart_region][value="+$(this).val()+"]");
				}	      					
			});
		}
	});
	if(!$(".smart_districtCallback").val()) $(".ui-multiselect-checkboxes input[name=multiselect_smart_region]").closest("li").show();

	$('.smartsearch input[type=checkbox]').each(function(){
		$(this).click(function(){
			prepareJson();
		});
	});

	$('.smartsearch #price_from,.smartsearch #price_to,.smartsearch #txt-rozloha1,.smartsearch #txt-rozloha2').keyup(function(e){
    	if ($.inArray(e.keyCode, numbersKeyCodes) >= 0) {
			prepareJson();
    	}
    });
});