/* Author: 

*/// Allows you to use the $ shortcut.  Put all your code  inside this wrapper
jQuery(document).ready(function(e){function s(){e(this).parents("ul:first").find("a").removeClass("selected").end().end().addClass("selected")}function o(t){var n=e("#staff-slider .navigation").find('a[href$="'+t.id+'"]').get(0);s.call(n)}e(".item:first-child").addClass("active");e("#locations li:first-child").addClass("active");e(".tab-pane:first-child").addClass("active");e(".full-bio span").click(function(){e(this).parent(".full-bio").find("p").slideToggle("fast",function(){e(this).is(":visible")?e(this).siblings("span").text("Read Less"):e(this).siblings("span").text("Read More")})});e("a.jd").click(function(){e("thead.job-description div").slideToggle("fast")});e("#sls span, #ils span").click(function(){e(".service-copy").slideToggle();e("#ils").html("<span class='poop'>Back</span>")});e(".show-comments").click(function(){e("#disqus_thread").slideToggle()});e('input[type="checkbox"]').click(function(){if(e('input[type="checkbox"]:checked').length>0){e("table#wpjb-job-list tr.wpjb-new").hide();e('input[type="checkbox"]:checked').each(function(){e("table#wpjb-job-list tr.wpjb-new[data-category="+this.id+"]").show();e("table#wpjb-job-list tr.wpjb-new[data-county="+this.id+"]").show()})}else e("table#wpjb-job-list tr.wpjb-new").show()});var t=e("#staff-slider .scrollContainer > div"),n=e("#staff-slider .scrollContainer"),r=!0;if(r){t.css({"float":"left",position:"relative"});n.css("width",t[0].offsetWidth*t.length)}var i=e("#staff-slider .scroll").css("overflow","hidden");i.before('<span class="left">&lsaquo;</span>').after('<span class="right">&rsaquo;</span>');e("#staff-slider .navigation").find("a").click(s);window.location.hash?o({id:window.location.hash.substr(1)}):e("ul.navigation a:first").click();var u=parseInt((r?n.css("paddingTop"):n.css("paddingLeft"))||0)*-1,a={target:i,items:t,navigation:".navigation a",prev:"span.left",next:"span.right",axis:"xy",onAfter:o,offset:u,duration:500,easing:"swing"};e("#staff-slider").serialScroll(a);e.localScroll(a);a.duration=1;e.localScroll.hash(a)});