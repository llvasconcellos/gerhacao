/*
 * CodePress regular expressions for PHP syntax highlighting
 */

// PHP
Language.syntax = [
	{ input : /\"/g, output : 'mceDoubleQuote'},
	{ input : /\b(class)\b/g, output : 'mceClass' },
	{ input : /(&lt;[^!]*?&gt;)/g, output : '<span class="mcePhpTag">$1</span>'}, // all tags
	{ input : /(&lt;style.*?&gt;)(.*?)(&lt;\/style&gt;)/g, output : '<span class="mcePhpStyle">$1</span><span class="mcePhpStyle">$2</emspan<span class="mcePhpStyle">$3</span>' }, // style tags
	{ input : /(&lt;script.*?&gt;)(.*?)(&lt;\/script&gt;)/g, output : '<span class="mcePhpStyle">$1</span><span class="mcePhpStyle">$2</span><span class="mcePhpStyle">$3</span>' }, // script tags
	{ input : /mceDoubleQuote(.*?)(mceDoubleQuote|<br>|<\/P>)/g, output : '<span class="mcePhpString">mceDoubleQuote$1$2</span>' }, // strings double quote
	{ input : /\'(.*?)(\'|<br>|<\/P>)/g, output : '<span class="mcePhpString">\'$1$2</span>'}, // strings single quote
	{ input : /(&lt;\?)/g, output : '<span class="mcePhp">$1' }, // <?.*
	{ input : /(\?&gt;)/g, output : '$1</span>' }, // .*?>
	{ input : /(&lt;\?php|&lt;\?=|&lt;\?|\?&gt;)/g, output : '<span class="mcePhp">$1</span>' }, // php tags
	{ input : /(\$[\w\.]*)/g, output : '<span class="mcePhpVar">$1</span>' }, // vars
	{ input : /\b(false|true|and|or|xor|__FILE__|exception|__LINE__|array|as|break|case|mceClass|const|continue|declare|default|die|do|echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|endwhile|eval|exit|extends|for|foreach|function|global|if|include|include_once|isset|list|new|print|require|require_once|return|static|switch|unset|use|while|__FUNCTION__|__CLASS__|__METHOD__|final|php_user_filter|interface|implements|extends|public|private|protected|abstract|clone|try|catch|throw|this)\b/g, output : '<span class="mcePhpReserved">$1</span>' }, // reserved words
	{ input : /([^:])\/\/(.*?)(<br|<\/P)/g, output : '$1<span class="mcePhpComment">//$2</span>$3' }, // php comments //
	{ input : /([^:])#(.*?)(<br|<\/P)/g, output : '$1<span class="mcePhpComment">#$2</span>$3' }, // php comments #
	{ input : /\/\*(.*?)\*\//g, output : '<span class="mcePhpComment">/*$1*/</span>' }, // php comments /* */
	{ input : /(&lt;!--.*?--&gt.)/g, output : '<span class="mcePhpComment">$1</span>' }, // html comments
	{ input : /\b(mceClass)\b/g, output : 'class' },
	{ input : /mceDoubleQuote/g, output : '"' }
]

Language.snippets = [
	{ input : 'if', output : 'if($0){\n\t\n}' },
	{ input : 'ifelse', output : 'if($0){\n\t\n}\nelse{\n\t\n}' },
	{ input : 'else', output : '}\nelse {\n\t' },
	{ input : 'elseif', output : '}\nelseif($0) {\n\t' },
	{ input : 'do', output : 'do{\n\t$0\n}\nwhile();' },
	{ input : 'inc', output : 'include_once("$0");' },
	{ input : 'fun', output : 'function $0(){\n\t\n}' },	
	{ input : 'func', output : 'function $0(){\n\t\n}' },	
	{ input : 'while', output : 'while($0){\n\t\n}' },
	{ input : 'for', output : 'for($0,,){\n\t\n}' },
	{ input : 'fore', output : 'foreach($0 as ){\n\t\n}' },
	{ input : 'foreach', output : 'foreach($0 as ){\n\t\n}' },
	{ input : 'echo', output : 'echo \'$0\';' },
	{ input : 'switch', output : 'switch($0) {\n\tcase "": break;\n\tdefault: ;\n}' },
	{ input : 'case', output : 'case "$0" : break;' },
	{ input : 'ret0', output : 'return false;' },
	{ input : 'retf', output : 'return false;' },
	{ input : 'ret1', output : 'return true;' },
	{ input : 'rett', output : 'return true;' },
	{ input : 'ret', output : 'return $0;' },
	{ input : 'def', output : 'define(\'$0\',\'\');' },
	{ input : '<?', output : 'php\n$0\n?>' }
]

Language.complete = [
	{ input : '\'', output : '\'$0\'' },
	{ input : '"', output : '"$0"' },
	{ input : '(', output : '\($0\)' },
	{ input : '[', output : '\[$0\]' },
	{ input : '{', output : '{\n\t$0\n}' }		
]

Language.shortcuts = [
	{ input : '[space]', output : '&nbsp;' },
	{ input : '[enter]', output : '<br />' } ,
	{ input : '[j]', output : 'testing' },
	{ input : '[7]', output : '&amp;' }
]