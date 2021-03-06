/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["dojox.editor.plugins.Breadcrumb"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojox.editor.plugins.Breadcrumb"] = true;
dojo.provide("dojox.editor.plugins.Breadcrumb");

dojo.require("dijit._editor._Plugin");
dojo.require("dijit._editor.range");
dojo.require("dojo.i18n");
dojo.require("dojo.string");
dojo.require("dijit.Toolbar");
dojo.require("dijit.form.Button");
dojo.require("dijit._editor.selection");
dojo.require("dijit.Menu");
dojo.require("dijit.MenuItem");
dojo.require("dijit.MenuSeparator");

dojo.experimental("dojox.editor.plugins.Breadcrumb");

dojo.requireLocalization("dojox.editor.plugins", "Breadcrumb", null, "ROOT");

dojo.declare("dojox.editor.plugins._BreadcrumbMenuTitle",[dijit._Widget, dijit._Templated, dijit._Contained],{
	// summary:
	//		SImple internal, non-clickable, menu entry to act as a menu title bar.
	templateString: "<tr><td dojoAttachPoint=\"title\" colspan=\"4\" class=\"dijitToolbar\" style=\"font-weight: bold; padding: 3px;\"></td></tr>",

	menuTitle: "",

	postCreate: function(){
		dojo.setSelectable(this.domNode, false);
		var label = this.id+"_text";
		dijit.setWaiState(this.domNode, "labelledby", label);
	},

	_setMenuTitleAttr: function(str){
		this.title.innerHTML = str;
	},
	_getMenuTitleAttr: function(str){
		return this.title.innerHTML;
	}
});


dojo.declare("dojox.editor.plugins.Breadcrumb",dijit._editor._Plugin,{
	// summary:
	//		This plugin provides Breadcrumb cabability to the editor.  When 
	//		As you move around the editor, it updates with your current indention 
	//		depth.

	//	_menu: [private]
	//		The popup menu that is displayed.
	_menu: null,

	//	breadcrumbBar: [protected]
	//		The toolbar containing the breadcrumb.
	breadcrumbBar: null,

	setEditor: function(editor){
		// summary:
		//		Over-ride for the setting of the editor.
		// editor: Object
		//		The editor to configure for this plugin to use.
		this.editor = editor;
		this._buttons = [];
		this.breadcrumbBar = new dijit.Toolbar();
		dojo.style(this.breadcrumbBar.domNode, "height", "1.5em");

		var strings = dojo.i18n.getLocalization("dojox.editor.plugins", "Breadcrumb");
		this._titleTemplate = strings.nodeActions;

		//editor.footer.appendChild(this.breadcrumbBar.domNode);
		dojo.place(this.breadcrumbBar.domNode, this.editor.iframe, "after");
		this.editor.onLoadDeferred.addCallback(dojo.hitch(this, function(){
			this._menu = new dijit.Menu({});
			dojo.addClass(this.breadcrumbBar.domNode, "dojoxEditorBreadcrumbArrow");
			var self = this;
			var body = new dijit.form.ComboButton({
				showLabel: true,
				label: "body",
				_selNode: editor.editNode,
				dropDown: this._menu,
				onClick: function(){
					self._menuTarget = editor.editNode;
					self._selectContents();
				}
			});
			
			// Build the menu
			this._menuTitle = new dojox.editor.plugins._BreadcrumbMenuTitle({menuTitle: strings.nodeActions});
			this._selCMenu = new dijit.MenuItem({label: strings.selectContents, onClick: dojo.hitch(this, this._selectContents)});
			this._delCMenu = new dijit.MenuItem({label: strings.deleteContents, onClick: dojo.hitch(this, this._deleteContents)});
			this._selEMenu = new dijit.MenuItem({label: strings.selectElement, onClick: dojo.hitch(this, this._selectElement)});
			this._delEMenu = new dijit.MenuItem({label: strings.deleteElement, onClick: dojo.hitch(this, this._deleteElement)});
			this._moveSMenu = new dijit.MenuItem({label: strings.moveStart, onClick: dojo.hitch(this, this._moveCToStart)});
			this._moveEMenu = new dijit.MenuItem({label: strings.moveEnd, onClick: dojo.hitch(this, this._moveCToEnd)});

			this._menu.addChild(this._menuTitle); 
			this._menu.addChild(this._selCMenu); 
			this._menu.addChild(this._delCMenu); 
			this._menu.addChild(new dijit.MenuSeparator({})); 
			this._menu.addChild(this._selEMenu); 
			this._menu.addChild(this._delEMenu); 
			this._menu.addChild(new dijit.MenuSeparator({})); 
			this._menu.addChild(this._moveSMenu); 
			this._menu.addChild(this._moveEMenu);

			body._ddConnect = dojo.connect(body, "openDropDown", dojo.hitch(this, function(){
				this._menuTarget = body._selNode;
				this._menuTitle.attr("menuTitle", dojo.string.substitute(this._titleTemplate,{
						"nodeName": "&lt;body&gt;"
				}));
				this._selEMenu.attr("disabled", true);
				this._delEMenu.attr("disabled", true);
			}));
			this.breadcrumbBar.addChild(body);
			this.connect(this.editor, "onNormalizedDisplayChanged", "updateState");
		}));
		this.breadcrumbBar.startup();
	},

	_selectContents: function(){
		// summary:
		//		Internal function for selecting the contents of a node.
		this.editor.focus();
		if(this._menuTarget){
			dojo.withGlobal(this.editor.window, 
				"collapse", dijit._editor.selection, [null]);
			dojo.withGlobal(this.editor.window, 
				"selectElementChildren", dijit._editor.selection, [this._menuTarget]);
			this.editor.onDisplayChanged();
		}
	},

	_deleteContents: function(){
		// summary:
		//		Internal function for selecting the contents of a node.
		if(this._menuTarget){
			this.editor.beginEditing();
			this._selectContents();
			dojo.withGlobal(this.editor.window, 
				"remove", dijit._editor.selection, [this._menuTarget]);
			this.editor.endditing();
			this._updateBreadcrumb();
			this.editor.onDisplayChanged();
		}
	},

	_selectElement: function(){
		// summary:
		//		Internal function for selecting the contents of a node.
		this.editor.focus();
		if(this._menuTarget){
			dojo.withGlobal(this.editor.window, 
				"collapse", dijit._editor.selection, [null]);
			dojo.withGlobal(this.editor.window, 
				"selectElement", dijit._editor.selection, [this._menuTarget]);
			this.editor.onDisplayChanged();
			
		}
	},

	_deleteElement: function(){
		// summary:
		//		Internal function for selecting the contents of a node.
		if(this._menuTarget){
			this.editor.beginEditing();
			this._selectElement();
			dojo.withGlobal(this.editor.window, 
				"remove", dijit._editor.selection, [this._menuTarget]);
			this.editor.endEditing();
			this._updateBreadcrumb();
			this.editor.onDisplayChanged();
		}
	},

	_moveCToStart: function(){
		// summary:
		//		Internal function for selecting the contents of a node.
		this.editor.focus();
		if(this._menuTarget){
			this._selectContents();
			dojo.withGlobal(this.editor.window, 
				"collapse", dijit._editor.selection, [true]);
		}
	},

	_moveCToEnd: function(){
		// summary:
		//		Internal function for selecting the contents of a node.
		this.editor.focus();
		if(this._menuTarget){
			this._selectContents();
			dojo.withGlobal(this.editor.window, 
				"collapse", dijit._editor.selection, [false]);
		}
	},

	_updateBreadcrumb: function(){
		// summary:
		//		Function to trigger updating of the breadcrumb
		// tags:
		//		private
		var ed = this.editor;
		if(ed.window){
			var sel = dijit.range.getSelection(ed.window);
			if(sel && sel.rangeCount > 0){
				var range = sel.getRangeAt(0);
				var node = range.startContainer;
				var bcList = [];

				// Make sure we get a selection within the editor document,
				// have seen cases on IE where this wasn't true.
				if(node && node.ownerDocument === ed.document){
					while(node && node !== ed.editNode){
						if(node.nodeType === 1){
							bcList.push({type: node.tagName.toLowerCase(), node: node}); 
						}
						node = node.parentNode;
					}
					bcList = bcList.reverse();

					while(this._buttons.length){
						var db = this._buttons.pop();
						dojo.disconnect(db._ddConnect);
						this.breadcrumbBar.removeChild(db);
					}
					this._buttons = [];

					var i;
					var self = this;
					for(i = 0; i < bcList.length; i++){
						var bc = bcList[i];
						var b = new dijit.form.ComboButton({
							showLabel: true,
							label: bc.type,
							_selNode: bc.node,
							dropDown: this._menu,
							onClick: function(){
								self._menuTarget = this._selNode;
								self._selectContents();
							}
						});
						b._ddConnect = dojo.connect(b, "openDropDown", dojo.hitch(b, function(){
							self._menuTarget = this._selNode;
							var title = dojo.string.substitute(self._titleTemplate,{
								"nodeName": "&lt;" + self._menuTarget.tagName.toLowerCase() + "&gt;"
							});
							self._menuTitle.attr("menuTitle", title);
							self._selEMenu.attr("disabled", false);
							self._delEMenu.attr("disabled", false);
						}));
						this._buttons.push(b);
						this.breadcrumbBar.addChild(b);
					}
				}
			}
		}
	},

	updateState: function(){
		// summary:
		//		Over-ride of updateState to hide the toolbar when the iframe is not visible.
		//		Also triggers the breadcrumb update.
		if(dojo.style(this.editor.iframe, "display") === "none"){
			dojo.style(this.breadcrumbBar.domNode, "display", "none");
		}else{
			if(dojo.style(this.breadcrumbBar.domNode, "display") === "none"){
				dojo.style(this.breadcrumbBar.domNode, "display", "block");
			}
			this._updateBreadcrumb();
		}
	},

	destroy: function(){
		// summary:
		//		Over-ride to clean up the breadcrumb toolbar.
		if(this.breadcrumbBar){
			this.breadcrumbBar.destroy();
			this.breadcrumbBar = null;
		}
		this._buttons = null;
		delete this.editor.breadcrumbBar;
		this.inherited(arguments);
	}
});

// Register this plugin.
dojo.subscribe(dijit._scopeName + ".Editor.getPlugin",null,function(o){
	if(o.plugin){ return; }
	var name = o.args.name.toLowerCase();
	if(name === "breadcrumb"){
		o.plugin = new dojox.editor.plugins.Breadcrumb({});
	}
});

}
