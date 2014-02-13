/**
 * TinyMCE Enlighter Plugin - Converts Shortcodes and Pre Tags between Visual and Text Modes
 */
(function(){
	// store current mode to lock content events
	var _mode = null;
	
	// register plugin
	tinymce.PluginManager.add('enlighter', function(editor, url){
		// visual mode -> text mode
		editor.onSetContent.add(function(ed, o){
			console.log("set content");
			
			// user mode change ?
			if (_mode != null){
				
			}
			
			// mode reset
			_mode = null;
		});

		// text mode -> visual mode
		editor.onGetContent.add(function(ed, o){
			console.log("get content");
			
			// user mode change ?
			if (_mode != null){
				// convert shortcode to html
				
				
			}
			
			// mode reset
			_mode = null;
		});
	});

	// overwrite the switchto function to observer state
	window.switchEditors.switchto = (function(el){
		var aid = el.id, l = aid.length, id = aid.substr(0, l - 5);
		_mode = aid.substr(l - 4);
		console.log("switchto: ", _mode);
		this.go(id, _mode);
	});
})();
