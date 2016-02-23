
(function() {
	
	$get_value = '';
    tinymce.PluginManager.add('my_mce_button', function( editor, url ) {
        editor.addButton( 'my_mce_button', {

            text: 'Shortcode',

            icon: false,

            type: 'menubutton',

            menu: [

                {

                            text: 'About',

                           menu: [

                            
                        {

                            text: 'Home About',

                            onclick: function() {

                                editor.windowManager.open( {

                                    title: 'Feature Style One Shortcode',

                                    body: [

                                        {

                                            type: 'textbox',

                                            name: 'a_text1',

                                            label: 'About Title',

                                            value: 'About',
                                            
                                        },

                                        {

                                            type: 'textbox',

                                            name: 'a_text2',

                                            label: 'About Hilighted Text',

                                            value: 'Us',
                                           
                                        },

                                        {

                                            type: 'textbox',

                                            name: 'a_content1',

                                            label: 'Write About us Content',

                                            value: 'Integer sollicitudin ligula non enim sodales, non lacinia nunc ornare. Sed commodo tempor dapibus.<br /> Duis convallis turpis in tortor volutpat, eget rhoncus nisi fringilla. Phasellus ornare risus in euismod varius nullam feugiat ultrices.<br /> Sed condimentum est libero, aliquet iaculis diam bibendum ullamcorper.',
                                            multiline: true,

                                            minWidth: 450,

                                            minHeight: 60,

                                        },

                                        {

                                            type: 'textbox',

                                            name: 'aftitle1',

                                            label: 'About Feature Title 1',

                                            value: 'Honey Moon',
                                            
                                        },

                                        
                                        {

                                            type: 'textbox',

                                            name: 'afcontent1',

                                            label: 'About Feature Content 1',

                                            value: 'Integer sollicitudin ligula non enim sodales<br />Non lacinia nunc ornare. Sed commodo tempor dapibus.<br />Duis convallis turpis in tortor volutpat, eget rhoncus',
                                            multiline: true,

                                            minWidth: 650,

                                            minHeight: 48,

                                        },

                                        {

                                            type: 'textbox',

                                            name: 'alink1',

                                            label: 'About Feature Link 1',

                                            value: '#',
                                            
                                        },

                                          {

                                            type: 'textbox',

                                            name: 'aftitle2',

                                            label: 'About Feature Title 2',

                                            value: 'Explore Nature',
                                            
                                        },

                                        
                                        {

                                            type: 'textbox',

                                            name: 'afcontent2',

                                            label: 'About Feature Content 2',

                                            value: 'Integer sollicitudin ligula non enim sodales<br />Non lacinia nunc ornare. Sed commodo tempor dapibus.<br />Duis convallis turpis in tortor volutpat, eget rhoncus',
                                            multiline: true,

                                            minWidth: 650,

                                            minHeight: 48,

                                        },

                                        {

                                            type: 'textbox',

                                            name: 'alink2',

                                            label: 'About Feature Link 2',

                                            value: '#',
                                            
                                        },

                                        {

                                            type: 'textbox',

                                            name: 'aftitle3',

                                            label: 'About Feature Title 3',

                                            value: 'Amazing Travel',
                                            
                                        },

                                        
                                        {

                                            type: 'textbox',

                                            name: 'afcontent3',

                                            label: 'About Feature Content 3',

                                            value: 'Integer sollicitudin ligula non enim sodales<br />Non lacinia nunc ornare. Sed commodo tempor dapibus.<br />Duis convallis turpis in tortor volutpat, eget rhoncus',
                                            multiline: true,

                                            minWidth: 650,

                                            minHeight: 48,

                                        },

                                        {

                                            type: 'textbox',

                                            name: 'alink3',

                                            label: 'About Feature Link 3',

                                            value: '#',
                                            
                                        },                                       
                                    ],

                                    onsubmit: function( e ) {

                                        editor.insertContent( '[tl_about_border title="'+e.data.a_text1+'" htitle="'+e.data.a_text2+'" acontent="'+e.data.a_content1+'"][tl_about_content aftitle="'+e.data.aftitle1+'" afcontent="'+e.data.afcontent1+'" alink="'+e.data.alink1+'"][tl_about_content aftitle="'+e.data.aftitle2+'" afcontent="'+e.data.afcontent2+'" alink="'+e.data.alink2+'"][tl_about_content aftitle="'+e.data.aftitle3+'" afcontent="'+e.data.afcontent3+'" alink="'+e.data.alink3+'"][/tl_about_border]');
                                        
                                    }

                                });

                            }

                        },
                                                                       
                    ]
                        },

                {

                            text: 'Destination',

                           menu: [

                            
                        {

                            text: 'Destination Home',

                            onclick: function() {

                                editor.windowManager.open( {

                                    title: 'Destination Home Shortcode',

                                    body: [

                                       
                                        {

                                            type: 'textbox',

                                            name: 'bg_image',

                                            label: 'Write Banckground Image Link',

                                            value: 'http://webpentagon.com/demo/themeforest/wordpress/voyo/wp-content/themes/voyo/images/demo/s-106.jpg',
                                            multiline: true,

                                            minWidth: 600,

                                        },

                                        {

                                            type: 'textbox',

                                            name: 'dtitle1',

                                            label: 'Write Destination Title',

                                            value: 'Awesome',

                                        },

                                        {

                                            type: 'textbox',

                                            name: 'dtitle2',

                                            label: 'Write Destination Highited Title',

                                            value: 'Destinations',

                                        },

                                        {

                                            type: 'textbox',

                                            name: 'd_content',

                                            label: 'Write Destination Content',

                                            value: 'Integer sollicitudin ligula non enim sodales, non lacinia nunc ornare. Sed commodo tempor dapibus.<br /> Duis convallis turpis in tortor volutpat, eget rhoncus nisi fringilla. Phasellus ornare risus in euismod varius nullam feugiat ultrices.<br /> Sed condimentum est libero, aliquet iaculis diam bibendum ullamcorper.',
                                            multiline: true,

                                            minWidth: 600,

                                            minHeight:70,

                                        },

                                         
                                       
                                    ],

                                    onsubmit: function( e ) {

                                        editor.insertContent( '[tl_destination_home bg_img="'+e.data.bg_image+'" dtitle1="'+e.data.dtitle1+'" dtitle2="'+e.data.dtitle2+'" d_content="'+e.data.d_content+'"]');
                                        
                                    }

                                });

                            }

                        },

                        
                        
                                               
                    ]
                        },

                        {

                            text: 'Offer',

                           menu: [

                           {

                            text: 'Offer Home',

                            onclick: function() {
  

                                        editor.insertContent( '[tl_offer_home]' );
                                        
                                        }

                        }, 
                                            
                      
                    ]
                        },

                        

                        {

                      text: 'Hotel',

                           menu: [

                        {

                            text: 'Our Travel',
                            

                            onclick: function() {

                                editor.windowManager.open( {

                                    title: 'Our Travel Shortcode',

                                    body: [

                                            {
                                                        
                                                type: 'textbox',
                                                        
                                                name: 'tr_title',
                                                        
                                                label: 'Our Travel Title',
                                                        
                                                value: 'Our Travel'
                                                        
                                            },

                                            {
                                                        
                                                type: 'textbox',
                                                        
                                                name: 'tr_content',
                                                        
                                                label: 'Our Travel Content',
                                                        
                                                value: 'Integer sollicitudin ligula non enim sodales, non lacinia nunc ornare. Sed commodo tempor dapibus.<br /> Duis convallis turpis in tortor volutpat, eget rhoncus nisi fringilla. Phasellus ornare risus in euismod varius nullam feugiat ultrices.<br /> Sed condimentum est libero, aliquet iaculis diam bibendum ullamcorper.',
                                                
                                                multiline: true,
                                                        
                                                minWidth: 450,
                                                        
                                                minHeight: 100,        
                                            },


                                                                                                                   

                                    ],

                                    onsubmit: function( e ) {

                                        editor.insertContent( '[tl_travel_home tr_title="'+e.data.tr_title+'" tr_content="'+e.data.tr_content+'"]' );
                                        
                                    }

                                });
                                 
                            }

                        },

                                            
                      
                    ]
                        },

                                           
                        {

                      text: 'Contact',

                           menu: [

                        
                        {

                            text: 'Contact Shortocde',
                            

                            onclick: function() {

                                editor.windowManager.open( {

                                    title: 'Contact Form Shortocde',

                                    body: [

                                            {
                                                        
                                                type: 'textbox',
                                                        
                                                name: 'bg_image',
                                                        
                                                label: 'Banckground Image Link',
                                                        
                                                value: 'http://webpentagon.com/demo/themeforest/wordpress/traveline/wp-content/themes/traveline/styles/themes/default/images/world-map-bg.png',

                                                multiline: true,
                                            
                                                minWidth: 650,

                                                minHeight: 100,
                                                                                                                                                   
                                            },

                                            {
                                                        
                                                type: 'textbox',
                                                        
                                                name: 'form_s',
                                                        
                                                label: 'Contact Form Shortocde',
                                                        
                                                value: '[contact-form-7 id="275" title="Contact form 1"]',

                                                multiline: true,
                                            
                                                minWidth: 650,
                                                 
                                            },

                                            
                                    ],

                                    onsubmit: function( e ) {

                                        editor.insertContent( '[tl_contact bg_image="'+e.data.bg_image+'"]'+e.data.form_s+'[/tl_contact]' );
                                        
                                    }

                                });
                                 
                            }

                        },

                        

                    ]
                        },
                       
                        {

                            text: 'Home Testimonial',

                            onclick: function() {
                                editor.windowManager.open( {

                                    title: 'Home Testimonial Shortocde',

                                    body: [
                                            
                                            {
                                                        
                                                type: 'textbox',
                                                        
                                                name: 'tetitle',
                                                        
                                                label: 'Testimonial Title',
                                                        
                                                value: 'What Other People Say About Us',

                                                multiline: true,
                                            
                                                minWidth: 650,
                                                 
                                            },

                                            {
                                                        
                                                type: 'textbox',
                                                        
                                                name: 'teftitle',
                                                        
                                                label: 'Figur title',
                                                        
                                                value: 'These Impressive Figures',

                                                multiline: true,
                                            
                                                minWidth: 650,
                                                 
                                            },
                                                                                                                                                             

                                    ],

                                    onsubmit: function( e ) {

                                        editor.insertContent( '[tl_testimonial_home tetitle="'+e.data.tetitle+'" teftitle="'+e.data.teftitle+'"]' );
                                        
                                    }

                                });
                             
                            }

                        },

                  
                    ]  

        });
       
    });
    
   
})(jQuery);

