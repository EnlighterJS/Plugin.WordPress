/*!
---
name: EnlighterJS.TinyMCE
description: TinyMCE Editor Plugin for WordPress

license: MIT-Style X11 License
version: 1.4

authors:
  - Andi Dittrich
  
requires:
  - EnlighterJS/2.10.0

provides: [Enlighter]
...
*/
(function(_tinymce, _enlighter){
	// fetch console object
	var c = window.console || {};

    // register plugin
	_tinymce.PluginManager.add('enlighter', function(editor, url){
		// check for global Enlighter config availability
		if (typeof _enlighter == 'undefined'){
			if (c.log){
				console.log('No Enlighter config found');
			}
			return;
		}

        // is a enlighter node (pre element) selected/focused ?
        var enlighterNodeActive = false;

        // listen on editor paste events
        editor.on('PastePreProcess', function(e) {
            // paste event within an enlighter codeblock ?
            if (enlighterNodeActive) {
                // remove outer pre tags
                // avoids the creation of additional pre sections within the editor pane when pasting into an active section
                e.content = e.content
                    .replace(/^\s*<pre(.*?)>([\s\S]+)<\/pre>\s*$/gi, '$2')

                    // keep linebreaks
                    .replace(/\n/g, '<BR/>')

                    // keep indentation
                    .replace(/ /g, '&nbsp;');
            }
        });

        // enlighter settings button (menubar)
		var editMenuButton = null;
		
		// generate language values
		var languageValues = [{
			text: 'Default (Global-Settings)',
			value: null
		}];
		_tinymce.each(_enlighter.languages, function(value, key){
			languageValues.push({
				text : key,
				value : value
			});
		});
		
		// generate theme values
		var themeValues = [{
			text: 'Default (Global-Settings)',
			value: null
		}];
		_tinymce.each(_enlighter.themes, function(value, key){
			themeValues.push({
				text : key,
				value : key.toLowerCase()
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
					},
					{
						type : 'listbox',
						name : 'mode',
						label : 'Mode',
						values : [
						    {text: 'Block-Code', value: 'block'},
						    {text: 'Inline-Code', value: 'inline'}
						],
						value: 'block'
					},				
					{
						type : 'checkbox',
						name : 'indentation',
						label : 'Left-Align Indentation',
						checked: (_enlighter.config.indent > 0),
						disabled: (_enlighter.config.indent < 0)
					},				
					{
						type : 'checkbox',
						name : 'addspaces',
						label : 'Surround with spaces',
						checked: false,
						disabled: false
					}, 
					{
						type : 'textbox',
						name : 'code',
						label : 'Sourcecode',
						multiline : true,
						minHeight : 200
					}],
					onsubmit : function(e){
						// get code - replace windows style linebreaks
						var code = e.data.code.replace(/\r\n/gmi, '\n');
						
						// inline or block code ?
						var tag = (e.data.mode == 'inline' ? 'code' : 'pre');
						
						// modify code indent
						if (e.data.mode == 'block' && e.data.indentation){
							// match all tabs
							code = code.replace(/^(\t*)/gim, function(match, p1, offset, string){
								// replace n tabs with n*newIndent spaces
								return (new Array(_enlighter.config.indent * p1.length + 1)).join(' ');
							});
							
							var minIndentation = 99999;
							
							// get minimal indentation
							var lines = code.split('\n');
							for (var i=0;i<lines.length;i++){
								var l = lines[i];

								// non-empty line ?
								if (l.replace(/\s*/, '').length > 0){
									var k = l.match(/^( *)/gmi);
									
									// indentation found ?
									if (k && k.length == 1){
										minIndentation = Math.min(k[0].length, minIndentation);
										
									// no identation offset dectected	
									}else{
										minIndentation = 0;
										break;
									}
								}
							}
														
							// remove indent ?
							if (minIndentation > 0 && minIndentation < 99999){
								var pattern = new RegExp('^( ){' + minIndentation + '}', 'gmi');
								code = code.replace(pattern, '');
							}
						}
						
						// entities encoding
						code =  _tinymce.html.Entities.encodeAllRaw(code);
						
						// surround with spaces ?
						var sp = (e.data.addspaces ? '&nbsp;' : '');
						
						// Insert codeblock into editors current position when the window form is "submitted"
						editor.insertContent(sp + '<' + tag + ' class="EnlighterJSRAW" data-enlighter-language="' + e.data.lang + '">' + code + '</' + tag + '>' + sp + '<p></p>');
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
			if (settings.linenumbers == _enlighter.config.linenumbers){
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
				linenumbers: (ln == null ? _enlighter.config.linenumbers : (ln=='true')),
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
                enlighterNodeActive = true;
   			}else{
				hideToolbar();
                enlighterNodeActive = false;
    		}
			
			// show hide edit menu button
			if (editMenuButton){
				if (isEnlighterCode(node)){
					editMenuButton.disabled(false);
				}else{
					editMenuButton.disabled(true);
				}
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
				left: rect.x,
                width: rect.w
			});
		}

		// remove the codeblock edit toolbar
		function hideToolbar() {
			var tb = editor.dom.get('EnlighterToolbar');
			if (tb){
				editor.dom.remove(tb);
			}
		}
	});
})(tinymce, Enlighter_EditorConfig);
