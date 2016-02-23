// Avoid `console` errors in browsers that lack a console.
(function() {
    "use strict";
    for (var a, e = function() {
    }, b = "assert clear count debug dir dirxml error exception group groupCollapsed groupEnd info log markTimeline profile profileEnd table time timeEnd timeStamp trace warn".split(" "), c = b.length, d = window.console = window.console || {}; c--; )
        a = b[c], d[a] || (d[a] = e);

jQuery('.menu-button button').click(function(){
        jQuery('.show-menu').slideToggle(500);
    })


jQuery(window).ready(function(jQuery) {

    // Resize window


    /** 
     * Handle window resizieng on the fly
     * ======================================= */


    var wi = jQuery(window).width();

    jQuery(window).resize(function() {
         jQuery('#mi-nav').height((jQuery('#mi-nav a').length - 1) * (jQuery('#mi-nav a').outerHeight() + parseInt(jQuery('#mi-nav a').css('margin-bottom'))));
  
//        var wi = jQuery(window).width();
//
//        var first = '#special-offers';
//        var second = '#mi-slider img';
//
//        jQuery(first).height() > jQuery(second).height() ? jQuery(second).height(jQuery(first).height()) : jQuery(first).height(jQuery(second).height());


    });


    // Close search hotel from in featured section
    jQuery('.open-close-btn').click(function() {
        if (jQuery('.featured-overlay').hasClass('closed')) {//open it
            jQuery('.opener-area').css('left', '-100px');
            setTimeout(function() {

                jQuery('.featured-overlay').css('left', '0').removeClass('closed');
            }, 300);
        } else {//close it
            jQuery('.featured-overlay').css('left', '-40%').addClass('closed');
            setTimeout(function() {
                jQuery('.opener-area').css('left', '0px');

            }, 300);

        }

    });

    // OWl Carousel
    if (jQuery(".partners-carousel .owl-carousel").length) {
        jQuery(".partners-carousel .owl-carousel").owlCarousel({
            autoplay: true,
            loop: true,
            margin: 25,
            dots: true,
            nav: false,
            navText: [
                "<i class='fa fa-caret-left'></i>",
                "<i class='fa fa-caret-right'></i>"
            ],
            responsive: {
                0:    {items: 1},
                479:  {items: 2},
                768:  {items: 3},
                991:  {items: 5},
                1024: {items: 6}
            }
        });
    }
    // OWl Carousel
    if (jQuery(".img-carousel").length) {
        jQuery(".img-carousel").owlCarousel({
            autoplay: true,
            loop: true,
            margin: 25,
            dots: true,
            nav: false,
            navText: [
                "<i class='fa fa-caret-left'></i>",
                "<i class='fa fa-caret-right'></i>"
            ],
            responsive: {
                0:    {items: 1},
                479:  {items: 1},
                768:  {items: 1},
                991:  {items: 1},
                1024: {items: 1}
            }
        });
    }

    // faq-filters
    if (jQuery(".faq-filters").length) {
        jQuery(".faq-filters li").on('click', function (event) {
            event.preventDefault();
            if (jQuery(this).hasClass('active')) {
                jQuery(this).removeClass('active');
            }
            else {
                jQuery(this).addClass('active');
            }
        });
    }


});

/* Static Window Width */

jQuery(window).ready(function(jQuery) {


    // Static window

    var first1 = '#special-offers';
    var second1 = '#mi-slider img';
    var window_width = jQuery(window).width();
    if (window_width < 9999) {


        jQuery(first1).height() > jQuery(second1).height() ? jQuery(second1).height(jQuery(first1).height()) : jQuery(first1).height(jQuery(second1).height());

    }


    if (jQuery('.section-amazing-tours').length > 0) {
        jQuery('.section-amazing-tours .items-holder').carouFredSel({
            auto: false,
            responsive: true

        });


        jQuery(".section-amazing-tours  .next").click(function(event) {
            event.preventDefault();
            jQuery('.section-amazing-tours .items-holder').trigger("next", 1);

        });


        jQuery(".section-amazing-tours .previous").click(function(event) {
            event.preventDefault();
            jQuery('.section-amazing-tours .items-holder').trigger("prev", 1);

        });

    }

    if (jQuery('#frame').length > 0) {
        jQuery('#frame').sly({
            scrollBar: jQuery('#scrollBar'),
            dragHandle: 1,
            easing: 'easeOutExpo',
            dragging: 1,
            scrollBy: 20,
            cycleBy: 'items'
        });


    }

    if (jQuery('.single-slider').length > 0) {
        var singlePSlider = jQuery('.single-slider').carouFredSel({
            auto: false

        });

        jQuery(".single-slider-holder .main-slide-nav .next-btn").click(function(event) {
            event.preventDefault();
            jQuery('.single-slider').trigger("next", 1);

        });


        jQuery(".single-slider-holder .main-slide-nav .prev-btn").click(function(event) {
            event.preventDefault();
            jQuery('.single-slider').trigger("prev", 1);

        });



    }

    if (jQuery('.single-slider-thumb-gallery').length > 0) {
        jQuery('.single-slider-thumb-gallery ul').carouFredSel({
            auto: false,
        
            circular: true
        });

        jQuery(".single-slider-thumb-gallery .next-btn").click(function(event) {
            event.preventDefault();
            jQuery('.single-slider-thumb-gallery ul').trigger("next", 1);

        });


        jQuery(".single-slider-thumb-gallery .prev-btn").click(function(event) {
            event.preventDefault();
            jQuery('.single-slider-thumb-gallery ul').trigger("prev", 1);

        });


        jQuery(".single-slider-thumb-gallery .horizontal-gallery-item").click(function(event) {
            event.preventDefault();
           var tid = jQuery(this).attr('href');
          var  targetSlide = jQuery(".single-slider " + tid);

            singlePSlider.trigger('slideTo', targetSlide);

        });



    }
    if (jQuery('.bar-item').length > 0) {
        jQuery('.bar-item').each(function() {
            var bar = jQuery(this).find('.bar');
            var w = bar.attr('data-width');
            bar.append('<div class="pbar" ></div>');
            bar.find('.pbar').delay(jQuery(this).index() * 200).animate({
                width: w
            }, 1000, 'easeOutBack');
        });
    }
    if (jQuery("#sliderz").length > 0) {
        jQuery("#sliderz").rangeSlider();
    }


    if (jQuery('#Grid').length > 0) {
        jQuery('#Grid').mixitup();
    }
      
    if (jQuery('.destination-lists').length > 0 &&  jQuery(window).width()>779) {
       
      var jQuerycontainer = jQuery('.destination-lists');
        // initialize
        jQuerycontainer.masonry({
            itemSelector: '.destination'
        });
        
        setTimeout(function(){
            jQuerycontainer.masonry('reloadItems');
        },500);
    }

    if (jQuery('.traveline_date_input').length > 0) {
        jQuery('.traveline_date_input').datepicker({
            dateFormat: 'd MM yy' // Date format http://jqueryui.com/datepicker/#date-formats
        });
    }
    if (jQuery('#mi-slider').length > 0) {

        jQuery('#mi-nav').height((jQuery('#mi-nav a').length - 1) * (jQuery('#mi-nav a').outerHeight() + parseInt(jQuery('#mi-nav a').css('margin-bottom'))));
        jQuery('#mi-slider').catslider();
        jQuery('#mi-slider ul li').each(function() {
            var el = jQuery(this).find('a');
            var img = el.find('img');
//            el.parent().css('min-width', img.width());
            el.css('background-image', 'url(' + img.attr('src') + ')');
            img.remove();
        });

    }


    if (jQuery('#top-slider').length > 0) {
        jQuery('#top-slider').flexslider({
            animation: "slide"
        });
    }

    if (jQuery('#sliding-testimony').length > 0) {
        jQuery('#sliding-testimony').flexslider({
            animation: "fade",
            controlNav: false,
        });
    }





//Rating Star activator
    if (jQuery('.star').length > 0) {
        if (jQuery('.star.big').length > 0) {
            jQuery('.star').raty({
                space: false,
                starOff: 'http://localhost/traveline/wp-content/themes/traveline/images/star-big-off.png',
                starOn: 'http://localhost/traveline/wp-content/themes/traveline/images/star-big-on.png',
                score: function() {
                    return jQuery(this).attr('data-score');
                }
            });
        } else {
            jQuery('.star').raty({
                space: false,
                starOff: 'http://localhost/traveline/wp-content/themes/traveline/images/star-off.png',
                starOn: 'http://localhost/traveline/wp-content/themes/traveline/images/star-on.png',
                score: function() {
                    return jQuery(this).attr('data-score');
                }
            });
        }

    }


    var mapCreated = false;
    if (jQuery('.tab-holder').length > 0) {
        jQuery('.nav-tabs a').click(function(e) {
            e.preventDefault();

            jQuery(this).tab('show');
            if (jQuery('.tab-pane.active#map').length > 0) {
                createHotelMap();
                mapCreated = true;
            }
        });

    }

//PlaceHolders controller for input

    jQuery('input,textarea').focus(function() {
        jQuery(this).data('placeholder', jQuery(this).attr('placeholder'))
        jQuery(this).attr('placeholder', '');
    });
    jQuery('input,textarea').blur(function() {
        jQuery(this).attr('placeholder', jQuery(this).data('placeholder'));
    });


//Google Map Activator
    var mapIsNotActive = true;

    var homeLang = object_name1.some_string1;
    var homeLat = object_name2.some_string2;
    var homeAddress = "<h3>Traveline House</h3>Trafford Wharf Road, Manchester M17 1AB,<br>United Kingdom<br>+44 161 835 3500";

    function initializeMap(holderID, lang, lat, address) {
        var secheltLoc = new google.maps.LatLng(lang, lat);

        var myMapOptions = {
            zoom: 14
            , center: secheltLoc
            , mapTypeId: google.maps.MapTypeId.ROADMAP
            , disableDefaultUI: true
        };
        var theMap = new google.maps.Map(document.getElementById(holderID), myMapOptions);


        var marker = new google.maps.Marker({
            map: theMap,
            draggable: true,
            position: new google.maps.LatLng(lang, lat),
            visible: true
        });

        var boxText = document.createElement("div");
        boxText.style.cssText = "margin-top: 8px; background-color:#85c616; color:#ffffff; padding: 20px;";
        boxText.innerHTML = address;
        var myOptions = {
            content: boxText
            , disableAutoPan: false
            , maxWidth: 0
                    // ,pixelOffset: new google.maps.Size(-20, -70)
            , pixelOffset: new google.maps.Size(-26, -860)
            , zIndex: null
            , boxStyle: {
                // background: "url('tipbox.gif') no-repeat"
                opacity: 1
                , width: "290px"
            }
            , closeBoxMargin: "10px 2px 2px 2px"
            , closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
            , infoBoxClearance: new google.maps.Size(1, 1)
            , isHidden: false
            , pane: "floatPane"
            , enableEventPropagation: false
        };

        google.maps.event.addListener(marker, "click", function(e) {
            ib.open(theMap, this);
        });

        var ib = new InfoBox(myOptions);

        ib.open(theMap, marker);
    }
    if (jQuery('#map_canvas').length > 0) {



        google.maps.event.addDomListener(window, 'load', initializeMap('map_canvas', homeLang, homeLat, homeAddress));
        jQuery("#map_canvas").fitMaps();
    }


    function createHotelMap() {
        if (!mapCreated) {
            var el = jQuery('.hotel-map-holder');

            google.maps.event.addDomListener(window, 'load', initializeMap('hotel-map', el.attr('data-lang'), el.attr('data-lat'), el.attr('data-info')));
        }
    }


    if (jQuery(".custom-checkbox").length > 0) {
        jQuery(".custom-checkbox").screwDefaultButtons({
            image: 'url("http://localhost/traveline/wp-content/themes/traveline/images/checkbox.png")',
            width: 16,
            height: 16
        });
    }
    if (jQuery(".hotel-type-filter-widget input:checkbox").length > 0) {
        jQuery(".hotel-type-filter-widget input:checkbox").screwDefaultButtons({
            image: 'url("http://localhost/traveline/wp-content/themes/traveline/images/checkbox.png")',
            width: 16,
            height: 16
        });
    }
    if (jQuery(".rating-filter-widget input:checkbox").length > 0) {
        jQuery(".rating-filter-widget input:checkbox").screwDefaultButtons({
            image: 'url("http://localhost/traveline/wp-content/themes/traveline/images/checkbox.png")',
            width: 16,
            height: 16
        });
    }


jQuery('.top-drop-menu').change(function() {
        var loc = (jQuery(this).find('option:selected').val());
        window.location = loc;

    });

    if (jQuery(".chosen-select").length > 0) {
        jQuery(".chosen-select").chosen({max_selected_options: 5});
    }
    if (jQuery(".custom-select").length > 0) {
        jQuery(".custom-select").chosen({disable_search_threshold: 10});
    }
    jQuery('.toggle-menu').click(function(e) {
        e.preventDefault();
        var el = jQuery(this);
        el.toggleClass('active');
        if (el.hasClass('active')) {
            jQuery('.toggle-menu-holder .menu-body').removeClass('closed').addClass('opened');

        } else {
            jQuery('.toggle-menu-holder .menu-body').removeClass('opened').addClass('closed');
        }
    });
    
  jQuery('#StyleSwitcher .switcher-btn').click(function () {

    'use strict';

    jQuery('#StyleSwitcher').toggleClass('open');
    return false;
});
jQuery('.color-switch').click(function () {

    'use strict';

    var title = jQuery(this).attr('title');
    jQuery('#color-switch').attr('href', 'css/colors/' + title + '.css');
    return false;
});

			
});

jQuery(window).bind("load", function() {
  jQuery('#status').fadeOut(); // will first fade out the loading animation
			jQuery('#preloader').delay(1000).fadeOut('slow'); // will fade out the white DIV that covers the website.
			jQuery('body').delay(1000).css({'overflow-x':'hidden'}).css({'overflow-y':'auto'});
});

})();

