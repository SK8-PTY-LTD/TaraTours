<?php
class IncludeCssJs {

	public static function mi_add_css_js(){

		$src_css =  get_template_directory_uri()."";
		
			
		
		$css_file = AfterSetupTheme::mi_return_theme_option('style_switcher');
		if($css_file==null){
			$css_file = 'stylesheet';
		}
		$protocol = is_ssl() ? 'https' : 'http';
		$css =array(
				'opensans'=>$protocol.'://fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700|Raleway:300,400,500,600',
				
				'bootstrap'=>'/styles/bootstrap/css/bootstrap.min.css',
				'main'=>'/styles/common.css',
				'mainstyle'=>'/style.css',
				'stylesheet'=>'/styles/themes/default/stylesheet.css',
				'flexslider'=>'/styles/flexslider.css',
				'fontawesome'=>'/styles/fontawesome/font-awesome.min.css',
				'jqueryui1'=>'/styles/jquery-ui-1.10.3.custom.min.css',
				'mislider'=>'/styles/mi-slider.css',
				'chosen'=>'/styles/chosen.css',
				'rangeslider'=>'/styles/rangeslider-classic.css',
				'elegantfont'=>'/styles/elegant-font/style1.css',
				'prettyPhoto'=>'/styles/prettyphoto/css/prettyPhoto.css',
				'owl'=>'/styles/owlcarousel2/assets/owl.carousel.min.css',
				'owlmin'=>'/styles/owlcarousel2/assets/owl.theme.default.min.css',
								
				$css_file=>'/styles/themes/default/'.$css_file.'.css'
		);
			
		foreach ($css as $key=>$value){
			if($key=='opensans'){
				wp_register_style($key, $value);
			}else{
			wp_register_style($key, $src_css.$value);
			}
			wp_enqueue_style($key);
		}
		
			
	}

	static function mi_add_admin_css_js(){
		$src_css =  get_template_directory_uri()."/css/";
		$css =array(
				'admin'=>'admin.css',
		);
		foreach ($css as $key=>$value){
		
			wp_register_style($key, $src_css.$value);
			wp_enqueue_style($key);
		}
	}
}