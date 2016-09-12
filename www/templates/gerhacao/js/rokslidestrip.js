/**
 *  @author Olmo Maldonado, <http://olmo-maldonado.com
 *  
 *  Fixed resize issue - Djamil Legato <djamil [at] djamil.it>
 *  version 1.9
 */
var RokSlide = new Class({
	version: '1.9',
	options: {
		active: '',
		fx: {
			wait: false,
			duration: 350
		},
		scrollFX: {
			wait: false,
			transition: Fx.Transitions.Sine.easeInOut	
		},
		
		dimensions: {
			width: 722,
			height: 200
		},
		
		dynamic: false,
		tabsPosition: 'top',
		arrows: true
	},
	
	initialize: function(contents, options) {
		this.setOptions(options);
		this.content = $(contents);
		this.sections = this.content.getElements('.tab-pane');
		if(!this.sections.length) return;
	
		this.filmstrip = new Element('div').injectAfter(this.content);
		this.buildToolbar();
		this.buildFrame();
		if(window.ie) this.fixIE();
		
		this.scroller = $('scroller');
		this.startposition = $(this.sections[0].id.replace('-tab', '-pane')).getPosition().x;
		//this.scroller.fx = this.scroller.effects(this.options.fx);
		this.scroller.scrollFX = new Fx.Scroll(this.scroller, this.options.scrollFX);
		if(this.options.active) this.scrollSection(this.options.active.test(/-tab|-pane/) ? this.options.active : this.options.active + '-tab');
		else this.scrollSection(this.sectionptr[0]);

		if (this.options.tabsPosition == 'bottom') {
			this.filmstrip.getElement('hr').inject(this.filmstrip);
			var ul = this.filmstrip.getElement('ul'); ul.inject(this.filmstrip);
			var tab_height = ul.getSize().size.y, frame = $('frame');
			frame.setStyle('height', frame.getStyle('height').toInt() - tab_height);
		}
	},
	
	buildToolbar: function() {	
		var lis = [];
		var that = this;
		this.sectionptr = [];
		var h1, title;

		if (!!this.options.dynamic) this.width = $(this.options.dynamic).getCoordinates().width;
		else this.width = this.options.dimensions.width;
		var width = this.width;
		
		this.sections.each(function(el) {
			el.setStyles({
				width: width - ((!!this.options.dynamic) ? 0 : (!this.options.arrows) ? 0 : 142),
				height: this.options.dimensions.height
			});
			this.sectionptr.push(el.id.replace('-pane', '-tab'));
			h1 = el.getElement('.tab-title');
			title = h1.innerHTML;
			h1.empty().remove();
			lis.push(new Element('li', {
				id: el.id.replace('-pane', '-tab'),
				events: {
					'click': function() {
						this.addClass('active');	
						
						that.scrollSection(this);
					},
					'mouseover': function() {
						this.addClass('hover');
						this.addClass('active');
					},
					'mouseout': function() {
						this.removeClass('hover');
						this.removeClass('active');
					}
				}
			}).setHTML(title));
		}, this);
		
		var length = lis.length - 1;
		lis[0].addClass('first');
		lis[length].addClass('last');
		
		this.filmstrip.adopt(new Element('ul', {
					id: 'rokslide-toolbar',
					styles: {
						width: width
					}
				}).adopt(lis), new Element('hr'));
	},
	
	buildFrame: function() {
		var width = this.width;
		
		var that = this, events = {
			'click': function() {
				that.scrollArrow(this)
			},
			'mouseover': function() {
				this.addClass('hover');
			},
			'mouseout': function() {
				this.removeClass('hover');
			}
		};
		
		var arrows = {
			'left': (this.options.arrows) ? new Element('div', {'class': 'button','id': 'left','events': events}) : '',
			'right': (this.options.arrows) ? new Element('div', {'class': 'button','id': 'right','events': events}) : ''
		};
		
		this.filmstrip.adopt(
			new Element('div', {
				id: 'frame', 
				styles: {
					width: width,
					height: this.options.dimensions.height
				}
			}).adopt(
				arrows.left,
				new Element('div', { 
					id: 'scroller',
					styles: {
						width: 	width - ((!!this.options.dynamic) ? 0 : (!this.options.arrows) ? 0 : 102),
						height: this.options.dimensions.height
					}
				}).adopt(this.content.setStyle('width', this.sections.length * 1600)),
				arrows.right
			)
		);	
	},
	
	fixIE: function() {
		// if(window.ie6) {
		// 	this.sections.each(function(el) {
		// 		el.setStyle('margin', '0px 10px');
		// 	});
		// }
		
		this.filmstrip.getElement('hr').setStyle('display', 'none');
		
		// [$('frame'), this.scroller].merge(this.sections).merge($$('#frame div.button')).each(function(el) {
		// 	if(el) el.setStyle('height', '75.5em');
		// });
	},
	
	scrollSection: function(element) {
		element = $($(element || this.sections[0]).id.replace('-pane', '-tab'));
		this.startposition = $(this.sections[0].id.replace('-tab', '-pane')).getPosition().x;
		
		var oldactive = element.getParent().getElement('.current');
		if(oldactive) oldactive.removeClass('current');
		element.addClass('current');
	
		var offset = $(element.id.replace('-tab', '-pane')).getPosition().x - this.startposition;
		this.scroller.scrollFX.scrollTo(offset, false);
		/*var that = this;
		this.scroller.fx.start({
			opacity: 0	
		}).chain(function() {
			that.scroller.fx.start({
				opacity: 1
			});
		});*/
	},
	
	scrollArrow: function(element) {
		var direction = Math.pow(-1, ['left','right'].indexOf(element.id) + 1);
		var current = this.sectionptr.indexOf(this.filmstrip.getElement('.current').id);
		var to = current + direction;
		this.scrollSection(this.sectionptr[to < 0 ? this.sectionptr.length - 1 : to % this.sectionptr.length]);
	}
});
RokSlide.implement(new Options);