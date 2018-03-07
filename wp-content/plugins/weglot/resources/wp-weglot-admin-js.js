jQuery(function($) {

generateWGWidgetCode();
$( "select[name=original_l]" ).change(function() {
	generateWGWidgetCode();
});
$( "select[name=type_flags]" ).change(function() {
	generateWGWidgetCode();
});
$( "input[name=destination_l]" ).blur(function() {
	generateWGWidgetCode();
});
$('input[name=with_flags]').change(function() {
	generateWGWidgetCode();
});
$('input[name=with_name]').change(function() {
	generateWGWidgetCode();
});
$('input[name=is_dropdown]').change(function() {
	generateWGWidgetCode();
});
$('input[name=is_fullname]').change(function() {
	generateWGWidgetCode();
});
$('input[name=is_menu]').change(function() {
	if(this.checked) {
		$('input[name=is_dropdown]').prop('checked', false);
		generateWGWidgetCode(); 
    }
});
$('textarea[name=override_css]').blur(function() {
	var style_value = $(this).val();
	if(style_value!="") {
		var style = $('<style wgstyle>'+$(this).val()+'</style>');
		$('style[wgstyle]').remove();
		$('html > body').append(style);
		generateWGWidgetCode();
	}
	else {
		$('style[wgstyle]').remove();
	}
});

$( "select.flag-en-type, select.flag-es-type, select.flag-pt-type, select.flag-fr-type, select.flag-ar-type" ).change(function() {
	refreshFlagCSS();
	generateWGWidgetCode();
});

$('.wgclose-btn').click(function() {
	$('.wgbox-blur').hide();
});
$('.flag-style-openclose').click(function() {
	$('.flag-style-wrapper').toggle();
});
$('input[name=project_key]').blur(function() {
	var key = $(this).val();
	$.getJSON( "https://weglot.com/api/user-info?api_key="+key, function( data ) {
		$('.wg-keyres').remove();
		$('input[name=project_key]').after('<span class="wg-keyres wg-okkey"></span>');
		$('.wg-widget-option-form input[type=submit]').prop('disabled', false);
	}).fail(function() {
		$('.wg-keyres').remove();
		$('input[name=project_key]').after('<span class="wg-keyres wg-nokkey"></span>')
		$('.wg-widget-option-form input[type=submit]').prop('disabled', true);
	  });
});
function generateWGWidgetCode() {
	var original = $( "select[name=original_l]" ).val();
	var destination = $( "input[name=destination_l]" ).val();
	var dests = destination.split(',');
	var list='';
	
	var flag_class =  "";
	if($('input[name=with_flags]').is(':checked')) {
		flag_class += "wg-flags ";
		flag_class += ($("select[name=type_flags]").val()=="0") ? "":"flag-"+$( "select[name=type_flags]" ).val()+" ";
	}
	
	
	if(destination.length>1) {
		list +='<ul>';
		for(var i=0;i<dests.length;i++) { 
				var d = dests[i];
				var l_name = $('input[name=with_name]').is(':checked') ? ($('input[name=is_fullname]').is(':checked') ? getLangNameFromCode(d,false):d.toUpperCase()):"";
				list += '<li class="wg-li '+flag_class+d+'"><a href="#">'+l_name+'</a></li>';
		}
		list +='</ul>';
	}
	
	var current_name = $('input[name=with_name]').is(':checked') ? ($('input[name=is_fullname]').is(':checked') ? getLangNameFromCode(original,false):original.toUpperCase()):"";
	
	if($('input[name=is_dropdown]').is(':checked')) {
		var opt_class = "wg-drop";
	}
	else { 
		var opt_class = "wg-list";
	}
	
	
	
	var style = $('<style wgstyle1>'+$('textarea[name=flag_css]').text()+'</style>');
	$('style[wgstyle1]').remove();
	$('html > body').append(style);
	
				
	var button = '<aside id="weglot_switcher" wg-notranslate class="'+opt_class+' country-selector closed" onclick="openClose(this);"><div class="wgcurrent wg-li '+flag_class+original+'"><a href="javascript:void(0);">'+current_name+'</a></div>'+list+'</aside>';
	$(".wg-widget-preview").html(button);
}

function updateDestInput() {

	var l = $("#select-lto").val();
	if(l) {
        $("#destination_input_hidden").val(l.join(','));
	}
	else {
        $("#destination_input_hidden").val("");
	}
}

function refreshFlagCSS() {
	var en_flags = new Array();
	var es_flags = new Array();
	var pt_flags = new Array();
	var fr_flags = new Array();
	var ar_flags = new Array();

	en_flags[1] = [3570,7841,48,2712];
	en_flags[2] = [3720,449,3048,4440];
	en_flags[3] = [3840,1281,2712,4224];
	en_flags[4] = [3240,5217,1224,2112];
	en_flags[5] = [4050,3585,1944,2496];
	en_flags[6] = [2340,3457,2016,2016];

	es_flags[1] = [4320,4641,3144,3552];
	es_flags[2] = [3750,353,2880,4656];
	es_flags[3] = [4200,1601,2568,3192];
	es_flags[4] = [3990,5793,1032,2232];
	es_flags[5] = [5460,897,4104,3120];
	es_flags[6] = [3810,7905,216,3888];
	es_flags[7] = [3630,8065,192,2376];
	es_flags[8] = [3780,1473,2496,4104];
	es_flags[9] = [6120,2145,4680,2568];
	es_flags[10] = [4440,3009,3240,1176];
	es_flags[11] = [5280,1825,3936,2976];
	es_flags[12] = [4770,2081,3624,1008];
	es_flags[13] = [4080,3201,2160,2544];
	es_flags[14] = [4590,5761,3432,624];
	es_flags[15] = [4350,2209,3360,2688];
	es_flags[16] = [5610,5249,3168,528];
	es_flags[17] = [5070,1729,3792,2952];
	es_flags[18] = [6870,5953,96,3408];
	es_flags[19] = [4020,5697,1056,1224];
	
	pt_flags[1] = [1740,5921,528,3504];

    fr_flags[1] = [2760,736,2856,4416];
    fr_flags[2] = [3840,1280,2712,4224];
    fr_flags[3] = [5700,7201,5016,2400];
    fr_flags[4] = [2220,4160,1632,1944];

    ar_flags[1] = [1830,129,3096,5664];
    ar_flags[2] = [5100,2177,3840,2904];
    ar_flags[3] = [4890,3425,3648,2136];
    ar_flags[4] = [1320,3681,1896,4080];
    ar_flags[5] = [1260,3841,1824,1200];
    ar_flags[6] = [1020,3969,1608,312];
    ar_flags[7] = [4800,4065,3600,72];
    ar_flags[8] = [4710,4865,3504,480];
    ar_flags[9] = [6720,5984,5112,3792];
    ar_flags[10] = [4500,7233,3288,1800];
    ar_flags[11] = [720,7522,384,3936];
    ar_flags[12] = [690,7745,336,1104];
    ar_flags[13] = [600,8225,120,1272];

	var enval = $("select.flag-en-type").val();
	var esval = $("select.flag-es-type").val();
	var ptval = $("select.flag-pt-type").val();
	var frval = $("select.flag-fr-type").val();
	var arval = $("select.flag-ar-type").val();

	var en_style = enval<=0 ? "":".wg-li.en a:before { background-position: -"+en_flags[enval][0]+"px 0; } .wg-li.flag-1.en a:before { background-position: -"+en_flags[enval][1]+"px 0; } .wg-li.flag-2.en a:before { background-position: -"+en_flags[enval][2]+"px 0; } .wg-li.flag-3.en a:before { background-position: -"+en_flags[enval][3]+"px 0; } ";
	var es_style = esval<=0 ? "":".wg-li.es a:before { background-position: -"+es_flags[esval][0]+"px 0; } .wg-li.flag-1.es a:before { background-position: -"+es_flags[esval][1]+"px 0; } .wg-li.flag-2.es a:before { background-position: -"+es_flags[esval][2]+"px 0; } .wg-li.flag-3.es a:before { background-position: -"+es_flags[esval][3]+"px 0; } ";
	var pt_style = ptval<=0 ? "":".wg-li.pt a:before { background-position: -"+pt_flags[ptval][0]+"px 0; } .wg-li.flag-1.pt a:before { background-position: -"+pt_flags[ptval][1]+"px 0; } .wg-li.flag-2.pt a:before { background-position: -"+pt_flags[ptval][2]+"px 0; } .wg-li.flag-3.pt a:before { background-position: -"+pt_flags[ptval][3]+"px 0; } ";
	var fr_style = frval<=0 ? "":".wg-li.fr a:before { background-position: -"+fr_flags[frval][0]+"px 0; } .wg-li.flag-1.fr a:before { background-position: -"+fr_flags[frval][1]+"px 0; } .wg-li.flag-2.fr a:before { background-position: -"+fr_flags[frval][2]+"px 0; } .wg-li.flag-3.fr a:before { background-position: -"+fr_flags[frval][3]+"px 0; } ";
	var ar_style = arval<=0 ? "":".wg-li.ar a:before { background-position: -"+ar_flags[arval][0]+"px 0; } .wg-li.flag-1.ar a:before { background-position: -"+ar_flags[arval][1]+"px 0; } .wg-li.flag-2.ar a:before { background-position: -"+ar_flags[arval][2]+"px 0; } .wg-li.flag-3.ar a:before { background-position: -"+ar_flags[arval][3]+"px 0; } ";

	$('textarea[name=flag_css]').text(en_style+es_style+pt_style+fr_style+ar_style);
}
function getLangNameFromCode (original,english) {
	switch (original) {
		case "sq":
			return english ? "Albanian":"Shqip";	
		case "en":
			return english ? "English":"English";
		case "ar":
			return english ? "Arabic":"‏العربية‏";
		case "hy":
			return english ? "Armenian":"հայերեն";			
		case "az":
			return english ? "Azerbaijani":"Azərbaycan dili";	
		case "af":
			return english ? "Afrikaans":"Afrikaans";
		case "eu":
			return english ? "Basque":"Euskara";	
		case "be":
			return english ? "Belarusian":"Беларуская";	
		case "bg":
			return english ? "Bulgarian":"български";	
		case "bs":
			return english ? "Bosnian":"Bosanski";	
		case "cy":
			return english ? "Welsh":"Cymraeg";				
		case "vi":
			return english ? "Vietnamese":"Tiếng Việt";				
		case "hu":
			return english ? "Hungarian":"Magyar";				
		case "ht":
			return english ? "Haitian":"Kreyòl ayisyen";				
		case "gl":
			return english ? "Galician":"Galego";
		case "nl":
			return english ? "Dutch":"Nederlands";		
		case "el":
			return english ? "Greek":"Ελληνικά";			
		case "ka":
			return english ? "Georgian":"ქართული";
		case "da":
			return english ? "Danish":"Dansk";
		case "he":
			return english ? "Hebrew":"עברית";
		case "id":
			return english ? "Indonesian":"Bahasa Indonesia";				
		case "ga":
			return english ? "Irish":"Gaeilge";	
		case "it":
			return english ? "Italian":"Italiano";	
		case "is":
			return english ? "Icelandic":"Íslenska";	
		case "es":
			return english ? "Spanish":"Español";			
		case "kk":
			return english ? "Kazakh":"Қазақша";				
		case "ca":
			return english ? "Catalan":"Català";	
		case "ky":
			return english ? "Kyrgyz":"кыргызча";	
		case "zh":
			return english ? "Simplified Chinese":"中文 (简体)";
		case "tw":
			return english ? "Traditional Chinese":"中文 (繁體)";
		case "ko":
			return english ? "Korean":"한국어";
		case "lv":
			return english ? "Latvian":"Latviešu";	
		case "lt":
			return english ? "Lithuanian":"Lietuvių";	
		case "mg":
			return english ? "Malagasy":"Malagasy";	
		case "ms":
			return english ? "Malay":"Bahasa Melayu";
		case "mt":
			return english ? "Maltese":"Malti";	
		case "mk":
			return english ? "Macedonian":"Македонски";	
		case "mn":
			return english ? "Mongolian":"Монгол";	
		case "de":
			return english ? "German":"Deutsch";
		case "no":
			return english ? "Norwegian":"Norsk";
		case "fa":
			return english ? "Persian":"فارسی";	
		case "pl":
			return english ? "Polish":"Polski";
		case "pt":
			return english ? "Portuguese":"Português";		
		case "ro":
			return english ? "Romanian":"Română";	
		case "ru":
			return english ? "Russian":"Русский";		
		case "sr":
			return english ? "Serbian":"Српски";	
		case "sk":
			return english ? "Slovak":"Slovenčina";	
		case "sl":
			return english ? "Slovenian":"Slovenščina";	
		case "sw":
			return english ? "Swahili":"Kiswahili";	
		case "tg":
			return english ? "Tajik":"Тоҷикӣ";	
		case "th":
			return english ? "Thai":"ภาษาไทย";	
		case "tl":
			return english ? "Tagalog":"Tagalog";	
		case "tt":
			return english ? "Tatar":"Tatar";
		case "tr":
			return english ? "Turkish":"Türkçe";
		case "uz":
			return english ? "Uzbek":"O'zbek";	
		case "uk":
			return english ? "Ukrainian":"Українська";	
		case "fi":
			return english ? "Finnish":"Suomi";
		case "fr":
			return english ? "French":"Français";	
		case "hr":
			return english ? "Croatian":"Hrvatski";	
		case "cs":
			return english ? "Czech":"Čeština";	
		case "sv":
			return english ? "Swedish":"Svenska";	
		case "et":
			return english ? "Estonian":"Eesti";	
		case "ja":
			return english ? "Japanese":"日本語";	
		case "hi":
			return english ? "Hindi":"हिंदी";
		case "ur":
			return english ? "Urdu":"اردو";
	}
}

$selectL = $('#select-lto').selectize({
	plugins: ['remove_button', 'drag_drop'],
	onChange: function () {
        updateDestInput();
        setTimeout(function(){ generateWGWidgetCode(); }, 1);
	},
	onDropdownOpen: function () {
		$(".selectize-dropdown-content .option").show();
		var original = $("select[name=original_l]").val();
		if (original) {
			$(".selectize-dropdown-content .option[data-value='" + original + "']").hide()
		}
	}
});
$selectL = $selectL[0].selectize;

});