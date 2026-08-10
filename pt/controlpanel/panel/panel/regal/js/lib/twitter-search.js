/*!
 * jQuery Twitter Search Plugin
 * Examples and documentation at: http://jquery.malsup.com/twitter/
 * Copyright (c) 2010 M. Alsup
 * Version: 1.01 (14-APR-2010)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Requires: jQuery v1.3.2 or later
 */

// @todo: refresh button

(function ($) {
	$.fn.twitterSearch = function (options) {
		if (typeof options == 'string')
			options = {
				term: options
		};
		return this.each(function () {
			var $frame = $(this);
			var opts = $.extend(true, {}, $.fn.twitterSearch.defaults, options || {}, $.metadata ? $frame.metadata() : {});
			opts.formatter = opts.formatter || $.fn.twitterSearch.formatter;
			opts.filter = opts.filter || $.fn.twitterSearch.filter;
			var url = opts.url + opts.term;

			if (!opts.applyStyles) { // throw away all style defs
				for (var css in opts.css)
					opts.css[css] = {};
			}

			if (opts.title === null) // user can set to '' to suppress title
				opts.title = opts.term;

			opts.title = opts.title || '';
			var t = opts.titleLink ? ('<a href="' + opts.titleLink + '">' + opts.title + '</a>') : ('<span>' + opts.title + '</span>');
			var $t = $(t);
			if (opts.titleLink)
				$t.css(opts.css['titleLink']);

			var $title = $('<div class="twitterSearchTitle"></div>').append($t).appendTo($frame).css(opts.css['title']);
			if (opts.bird) {
				var $b = $title.css(opts.css['bird']);
				if (opts.birdLink)
					$b.wrap('<a href="' + opts.birdLink + '"></a>');
			}
			var $cont = $('<div class="twitterSearchContainter"></div>').appendTo($frame).css(opts.css['container']);
			var $close = $('<div class="twitterSearchClose"></div>').appendTo($frame);
			var cont = $cont[0];
			if (opts.colorExterior)
				$title.css('background-color', opts.colorExterior);
			if (opts.colorInterior)
				$cont.css('background-color', opts.colorInterior);

			$frame.css(opts.css['frame']);
			if (opts.colorExterior)
				$frame.css('border-color', opts.colorExterior);

			var h = $frame.innerHeight() - $title.outerHeight();
			//$cont.height(h);

			if (opts.pause)
				$cont.hover(function () {
					cont.twitterSearchPause = true;
				}, function () {
					cont.twitterSearchPause = false;
				});

			$('<div class="twitterSearchLoading">Loading tweets..</div>').css(opts.css['loading']).appendTo($cont);

			// grab twitter stream
			$.ajax({
				url: opts.url,
				timeout: 30000,
				dataType: 'json'
			}).fail(function (xhr, status, e) {
				failWhale(e);
			}).done(function (json) {
				if (json.error) {
					failWhale(json.error);
					return;
				}
				$cont.empty();
				$.each(json.statuses, function (i) {
					if (!opts.filter.call(opts, this))
						return; // skip this tweet
					var tweet = opts.formatter(this, opts),
						$tweet = $(tweet);
					$tweet.css(opts.css['tweet']);
					var $img = $tweet.find('.twitterSearchProfileImg').css(opts.css['img']);
					$tweet.find('.twitterSearchUser').css(opts.css['user']);
					$tweet.find('.twitterSearchTime').css(opts.css['time']);
					$tweet.find('a').css(opts.css['a']);
					$tweet.appendTo($cont);
					var $text = $tweet.find('.twitterSearchText').css(opts.css['text']);
					if (opts.avatar) {
						var w = $img.outerWidth() + parseInt($tweet.css('paddingLeft'));
						$text.css('paddingLeft', w);
					}
				});

				if (json.statuses.length < 2)
					return;

				// stage first animation
				setTimeout(go, opts.timeout);
			});

			function failWhale(msg) {
				var $fail = $('<div class="twitterSearchFail">' + msg + '</div>').css(opts.css['fail']);
				$cont.empty().append($fail);
			};

			function go() {
				if (cont.twitterSearchPause) {
					setTimeout(go, 500);
					return;
				}
				var $el = $cont.children(':first'),
					el = $el[0];
				$el.animate(opts.animOut, opts.animOutSpeed, function () {
					var h = $el.outerHeight();
					$el.animate({
						marginTop: -h
					}, opts.animInSpeed, function () {
						$el.css({
							marginTop: 0,
							opacity: 1
						});
						/*@cc_on
						try { el.style.removeAttribute('filter'); } // ie cleartype fix
						catch(smother) {}
						@*/
						$el.css(opts.css['tweet']).show().appendTo($cont);
					});
					// stage next animation
					setTimeout(go, opts.timeout);
				});
			}
		});
	};

	$.fn.twitterSearch.filter = function (tweet) {
		return true;
	};

	$.fn.twitterSearch.formatter = function (json, opts) {
		var t = json.text;
		if (opts.anchors) {
			t = json.text.replace(/(http:\/\/\S+)/g, '<a target="_blank" href="$1">$1</a>');
			t = t.replace(/\@(\w+)/g, '<a style="color:' + opts.colorExterior + '" target="_blank" href="http://twitter.com/$1">@$1</a>');
		}
		//https://twitter.com/search/realtime?q=clumsypig
		var s = '<div class="twitterSearchTweet">';
		if (opts.avatar)
			s += '<img class="twitterSearchProfileImg" src="' + json.user.profile_image_url + '" />';
		s += '<div><span class="twitterSearchUser"><a style="color:' + opts.colorExterior + '" target="_blank" href="https://twitter.com/search/realtime?q=' + json.user.screen_name + '">' + json.user.name + ' @' + json.user.screen_name + '</a></span>';
		var d = prettyDate(json.created_at);
		if (opts.time && d)
			s += ' <span class="twitterSearchTime">(' + d + ')</span>'
		s += '<div class="twitterSearchText">' + t + '</div></div></div>';
		return s;
	};

	$.fn.twitterSearch.defaults = {
		url: 'http://search.twitter.com/search.json?callback=?&q=',
		anchors: true, // true or false (enable embedded links in tweets)
		animOutSpeed: 700, // speed of animation for top tweet when removed
		animInSpeed: 700, // speed of scroll animation for moving tweets up
		animOut: {
			opacity: 0
		}, // animation of top tweet when it is removed
		applyStyles: true, // true or false (apply default css styling or not)
		avatar: true, // true or false (show or hide twitter profile images)
		bird: true, // true or false (show or hide twitter bird image)
		birdLink: false, // url that twitter bird image should like to
		birdSrc: 'http://cloud.github.com/downloads/malsup/twitter/tweet.gif', // twitter bird image
		colorExterior: null, // css override of frame border-color and title background-color
		colorInterior: null, // css override of container background-color
		filter: null, // callback fn to filter tweets:  fn(tweetJson) { /* return false to skip tweet */ }
		formatter: null, // callback fn to build tweet markup
		pause: false, // true or false (pause on hover)
		term: '', // twitter search term
		time: true, // true or false (show or hide the time that the tweet was sent)
		timeout: 8000, // delay betweet tweet scroll
		title: null, // title text to display when frame option is true (default = 'term' text)
		titleLink: null, // url for title link
		css: {
			// default styling
			a: {
				textDecoration: 'none'
			},
			bird: {
				width: '50px',
				height: '20px',
				position: 'absolute',
				left: '-30px',
				top: '-20px',
				border: 'none'
			},
			container: {
				overflow: 'hidden',
				backgroundColor: '#FFF',
				height: '253px',
				border: '1px solid #ccc'
			},
			fail: {
				background: '#6cc5c3 url(http://cloud.github.com/downloads/malsup/twitter/failwhale.png) no-repeat 50% 50%',
				height: '100%',
				padding: '10px'
			},
			frame: {
				height: '280px',
				border: '5px solid #C2CFF1',
				'-moz-box-shadow': '1px 1px 5px #000',
				'-webkit-box-shadow': '1px 1px 5px #000'
			},
			tweet: {
				padding: '5px 10px',
				clear: 'left'
			},
			img: {
				'float': 'left',
				margin: '5px 10px 5px 0px',
				width: '48px',
				height: '48px',
				'-moz-box-shadow': '1px 1px 5px #000',
				'-webkit-box-shadow': '1px 1px 5px #000'
			},
			loading: {
				padding: '20px',
				textAlign: 'center',
				color: '#888',
				fontSize: '10px'
			},
			text: {
				fontSize: '11px',
				borderBottom: '1px solid #ddd',
				paddingBottom: '6px'
			},
			time: {
				fontSize: '10px',
				color: '#888'
			},
			title: {},
			titleLink: {
				textDecoration: 'none'
			},
			user: {
				fontWeight: 'bold',
				fontSize: '11px',
				textShadow: '1px 1px 1px #fff'
			}
		}
	};

	/*
	 * JavaScript Pretty Date
	 * Copyright (c) 2008 John Resig (jquery.com)
	 * Licensed under the MIT license.
	 */
	// converts ISO time to casual time
	function prettyDate(time) {
		var date = new Date((time || "").replace(/-/g, "/").replace(/TZ/g, " ")),
			diff = (((new Date()).getTime() - date.getTime()) / 1000),
			day_diff = Math.floor(diff / 86400);

		if (isNaN(day_diff) || day_diff < 0 || day_diff >= 31)
			return;
		var v = day_diff == 0 && (
			diff < 60 && "just now" ||
			diff < 120 && "1 minute ago" ||
			diff < 3600 && Math.floor(diff / 60) + " minutes ago" ||
			diff < 7200 && "1 hour ago" ||
			diff < 86400 && Math.floor(diff / 3600) + " hours ago") ||
			day_diff == 1 && "Yesterday" ||
			day_diff < 7 && day_diff + " days ago" ||
			day_diff < 31 && Math.ceil(day_diff / 7) + " weeks ago";
		if (!v)
			window.console && console.log(time);
		return v ? v : '';
	}

})(jQuery);

