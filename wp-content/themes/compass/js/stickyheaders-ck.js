/*!
 * Sticky Section Headers
 *
 * Copyright (c) 2012 Florian Plank (http://www.polarblau.com/)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * USAGE:
 *
 * $('#container').stickySectionHeaders({
 *   stickyClass      : 'sticky',
 *   headlineSelector : 'strong'
 * });
 *
 */(function(e){e.fn.stickySectionHeaders=function(t){var n=e.extend({stickyClass:"sticky",headlineSelector:"strong"},t);return e(this).each(function(){var t=e(this);e(this).find("ul:first").bind("scroll.sticky",function(t){e(this).find("> li").each(function(){var t=e(this),r=t.position().top,i=t.outerHeight(),s=t.find(n.headlineSelector),o=s.outerHeight();if(r<0){t.addClass(n.stickyClass).css("paddingTop",o);s.css({top:i+r<o?(o-(r+i))*-1:"",width:t.outerWidth()-s.cssSum("paddingLeft","paddingRight")})}else t.removeClass(n.stickyClass).css("paddingTop","")})})})};e.fn.cssSum=function(){var t=e(this),n=0;e(arguments).each(function(e,r){n+=parseInt(t.css(r)||0,10)});return n}})(jQuery);