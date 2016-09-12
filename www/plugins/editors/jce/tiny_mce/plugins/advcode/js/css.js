/*
 * CodePress regular expressions for CSS syntax highlighting
 */

// CSS
Language.syntax = [
	{ input : /(.*?){(.*?)}/g,output : '<span class="mceCssTag">$1</span>{<span class="mceCssValue">$2</span>}' }, // tags, ids, classes, values
	{ input : /([\w-]*?):([^\/])/g,output : '<span class="mceCssKey">$1</span>:$2' }, // keys
	{ input : /\((.*?)\)/g,output : '(<span class="mceCssParameter">$1</span>)' }, // parameters
	{ input : /\/\*(.*?)\*\//g,output : '<span class="mceCssComment">/*$1*/</span>'} // comments
]

Language.snippets = []

Language.complete = [
	{ input : '\'',output : '\'$0\'' },
	{ input : '"', output : '"$0"' },
	{ input : '(', output : '\($0\)' },
	{ input : '[', output : '\[$0\]' },
	{ input : '{', output : '{\n\t$0\n}' }		
]

Language.shortcuts = []
