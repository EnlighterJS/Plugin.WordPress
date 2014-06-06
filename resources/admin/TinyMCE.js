/*!
---
name: EnlighterJS.TinyMCE
description: TinyMCE Editor Plugin for WordPress

license: MIT-Style X11 License
version: 1.0

authors:
  - Andi Dittrich
  
requires:
  - EnlighterJS/2.2

provides: [Enlighter]
...
*/
(function(){
	// register plugin
	tinymce.PluginManager.add('enlighter', function(editor, url){
		// enlighter settings button (menubar)
		var editMenuButton = null;
		
		// generate language values
		var languageValues = [{
			text: 'Default-Setting',
			value: null
		}];
		tinymce.each(Enlighter.languages, function(value, key){
			languageValues.push({
				text : key,
				value : value
			});
		});
		
		// generate theme values
		var themeValues = [{
			text: 'Default-Setting',
			value: null
		}];
		tinymce.each(Enlighter.themes, function(value, key){
			themeValues.push({
				text : key,
				value : value
			});
		});

		// Add Code Insert Button to toolbar
		editor.addButton('EnlighterInsert', {
			title : 'Enlighter Code Insert',
			image : url + '/code-insert-icon.png',

			onclick : function(){

				// Open new window on click
				editor.windowManager.open({
					title : 'Enlighter Code Insert',
					minWidth : 700,
					body : [{
						type : 'listbox',
						name : 'lang',
						label : 'Language',
						values : languageValues
					}, {
						type : 'textbox',
						name : 'code',
						label : 'Sourcecode',
						multiline : true,
						minHeight : 200
					}],
					onsubmit : function(e){
						// Insert codeblock into editors current position when the window form is "submitted"
						editor.insertContent('<pre class="EnlighterJSRAW" data-enlighter-language="' + e.data.lang + '">' + e.data.code + '</pre>');
					}
				});
			}
		});
		
		var showSettings = (function(){
			// get the current node settings
			var settings = getEnlighterSettings();
			
			// inline mode ?
			var inlineMode = isEnlighterInlineCode(editor.selection.getNode());
			
			// Open new window on click
			editor.windowManager.open({
				title : 'Enlighter Code Settings',
				minWidth : 700,
				body : [{
					type : 'listbox',
					name : 'language',
					label : 'Language',
					values : languageValues,
					value: settings.language
				},
				{
					type : 'listbox',
					name : 'theme',
					label : 'Theme',
					values : themeValues,
					value: settings.theme
				},
				{
					type : 'checkbox',
					name : 'linenums',
					label : 'Show Linenumbers',
					checked: settings.linenumbers,
					disabled: inlineMode
				},
				{
					type : 'textbox',
					name : 'highlight',
					label : 'Point out Lines (e.g. 1,2-6,9)',
					multiline : false,
					value: settings.highlight,
					disabled: inlineMode
				},
				{
					type : 'textbox',
					name : 'offset',
					label : 'Linennumber offset (e.g. 5)',
					multiline : false,
					value : settings.lineoffset,
					disabled: inlineMode
				},
				{
					type : 'textbox',
					name : 'group',
					label : 'Codegroup Identifier',
					multiline : false,
					value : settings.group,
					disabled: inlineMode
				},
				{
					type : 'textbox',
					name : 'title',
					label : 'Codegroup Title',
					multiline : false,
					value : settings.title,
					disabled: inlineMode
				}],
				
				onsubmit : function(e){
					// apply the enlighter specific node attributes to the current selected node
					setEnlighterSettings({
						language: e.data.language,
						linenumbers: e.data.linenums,
						highlight: e.data.highlight,
						lineoffset: e.data.offset,
						theme: e.data.theme,
						title: e.data.title,
						group: e.data.group
					});
				}
			});
		});
		// Add Code Edit Button to toolbar
		editor.addButton('EnlighterEdit', {
			title : 'Enlighter Code Settings',
			disabled: true,
			image : url + '/code-edit-icon.png',
			
			// store menu button instance
			onPostRender: function() {
				editMenuButton = this;
			},
			
			onclick: showSettings
		});

		/*
		// tab + tab-shift Indent/Outdent Events
		editor.on('keydown', function(e){
			// get current node
			var node = editor.selection.getNode();
			
			// enlighter element ?
			if (!isEnlighterCodeblock(node)){
				return;
			}

			// tab pressed ?
			if (e.keyCode == 9){
				// disable default events 
				e.preventDefault();
				e.stopImmediatePropagation();
				e.stopPropagation();
				
				// get content
				var txt = '';
				for (var i=0;i<editor.selection.getNode().childNodes.length;i++){
					txt += editor.selection.getNode().childNodes[i].nodeValue;
				}

				// get lines
				var lines = txt.split("\n");
				var stxt = editor.selection.getContent();
				
				// range indent mode ?
				if (stxt.length > 0){
					
				
				// indent single line	
				}else{
					// tab/shift combo pressed ? - OUTDENT
					if (e.shiftKey){
						console.log("Outdent");

						var pos = getCursorPosition();
						console.log(pos);
						
					// tab pressed - INDENT
					}else{
						editor.insertContent("&#09;");
					}
				}				
			}
		});
		*/
		
		// set the enlighter settings of the current node
		function setEnlighterSettings(settings){
			// get current node
			var node = editor.selection.getNode();
			
			// enlighter element ?
			if (!isEnlighterCode(node)){
				return;
			}
			
			// helper function
			var setAttb = (function(name){
				if (settings[name]){
					node.setAttribute('data-enlighter-' + name, settings[name]);
				}else{
					node.removeAttribute('data-enlighter-' + name);
				}
			});
			
			// language attribute available ?
			setAttb('language');
			
			// theme attribute available ?
			setAttb('theme');
			
			// highlight attribute available ?
			setAttb('highlight');
			
			// linenumber offset attribute available ?
			setAttb('lineoffset');
			
			// codegroup title set ?
			setAttb('group');
			setAttb('title');
			
			// show linenumbers
			if (settings.linenumbers == Enlighter.config.linenumbers){
				// default value
				node.removeAttribute('data-enlighter-linenumbers');
			}else{
				// user set value
				node.setAttribute('data-enlighter-linenumbers', (settings.linenumbers ? 'true' : 'false'));
			}
		}
		
		// get the enlighter settings of the current selected node
		function getEnlighterSettings(){
			// get current node
			var node = editor.selection.getNode();
			
			// enlighter element ?
			if (!isEnlighterCode(node)){
				return {};
			}
			
			// get linenumber attribute (null: not set | true/false)
			var ln = node.getAttribute('data-enlighter-linenumbers');

			// generate config
			return {
				language: node.getAttribute('data-enlighter-language'),
				linenumbers: (ln == null ? Enlighter.config.linenumbers : (ln=='true')),
				highlight: node.getAttribute('data-enlighter-highlight'),
				lineoffset: node.getAttribute('data-enlighter-lineoffset'),
				theme: node.getAttribute('data-enlighter-theme'),
				group: node.getAttribute('data-enlighter-group'),
				title: node.getAttribute('data-enlighter-title')
			};
		}
		
		// listen on NodeChange Event to show/hide the toolbar
		editor.on('NodeChange', function(e){
			// get current node
			var node = editor.selection.getNode();
			
			// show hide codeblock toolbar
			if (isEnlighterCodeblock(node)){
				showToolbar(node);				
			}else{
				hideToolbar();				
			}
			
			// show hide edit menu button
			if (isEnlighterCode(node)){
				editMenuButton.disabled(false);
			}else{
				editMenuButton.disabled(true);
			}
        });
		
		// is the given node a enlighter codeblock ?
		function isEnlighterCodeblock(node){
			// enlighter element ?
			return (node.nodeName == 'PRE' && editor.dom.hasClass(node, 'EnlighterJSRAW'));
		}
		
		// is the given node a enlighter inline codeblock ?
		function isEnlighterInlineCode(node){
			// enlighter element ?
			return (node.nodeName == 'CODE' && editor.dom.hasClass(node, 'EnlighterJSRAW'));
		}
		
		// is the given node a enlighter inline block ?
		function isEnlighterCode(node){
			// enlighter element ?
			return ((node.nodeName == 'CODE' || node.nodeName == 'PRE' ) && editor.dom.hasClass(node, 'EnlighterJSRAW'));
		}
		
		// show toolbar on top of the boundingNode
		// recreation is required for WordPress editor switch visual/text mode!
		function showToolbar(boundingNode){
			hideToolbar();
			
			// create new toolbar object
			var toolbar = editor.dom.create('div', {
				'id': 'EnlighterToolbar',
				'data-mce-bogus': '1',
				'contenteditable': false
			});
			
			var button = editor.dom.create('div', {
				'class': 'editicon',
				'data-mce-bogus': '1',
				'contenteditable': false
			});
			toolbar.appendChild(button);
			editor.dom.bind(toolbar, 'mousedown', function(){
				showSettings();
			});
			
			// add toolbar to editor area
			editor.getBody().appendChild(toolbar);
			
			// get bounding content rect for absolute positioning
			var rect = editor.dom.getRect(boundingNode);
			
			// show toolbar and set positon
			editor.dom.setStyles(toolbar, {
				top: rect.y,
				left: rect.x
			});
		}

		// remove the codeblock edit toolbar
		function hideToolbar() {
			var tb = editor.dom.get('EnlighterToolbar');
			if (tb){
				editor.dom.remove(tb);
			}
		}
		
		// utlity
		function getCursorPosition(){
	        //set a bookmark so we can return to the current position after we reset the content later
	        var bm = editor.selection.getBookmark(0);    

	        // select bookmark element
	        var bme = editor.dom.select('[data-mce-type=bookmark]');

	        //put the cursor in front of that element
	        editor.selection.select(bme[0]);
	        editor.selection.collapse();

	        //add in my special span to get the index...
	        //we won't be able to use the bookmark element for this because each browser will put id and class attributes in different orders.
	        var positionString = '<span id="_shadow_cursor"></span>';
	        editor.selection.setContent(positionString);

	        //get the content with the special span but without the bookmark meta tag
	        var content = editor.getContent({format: "html"});
	        
	        //find the index of the span we placed earlier
	        var index = content.indexOf(positionString);

	        //remove my special span from the content
	        editor.dom.remove('_shadow_cursor', false);            

	        //move back to the bookmark
	        editor.selection.moveToBookmark(bm);
	        return index;
		}
	});
})();
