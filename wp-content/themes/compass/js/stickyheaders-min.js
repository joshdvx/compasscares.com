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
 * CHANGELOG
 * 
 * 2013-01-22: Jonah Dahlquist customized to match current markup, and added
 * settings topPadding and headlineHeight to accomidate needed functionality
 *
 */(function(e){e.fn.stickySectionHeaders=function(t){var n=e.extend({stickyClass:"sticky",headlineSelector:"strong",topPadding:0},t);return e(this).each(function(){e(this).bind("scroll.sticky",function(t){var r=e(window).scrollTop();e(".home-section").each(function(){var t=e(this),i=t.position().top-r-n.topPadding,s=t.outerHeight(),o=t.find(n.headlineSelector),u=n.headlineHeight?n.headlineHeight:o.outerHeight();if(i<0){t.addClass(n.stickyClass).css("paddingTop",u);o.css({top:s+i<u?(u-(i+s))*-1+n.topPadding:n.topPadding,width:t.outerWidth()-o.cssSum("paddingLeft","paddingRight")})}else{t.removeClass(n.stickyClass).css("paddingTop","");o.css({top:"",width:""})}})})})};e.fn.cssSum=function(){var t=e(this),n=0;e(arguments).each(function(e,r){n+=parseInt(t.css(r)||0,10)});return n}})(jQuery);