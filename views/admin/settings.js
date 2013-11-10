(function(){
	// initialize
	jQuery(document).ready(function($){
		/**
		 * THEME COLORIZER - COLORPICKER
		 */
		// colorpicker
		jQuery('.EnlighterJSColorChooser').ColorPicker({
			onSubmit : function(hsb, hex, rgb, el){
				jQuery(el).val('#' + hex);
				jQuery(el).css('background-color', '#' + hex);
				jQuery(el).ColorPickerHide();
			},
			onBeforeShow : function(){
				jQuery(this).ColorPickerSetColor(this.value);
			}
		});

		// advanced color settings
		jQuery('#enlighter-defaultTheme').change(function(){
			var value = jQuery('#enlighter-defaultTheme').val();

			// display section only in custom mode !
			if (value == 'wpcustom'){
				jQuery('#EnlighterJSThemeCustomizer').show();
			}else{
				jQuery('#EnlighterJSThemeCustomizer').hide();
			}
		});

		// initially trigger event
		jQuery('#enlighter-defaultTheme').change();

		/**
		 * THEME CUSTOMIZER
		 */
		jQuery('#enlighter_loadBasicTheme').click(function(){
			// get selected theme
			var theme = jQuery('#enlighter-customThemeBase').val().trim();
			
			// theme data avialable ?
			if (!ThemeStyles[theme]){
				return;
			}
			
			// set font styles
			jQuery('#enlighter-customFontFamily').val(ThemeStyles[theme].sourcefont[0]);	
			jQuery('#enlighter-customFontSize').val(ThemeStyles[theme].sourcefont[1]);	
			jQuery('#enlighter-customLineHeight').val(ThemeStyles[theme].sourcefont[2]);
			setElementColor('#enlighter-customFontColor', ThemeStyles[theme].sourcefont[3]);

			// set line-number styles
			jQuery('#enlighter-customLinenumberFontFamily').val(ThemeStyles[theme].linenumbers[0]);	
			jQuery('#enlighter-customLinenumberFontSize').val(ThemeStyles[theme].linenumbers[1]);	
			jQuery('#enlighter-customLinenumberLineHeight').val(ThemeStyles[theme].linenumbers[2]);
			setElementColor('#enlighter-customLinenumberFontColor', ThemeStyles[theme].linenumbers[3]);
			
			// set special styles
			jQuery('#enlighter-customLineHighlightColor').val(ThemeStyles[theme].special.highlightColor);
			setElementColor('#enlighter-customLineHighlightColor', ThemeStyles[theme].special.highlightColor);
			jQuery('#enlighter-customLineHoverColor').val(ThemeStyles[theme].special.hoverColor);
			setElementColor('#enlighter-customLineHoverColor', ThemeStyles[theme].special.hoverColor);
			
			// set token styles
			jQuery.each(ThemeStyles[theme].tokens, function(key, value){
				// foreground color
				setElementColor('#enlighter-custom-color-' + key, value[0]);
				
				// background color
				setElementColor('#enlighter-custom-bgcolor-' + key, value[3]);
				
				jQuery('#enlighter-custom-fontstyle-' + key).val(value[1]);			
				jQuery('#enlighter-custom-decoration-' + key).val(value[2]);
			});

		});
	});
	
	// set element color
	var setElementColor = (function(id, value){
		// value available ?
		if (value){
			jQuery(id).val(value);
			jQuery(id).css('background-color', value);
		}else{
			jQuery(id).val('');
			jQuery(id).css('background-color', 'transparent');
		}
	});

	// theme style data
	// extract from EnlighterJS CSS Sources
	var ThemeStyles = {
		'standard': {
			/* font family, font-size, line-height, font-color */
			'sourcefont': ['Monaco, Courier, Monospace', '12px', '16px', '#000000'],
			
			/* font family, font-size, line-height, font-color */
			'linenumbers': [null, '10px', null, '#939393'],
			
			/* special stykes */
			'special': {
				hoverColor: '#F4F8FC',
				highlightColor: '#F4F8FC'
			},
						
			'tokens': {
				/* foreground color, text style, text line style, background color */
				'kw1': ['#1b609a', null, null, null],
				'kw2': ['#9a6f1b', null, null, null],
				'kw3': ['#784e0c', null, null, null],
				'kw4': ['#9a6f1b', null, null, null],
				'co1': ['#888888', null, null, null],
				'co2': ['#888888', null, null, null],
				'st0': ['#489a1b', null, null, null],
				'st1': ['#489a1b', null, null, null],
				'st2': ['#489a1b', null, null, null],
				'nu0': ['#70483d', null, null, null],
				'me0': ['#666666', null, null, null],
				'me1': ['#666666', null, null, null],
				'br0': ['#444444', null, null, null],
				'sy0': ['#444444', null, null, null],
				'es0': ['#444444', null, null, null],
				're0': ['#784e0c', null, null, null],
				'de1': [null, null, null, null],
				'de2': [null, null, null, null]
			}
		},
		'git': {
			'sourcefont': ['Courier, Monospace', '12px', '16px', '#000000'],
			'linenumbers': [null, '10px', null,  '#939393'],
			'special': {
				hoverColor: '#ffffcc',
				highlightColor: '#fffff2'
			},
			'tokens': {
				'kw1': ['#000000', 'bold', null, null],
				'kw2': ['#0086b3', null, null, null],
				'kw3': ['#445588', 'bold', null, null],
				'kw4': ['#990073', null, null, null],
				'co1': ['#999988', 'italic', null, null],
				'co2': ['#999988', 'italic', null, null],
				'st0': ['#dd1144', null, null, null],
				'st1': ['#dd1144', null, null, null],
				'st2': ['#dd1144', null, null, null],
				'nu0': ['#009999', null, null, null],
				'me0': ['#0086b3', null, null, null],
				'me1': ['#0086b3', null, null, null],
				'br0': ['#777', null, null, null],
				'sy0': ['#777', null, null, null],
				'es0': ['#777', null, null, null],
				're0': ['#784e0c', null, null, null],
				'de1': ['#CF6A4C', null, null, null],
				'de2': ['#CF6A4C', null, null, null]
			}
		},
		'mocha': {
			'sourcefont': ['Monaco, Courier, Monospace', '12px', '16px', '#f8f8f8'],
			'linenumbers': [null, '10px', null, '#939393'],
			'special': {
				hoverColor: '#423F43',
				highlightColor: '#423F43'
			},
			'tokens': {
				'kw1': ['#CDA869', null, null, null],
				'kw2': ['#CACD69', null, null, null],
				'kw3': ['#afc4db', null, null, null],
				'kw4': ['#CF6A4C', null, null, null],
				'co1': ['#5F5A60', 'italic', null, null],
				'co2': ['#5F5A60', 'italic', null, null],
				'st0': ['#8F9D6A', null, null, null],
				'st1': ['#8F9D6A', null, null, null],
				'st2': ['#DDF2A4', null, null, null],
				'nu0': ['#5B97B5', null, null, null],
				'me0': ['#C5AF75', null, null, null],
				'me1': ['#C5AF75', null, null, null],
				'br0': ['#777', null, null, null],
				'sy0': ['#777', null, null, null],
				'es0': ['#777', null, null, null],
				're0': ['#784e0c', null, null, null],
				'de1': ['#CF6A4C', null, null, null],
				'de2': ['#CF6A4C', null, null, null]
			}
		},
		'panic': {
			'sourcefont': ['Monaco, Courier, Monospace', '12px', '16px', '#000000'],
			'linenumbers': [null, '10px', null, '#939393'],
			'special': {
				hoverColor: '#F4F8FC',
				highlightColor: '#F4F8FC'
			},
			'tokens': {
				'kw1': ['#9F0050', null, null, null],
				'kw2': ['#9F0050', null, null, null],
				'kw3': ['#9a6c00', null, null, null],
				'kw4': ['#9F0050', null, null, null],
				'co1': ['#00721F', 'italic', null, null],
				'co2': ['#00721F', 'italic', null, null],
				'st0': ['#EF7300', null, null, null],
				'st1': ['#8A000F', null, null, null],
				'st2': ['#8A000F', null, null, null],
				'nu0': ['#1600FF', null, null, null],
				'me0': ['#00417f', null, null, null],
				'me1': ['#00417f', null, null, null],
				'br0': ['#000000', null, null, null],
				'sy0': ['#000000', null, null, null],
				'es0': ['#000000', null, null, null],
				're0': ['#784e0c', null, null, null],
				'de1': ['#A00083', null, null, null],
				'de2': ['#A00083', null, null, null]
			}
		},
		'tutti': {
			'sourcefont': ['Monaco, Courier, Monospace', '12px', '16px', '#000000'],
			'linenumbers': [null, '10px', null, '#939393'],
			'special': {
				hoverColor: '#F4F8FC',
				highlightColor: '#F4F8FC'
			},
			'tokens': {
				'kw1': ['#8600c9', null, null, null],
				'kw2': ['#3a1d72', 'bold', null, null],
				'kw3': ['#4F9FCF', null, null, null],
				'kw4': ['#4F9FCF', null, null, null],
				'co1': ['#bbb', null, null, null],
				'co2': ['#bbb', null, null, null],
				'st0': ['#bc670f', null, null, 'rgba(251,233,173,0.1)'],
				'st1': ['#bc670f', null, null, 'rgba(251,233,173,0.1)'],
				'st2': ['#bc670f', null, null, 'rgba(251,233,173,0.1)'],
				'nu0': ['#6700b9', null, null, null],
				'me0': ['#000', null, null, null],
				'me1': ['#6eb13f', 'bold', null, null],
				'br0': ['#4f4f4f', null, null, null],
				'sy0': ['#626fc9', null, null, null],
				'es0': ['#4f4f4f', null, null, null],
				're0': ['#d44950', null, null, null],
				'de1': ['#6eb13f', null, null, null],
				'de2': ['#6eb13f', null, null, null]
			}
		},
		'twilight': {
			'sourcefont': ['Monaco, Courier, Monospace', '12px', '16px', '#f8f8f8'],
			'linenumbers': [null, '10px', null, '#939393'],
			'special': {
				hoverColor: '#202021',
				highlightColor: '#202021'
			},
			'tokens': {
				'kw1': ['#CDA869', null, null, null],
				'kw2': ['#F9EE98', null, null, null],
				'kw3': ['#6F87A8', null, null, null],
				'kw4': ['#E96546', null, null, null],
				'co1': ['#5F5A60', null, null, null],
				'co2': ['#bbb', null, null, null],
				'st0': ['#8F9657', null, null, null],
				'st1': ['#8F9657', null, null, null],
				'st2': ['#8F9657', null, null, null],
				'nu0': ['#CF6745', null, null, null],
				'me0': ['#fff', null, null, null],
				'me1': ['#fff', null, null, null],
				'br0': ['#fff', null, null, null],
				'sy0': ['#fff', null, null, null],
				'es0': ['#fff', null, null, null],
				're0': ['#E57A27', null, null, null],
				'de1': ['#fff', null, null, null],
				'de2': ['#fff', null, null, null]
			}
		}
	};

})();
