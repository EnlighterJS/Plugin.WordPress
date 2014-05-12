/*!
---
name: Enlighter Settings Page
description: ThemeCustomizer setup
authors:
  - Andi Dittrich

requires:
  - jQuery
...
*/
(function(themeData){
	// initialize
	jQuery(document).ready(function($){
		// initialize tooltipps
		// jQuery(document).tooltip();
		
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
		// initialize colors
		jQuery('.EnlighterJSColorChooser').each(function(){
			// get element color value
			var color = jQuery(this).val();
			
			// color available ?
			if (color.length > 0){
				jQuery(this).css('background-color', color);
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
			if (!themeData[theme]){
				return;
			}
			
			// set font styles
			jQuery('#enlighter-customFontFamily').val(themeData[theme].basestyle['font-family']);	
			jQuery('#enlighter-customFontSize').val(themeData[theme].basestyle['font-size']);	
			jQuery('#enlighter-customLineHeight').val(themeData[theme].linestyle['line-height']);
			setElementColor('#enlighter-customFontColor', themeData[theme].basestyle['color']);

			// set line-number styles
			jQuery('#enlighter-customLinenumberFontFamily').val(themeData[theme].linestyle['font-family']);	
			jQuery('#enlighter-customLinenumberFontSize').val(themeData[theme].linestyle['font-size']);	
			setElementColor('#enlighter-customLinenumberFontColor', themeData[theme].linestyle['color']);
			
			// set raw font styles
			jQuery('#enlighter-customRawFontFamily').val(themeData[theme].rawstyle['font-family']);	
			jQuery('#enlighter-customRawFontSize').val(themeData[theme].rawstyle['font-size']);	
			jQuery('#enlighter-customRawLineHeight').val(themeData[theme].rawstyle['line-height']);
			setElementColor('#enlighter-customRawFontColor', themeData[theme].rawstyle['color']);
			setElementColor('#enlighter-customRawBackgroundColor', themeData[theme].rawstyle['background-color']);
			
			// set special styles
			jQuery('#enlighter-customLineHighlightColor').val(themeData[theme].special.highlightColor);
			setElementColor('#enlighter-customLineHighlightColor', themeData[theme].special.highlightColor);
			jQuery('#enlighter-customLineHoverColor').val(themeData[theme].special.hoverColor);
			setElementColor('#enlighter-customLineHoverColor', themeData[theme].special.hoverColor);
			
			// set token styles
			// format: foreground color, background color, text-decoration, font-weight, font-style 
			jQuery.each(themeData[theme].tokens, function(key, value){
				// foreground color
				setElementColor('#enlighter-custom-color-' + key, value[0]);
				
				// background color
				setElementColor('#enlighter-custom-bgcolor-' + key, value[1]);
				
				// text decoration
				jQuery('#enlighter-custom-decoration-' + key).val((value[2] ? value[2] : 'normal'));

				// change dropdown selection if value is set
				if (value[3] && value[4]){
					jQuery('#enlighter-custom-fontstyle-' + key).val('bolditalic');
				}else if (value[3]){
					jQuery('#enlighter-custom-fontstyle-' + key).val('bold');
				}else if (value[4]){
					jQuery('#enlighter-custom-fontstyle-' + key).val('italic');
				}else{
					jQuery('#enlighter-custom-fontstyle-' + key).val('normal');
				}
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
})(Enlighter_ThemeStyles);
