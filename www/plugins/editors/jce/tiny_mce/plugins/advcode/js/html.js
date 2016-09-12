/*
 * CodePress regular expressions for HTML syntax highlighting
 */

// HTML
Language.syntax = [
	{ input : /\"/g, output : 'mceDoubleQuote'},
	{ input : /(&lt;[^!]*?&gt;)/g, output : '<span class="mceHtmlTag">$1</span>'}, // all tags
	{ input : /(&lt;a .*?&gt;|&lt;\/a&gt;)/g, output : '<span class="mceHtmlLink">$1</span>' }, // links
	{ input : /(&lt;img .*?&gt;)/g, output : '<span class="mceHtmlImage">$1</span>' }, // images
	{ input : /(&lt;\/?(button|textarea|form|input|select|option|label).*?&gt;)/g, output : '<span class="mceHtmlForm">$1</span>' }, // forms
	{ input : /(&lt;style.*?&gt;)(.*?)(&lt;\/style&gt;)/g, output : '<span class="mceHtmlStyle">$1</span><span class="mceHtmlStyle">$2</span><span class="mceHtmlStyle">$3</span>' }, // style tags
	{ input : /(&lt;script.*?&gt;)(.*?)(&lt;\/script&gt;)/g, output : '<span class="mceHtmlScript">$1</span><span class="mceHtmlReserved">$2</span class="mceHtmlScript"><strong>$3</span>' }, // script tags
	{ input : /=(mceDoubleQuote.*?mceDoubleQuote)/g, output : '=<span class="mceHtmlAttribute">$1</span>' }, // atributes double quote
	{ input : /=('.*?')/g, output : '=<span class="mceHtmlQuote">$1</span>' }, // atributes single quote
	{ input : /(&lt;!--.*?--&gt.)/g, output : '<span class="mceHtmlComment">$1</span>' }, // comments 
	{ input : /\b(alert|window|document|break|continue|do|for|new|this|void|case|default|else|function|return|typeof|while|if|label|switch|var|with|catch|boolean|int|try|false|throws|null|true|goto)\b/g, output : '<span class="mceHtmlReserved">$1</span>' }, // script reserved words 
	{ input : /mceDoubleQuote/g, output : '"' }
]

Language.snippets = [
	{ input : 'aref', output : '<a href="$0"></a>' },
	{ input : 'h1', output : '<h1>$0</h1>' },
	{ input : 'h2', output : '<h2>$0</h2>' },
	{ input : 'h3', output : '<h3>$0</h3>' },
	{ input : 'h4', output : '<h4>$0</h4>' },
	{ input : 'h5', output : '<h5>$0</h5>' },
	{ input : 'h6', output : '<h6>$0</h6>' },
	{ input : 'html', output : '<html>\n\t$0\n</html>' },
	{ input : 'head', output : '<head>\n\t<meta http-equiv="content-type" content="text/html; charset=utf-8" />\n\t<title>$0</title>\n\t\n</head>' },
	{ input : 'img', output : '<img src="$0" alt="" />' },
	{ input : 'input', output : '<input name="$0" id="" type="" value="" />' },
	{ input : 'label', output : '<label for="$0"></label>' },
	{ input : 'legend', output : '<legend>\n\t$0\n</legend>' },
	{ input : 'link', output : '<link rel="stylesheet" href="$0" type="text/css" media="screen" charset="utf-8" />' },		
	{ input : 'base', output : '<base href="$0" />' }, 
	{ input : 'body', output : '<body>\n\t$0\n</body>' }, 
	{ input : 'css', output : '<link rel="stylesheet" href="$0" type="text/css" media="screen" charset="utf-8" />' },
	{ input : 'div', output : '<div>\n\t$0\n</div>' },
	{ input : 'divid', output : '<div id="$0">\n\t\n</div>' },
	{ input : 'dl', output : '<dl>\n\t<dt>\n\t\t$0\n\t</dt>\n\t<dd></dd>\n</dl>' },
	{ input : 'fieldset', output : '<fieldset>\n\t$0\n</fieldset>' },
	{ input : 'form', output : '<form action="$0" method="" name="">\n\t\n</form>' },
	{ input : 'meta', output : '<meta name="$0" content="" />' },
	{ input : 'p', output : '<p>$0</p>' },
	{ input : 'script', output : '<script type="text/javascript" language="javascript" charset="utf-8">\n\t$0\t\n</script>' },
	{ input : 'scriptsrc', output : '<script src="$0" type="text/javascript" language="javascript" charset="utf-8"></script>' },
	{ input : 'span', output : '<span>$0</span>' },
	{ input : 'table', output : '<table border="$0" cellspacing="" cellpadding="">\n\t<tr><th></th></tr>\n\t<tr><td></td></tr>\n</table>' },
	{ input : 'style', output : '<style type="text/css" media="screen">\n\t$0\n</style>' }
]
	
Language.complete = [
	{ input : '\'',output : '\'$0\'' },
	{ input : '"', output : '"$0"' },
	{ input : '(', output : '\($0\)' },
	{ input : '[', output : '\[$0\]' },
	{ input : '{', output : '{\n\t$0\n}' }		
]

Language.shortcuts = []
