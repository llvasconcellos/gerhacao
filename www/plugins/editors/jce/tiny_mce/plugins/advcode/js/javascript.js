/*
 * CodePress regular expressions for JavaScript syntax highlighting
 */
 
// JavaScript
Language.syntax = [ 
	{ input : /\"/g, output : 'mceDoubleQuote'},
	{ input : /mceDoubleQuote(.*?)(mceDoubleQuote|<br>|<\/P>)/g, output : '<span class="mceJavascriptString">mceDoubleQuote$1$2</span>' }, // strings double quote
	{ input : /\'(.*?)(\'|<br>|<\/P>)/g, output : '<span class="mceJavascriptString">\'$1$2</span>' }, // strings single quote
	{ input : /\b(break|continue|do|for|new|this|void|case|default|else|function|return|typeof|while|if|label|switch|var|with|catch|boolean|int|try|false|throws|null|true|goto)\b/g, output : '<span class="mceJavascriptReserved">$1</span>' }, // reserved words
	{ input : /\b(alert|isNaN|parent|Array|parseFloat|parseInt|blur|clearTimeout|prompt|prototype|close|confirm|length|Date|location|Math|document|element|name|self|elements|setTimeout|navigator|status|String|escape|Number|submit|eval|Object|event|onblur|focus|onerror|onfocus|onclick|top|onload|toString|onunload|unescape|open|valueOf|window|onmouseover)\b/g, output : '<span class="mceJavascriptSpecial">$1</span>' }, // special words
	{ input : /([^:]|^)\/\/(.*?)(<br|<\/P)/g, output : '$1<span class="mceJavascriptComment">//$2</span>$3' }, // comments //
	{ input : /\/\*(.*?)\*\//g, output : '<span class="mceJavascriptComment">/*$1*/</span>' }, // comments /* */
	{ input : /mceDoubleQuote/g, output : '"' }
]

Language.snippets = [
	{ input : 'dw', output : 'document.write(\'$0\');' },
	{ input : 'getid', output : 'document.getElementById(\'$0\')' },
	{ input : 'fun', output : 'function $0(){\n\t\n}' },
	{ input : 'func', output : 'function $0(){\n\t\n}' }
]

Language.complete = [
	{ input : '\'',output : '\'$0\'' },
	{ input : '"', output : '"$0"' },
	{ input : '(', output : '\($0\)' },
	{ input : '[', output : '\[$0\]' },
	{ input : '{', output : '{\n\t$0\n}' }		
]

Language.shortcuts = []
