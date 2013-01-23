/* Author: 

*/

// Allows you to use the $ shortcut.  Put all your code  inside this wrapper
jQuery(document).ready(function($) {
	
	// Add .active to certain elements
	$(".item:first-child").addClass("active");
	$("#locations li:first-child").addClass("active");
	$(".tab-pane:first-child").addClass("active");

	// Last word bold on section headings
	$("h1.section-header .main-title").lettering('words');

	// Company Info Bio
	$(".full-bio span").click(function(){
		$(this).parent(".full-bio").find("p").slideToggle('fast', function (){
	      if ($(this).is(":visible")) {
	        $(this).siblings("span").text("Read Less");
	      } 
	      else {
	        $(this).siblings("span").text("Read More");     
	      }
	    });
	});
	
	$("a.jd").click(function(){
		$("thead.job-description div").slideToggle("fast");
	});

	$("#sls span, #ils span").click(function(){
		$(".service-copy").slideToggle();
		$("#ils").html("<span class='poop'>Back</span>");
	});

	// Show Comments 
	$(".show-comments").click(function(){
		$("#disqus_thread").slideToggle();
	});

	// Job Listing Filters
        $('input[type="checkbox"]').click(function() {
            var any = false;
            if ($('input[type="checkbox"]:checked').length > 0) {
                $('table#wpjb-job-list tr.wpjb-free').hide();
                $('input[type="checkbox"]:checked').each(function() {
                    $('table#wpjb-job-list tr.wpjb-free[data-county=' + this.id + ']').each(function() {
                        $(this).show();
                        any = true;
                    });
                });
            } else {
                $('table#wpjb-job-list tr.wpjb-free').each(function() {
                    $(this).show();
                    any = true;
                });
            }
            $('.no-jobs-row').toggle(!any);
        });

	 // Job Application 
	 $('.wpjb-element-name-applicant_name').before('<h3>Personal Information</h3>');
	 $('.wpjb-element-name-field_14').after('<h3 class="ha1">Education</h3>');
	 $('.ha1').after('<h4>High School</h4>');
	 $('.wpjb-element-name-field_20').after('<h4>College</h4>');
	 $('.wpjb-element-name-field_25').after('<h4>Graduate School</h4>');
	 $('.wpjb-element-name-field_30').after('<h4>Other</h4>');
	 $('.wpjb-element-name-field_35').after('<h3>U.S. Military Service</h3>');
	 $('.wpjb-element-name-field_38').after('<h3>Special Skills</h3>');
	 $('.wpjb-element-name-field_40').after('<h3>Legal</h3><p>Identity and employment eligibility of all new hires will be verified as required by the immigration reform and control act of 1986.</p>');
	 $('.wpjb-element-name-field_43').after('<h3 class="ha2">Please indicate in the space below times that you be available to work</h3>');
	 $('.ha2').after('<h4>Monday</h4>');
	 $('.wpjb-element-name-field_45').after('<h4>Tuesday</h4>');
	 $('.wpjb-element-name-field_47').after('<h4>Wednesday</h4>');
	 $('.wpjb-element-name-field_49').after('<h4>Thursday</h4>');
	 $('.wpjb-element-name-field_51').after('<h4>Friday</h4>');
	 $('.wpjb-element-name-field_53').after('<h4>Saturday</h4>');
	 $('.wpjb-element-name-field_55').after('<h4>Sunday</h4>');
	 $('.wpjb-element-name-field_57').after('<h3>Employment History</h3><p class="ha3">Please list your last three (3) employers, assignments, volunteer activities, starting with the most recent, including military experience.  Please explain any gaps in employment in the comments section below.</p>');
	 $('.ha3').after('<h4>Recent Employer 1</h4>');
	 $('.wpjb-element-name-field_69').after('<h4>Recent Employer 2</h4>');
	 $('.wpjb-element-name-field_81').after('<h4>Recent Employer 3</h4>');
	 $('.wpjb-element-name-field_94').after('<h3>References</h3><p class="ha4">Give below the names of three persons not related to you whom you have known atleast one year</p>');
	 $('.ha4').after('<h4>Reference 1</h4>');
	 $('.wpjb-element-name-field_99').after('<h4>Reference 2</h4>');
	 $('.wpjb-element-name-field_104').after('<h4>Reference 3</h4>');


	//Staff Success Stories Slider
	var $panels = $('#staff-slider .scrollContainer > div');
	  var $container = $('#staff-slider .scrollContainer');

	  // if false, we'll float all the panels left and fix the width 
	  // of the container
	  var horizontal = true;

	  // float the panels left if we're going horizontal
	  if (horizontal) {
	    $panels.css({
	      'float' : 'left',
	      'position' : 'relative' // IE fix to ensure overflow is hidden
	    });
	    
	    // calculate a new width for the container (so it holds all panels)
	    $container.css('width', $panels[0].offsetWidth * $panels.length);
	  }

	  // collect the scroll object, at the same time apply the hidden overflow
	  // to remove the default scrollbars that will appear
	  var $scroll = $('#staff-slider .scroll').css('overflow', 'hidden');

	  // apply our left + right buttons
	  $scroll
	    .before('<span class="left">&lsaquo;</span>')
	    .after('<span class="right">&rsaquo;</span>');

	  // handle nav selection
	  function selectNav() {
	    $(this)
	      .parents('ul:first')
	        .find('a')
	          .removeClass('selected')
	        .end()
	      .end()
	      .addClass('selected');
	  }

	  $('#staff-slider .navigation').find('a').click(selectNav);

	  // go find the navigation link that has this target and select the nav
	  function trigger(data) {
	    var el = $('#staff-slider .navigation').find('a[href$="' + data.id + '"]').get(0);
	    selectNav.call(el);
	  }

	  if (window.location.hash) {
	    trigger({ id : window.location.hash.substr(1) });
	  } else {
	    $('ul.navigation a:first').click();
	  }

	  // offset is used to move to *exactly* the right place, since I'm using
	  // padding on my example, I need to subtract the amount of padding to
	  // the offset.  Try removing this to get a good idea of the effect
	  var offset = parseInt((horizontal ? 
	    $container.css('paddingTop') : 
	    $container.css('paddingLeft')) 
	    || 0) * -1;


	  var scrollOptions = {
	    target: $scroll, // the element that has the overflow
	    
	    // can be a selector which will be relative to the target
	    items: $panels,
	    
	    navigation: '.navigation a',
	    
	    // selectors are NOT relative to document, i.e. make sure they're unique
	    prev: 'span.left', 
	    next: 'span.right',
	    
	    // allow the scroll effect to run both directions
	    axis: 'xy',
	    
	    onAfter: trigger, // our final callback
	    
	    offset: offset,
	    
	    // duration of the sliding effect
	    duration: 500,
	    
	    // easing - can be used with the easing plugin: 
	    // http://gsgd.co.uk/sandbox/jquery/easing/
	    easing: 'swing'
	  };

	  // apply serialScroll to the staff-slider - we chose this plugin because it 
	  // supports// the indexed next and previous scroll along with hooking 
	  // in to our navigation.
	  $('#staff-slider').serialScroll(scrollOptions);

	  // now apply localScroll to hook any other arbitrary links to trigger 
	  // the effect
	  $.localScroll(scrollOptions);

	  // finally, if the URL has a hash, move the staff-slider in to position, 
	  // setting the duration to 1 because I don't want it to scroll in the
	  // very first page load.  We don't always need this, but it ensures
	  // the positioning is absolutely spot on when the pages loads.
	  scrollOptions.duration = 1;
	  $.localScroll.hash(scrollOptions);

	  Filters
	   $("#filters :checkbox").click(function() {

       var re = new RegExp($("#filters :checkbox:checked").map(function() {
                              return this.value;
                           }).get().join("|") );
       $("div").each(function() {
          var $this = $(this);
          $this[re.source!="" && re.test($this.attr("class")) ? "show" : "hide"]();
       });
    });

});























