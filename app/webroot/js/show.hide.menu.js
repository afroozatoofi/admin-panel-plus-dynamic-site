$(document).ready(function() {    
    var showImg = '<img src="img/menu/DESIGN-33.png" />';
    var hideImg = '<img src="img/menu/DESIGN-32.png" />';
    var showClass = 'showM';
    var hideClass = 'hideM';
    var openClass = 'openC';
	var closeClass= 'closeC';
	
    $('#sidebar h3').click(function() {
    	$span = $(this).find('span.toggleLink');
        var show = $span.hasClass(showClass) ? true : false;
        var name = $(this).attr('code');                
        if (!show){
        	createCookie(name,'show',30);
        	$span.removeClass(hideClass).addClass(showClass);        	
        	$span.html(hideImg);
            $(this).next('.toggle').stop().slideDown(200);            
        } else {            
            createCookie(name,'hide',30);
            $span = $(this).find('span.toggleLink');
            $span.removeClass(showClass).addClass(hideClass);        
            $span.html(showImg);
            $(this).next('.toggle').stop().slideUp(400);
        }       
        setTimeout(function(){
        	$(".nano#sidebar").nanoScroller();
        },400);        
        return false;
    });
    
    $('#sidebar span.toggleMenu').click(function(){    	
    	var open = $(this).hasClass(openClass) ? true : false;
    	if(open){
    		createCookie('menu','close',30);
    		$(this).removeClass(openClass).addClass(closeClass);
    		$('#sidebar,#mainPanel').removeClass('openMenu').addClass('closeMenu');
    		$(this).find('img').attr('src','img/DESIGN-03.png');
    	} else {
    		createCookie('menu','open',30);
    		$(this).removeClass(closeClass).addClass(openClass);
    		$('#sidebar,#mainPanel').removeClass('closeMenu').addClass('openMenu');
    		$(this).find('img').attr('src','img/DESIGN-01.png');
    	} 	
    });
});
function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}