var switcher='<div class="color-switcher"><form id="switchform"><select name="switchcontrol" size="1" onChange="chooseStyle(this.options[this.selectedIndex].value, 60)"><option value="none" selected="selected">Default style</option><option value="02">Style 02</option><option value="03">Style 03</option><option value="04">Style 04</option><option value="05">Style 05</option><option value="06">Style 06</option><option value="07">Style 07</option><option value="08">Style 08</option><option value="09">Style 09</option><option value="10">Style 10</option><option value="11">Style 11</option><option value="12">Style 12</option><option value="13">Style 13</option><option value="14">Style 14</option><option value="15">Style 15</option></select></form>    </div>';
jQuery('body').append('<a class="goto-top" href="#gotop"></a>');

jQuery('.switcher-colors a').click(function(e){
e.preventDefault();
var title=jQuery(this).attr('title');

chooseStyle(title,60);
});

jQuery('.goto-top').click(function(e){
e.preventDefault();
 jQuery('html,body').animate({
          scrollTop: 0
        }, 2000);
});

// Sticky Nav
jQuery(window).scroll(function(e) {
    var nav_anchor = jQuery("#header");
     var gotop = jQuery(document);

 if (jQuery(this).scrollTop() >= gotop.height()/2) {
    jQuery('.goto-top').css({'opacity':1});
 }else if (jQuery(this).scrollTop() < gotop.height()/2)
 {
      jQuery('.goto-top').css({'opacity':0});
 }
    if (jQuery(this).scrollTop() >= nav_anchor.height() && nav_anchor.css('position') != 'fixed' && !nav_anchor.hasClass('fixed-menu')) 
    {    
        nav_anchor.css({
            'position': 'fixed',
            'top': '-200px'
        });
           setTimeout(function(){
               
                 nav_anchor.addClass('splited');
           },200);     
              

        
    } 
    else if (jQuery(this).scrollTop() < nav_anchor.height() && nav_anchor.css('position') != 'relative' && !nav_anchor.hasClass('fixed-menu')) 
    {   

     

        nav_anchor.css({
            
              'top': '0px'
        });
        
        
         setTimeout(function(){
               
                 nav_anchor.css({
            'position': 'relative'
        }).removeClass('splited');
           },200);  
    }

    jQuery("a[data-gal^='prettyPhoto']").prettyPhoto({
            theme: 'dark_square'
        });
});