(function (window, $) {
	$.fn.twitterpopup = function (options) {
		var opts = $.extend({}, $.fn.twitterpopup.defaults, options);
		return this.each(function () {
			var $this = $(this);
			var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
			$this.on('click', function (e) {
				var $this = $(this);
				if ($this.data('active'))
					return;
				var $search = $('<div class="search_results"></div>').appendTo(opts.container);
				//if (opts.modal)
				//$search.css('position','fixed');
				$search.twitterSearch({
					term: $this.html(),
					bird: false,
					colorExterior: opts.colorExterior,
					colorInterior: '#FFF',
					pause: true,
					//time			: false, 
					timeout: 3000,
					url: opts.url
				});
				var PopupPositions = $.fn.twitterpopup.calculatePopupPositions($this, $search);

				$search.resizable({
					alsoResize: $search.find('.twitterSearchContainter'),
					handles: 'se'
				}).draggable();
				$search.css({
					left: (0),
					top: ('82px')
				}).show();
				$this.data('active', true);
				$search.find('.twitterSearchClose').on('click', function () {
					$search.remove();
					$this.data('active', false);
				});
			});

		});
	};
	/*
	gets the current viewport width and height
	*/
	$.fn.twitterpopup.getWindowSize = function () {
		var WindowSize = {
			width: window.width(),
			height: window.height()
		};
		return WindowSize;
	};
	/*
	calculates left and top for the popup to be displayed, based on the viewport width and height
	*/
	$.fn.twitterpopup.calculatePopupPositions = function ($elem, $popup) {
		var WindowSize = $.fn.twitterpopup.getWindowSize();

		/* defaults sould be: */
		var popupL = $elem.offset().left + $elem.width() + 20;
		var popupT = $elem.offset().top;

		/* if final left+width of popup exceeds window width then popup should be placed on the left side */
		var popupWidth = $popup.width();
		if (popupL + popupWidth > WindowSize.width)
			popupL = $elem.offset().left - popupWidth - 20;

		/* if final top+height of popup exceeds window height then popup should be adjusted to fit the window */
		var $elemOffsetTop = $elem.offset().top - window.scrollTop();
		var popupHeight = $popup.height();

		/* cases: 
			1) when popup would be hidden on top of viewport 
			2) when popup would be hidden on bottom of viewport 
		*/
		if ($elemOffsetTop < 0) {
			popupT = $elem.offset().top - $elemOffsetTop;
		} else if ($elemOffsetTop + popupHeight > WindowSize.height) {
			var diff = $elemOffsetTop + popupHeight - WindowSize.height;
			popupT = $elem.offset().top - diff - 20;
		}

		/* new popup positions */
		var PopupPositions = {
			left: popupL,
			top: popupT
		};

		return PopupPositions;
	};

})(jQuery(window), jQuery);