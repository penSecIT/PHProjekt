/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["dojox.dtl.filter.htmlstrings"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojox.dtl.filter.htmlstrings"] = true;
dojo.provide("dojox.dtl.filter.htmlstrings");

dojo.require("dojox.dtl._base");

dojo.mixin(dojox.dtl.filter.htmlstrings, {
	_linebreaksrn: /(\r\n|\n\r)/g,
	_linebreaksn: /\n{2,}/g,
	_linebreakss: /(^\s+|\s+$)/g,
	_linebreaksbr: /\n/g,
	_removetagsfind: /[a-z0-9]+/g,
	_striptags: /<[^>]*?>/g,
	linebreaks: function(value){
		// summary: Converts newlines into <p> and <br />s
		var output = [];
		var dh = dojox.dtl.filter.htmlstrings;
		value = value.replace(dh._linebreaksrn, "\n");
		var parts = value.split(dh._linebreaksn);
		for(var i = 0; i < parts.length; i++){
			var part = parts[i].replace(dh._linebreakss, "").replace(dh._linebreaksbr, "<br />");
			output.push("<p>" + part + "</p>");
		}

		return output.join("\n\n");
	},
	linebreaksbr: function(value){
		// summary: Converts newlines into <br />s
		var dh = dojox.dtl.filter.htmlstrings;
		return value.replace(dh._linebreaksrn, "\n").replace(dh._linebreaksbr, "<br />");
	},
	removetags: function(value, arg){
		// summary: Removes a space separated list of [X]HTML tags from the output"
		var dh = dojox.dtl.filter.htmlstrings;
		var tags = [];
		var group;
		while(group = dh._removetagsfind.exec(arg)){
			tags.push(group[0]);
		}
		tags = "(" + tags.join("|") + ")";
		return value.replace(new RegExp("</?\s*" + tags + "\s*[^>]*>", "gi"), "");
	},
	striptags: function(value){
		// summary: Strips all [X]HTML tags
		return value.replace(dojox.dtl.filter.htmlstrings._striptags, "");
	}
});

}
