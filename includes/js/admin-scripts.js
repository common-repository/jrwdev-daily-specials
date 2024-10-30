jQuery(document).ready(function($) {
	// settings tabs
	$(".tab_content").hide(); //Hide all content
	$("h2.nav-tab-wrapper a:first").addClass("nav-tab-active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	$('h2.nav-tab-wrapper a').click(function(e) {				
		e.preventDefault();
		var tab = $(this).attr('href');
		$( 'h2.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$(this).addClass( 'nav-tab-active' );
		$(".tab_content").hide();		
		$("#tab_container " + tab).fadeIn();	
	});
});