(function() {
	tinymce.create('tinymce.plugins.Browser', {
		init : function(ed, url) {
		},

		getInfo : function() {
			return {
				longname : 'Browser',
				author : 'Ryan Demmer',
				authorurl : 'http://www.cellardoor.za.net',
				infourl : 'http://www.cellardoor.za.net/index2.php?option=com_content&amp;task=findkey&amp;pop=1&amp;lang=en&amp;keyref=browser.about',
				version : '1.5.0'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('browser', tinymce.plugins.Browser);
})();