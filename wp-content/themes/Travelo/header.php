<?php
/**
 * The Main Header.
 */
global $trav_options, $logo_url, $my_account_page, $login_url, $signup_url, $redirect_url_on_login;
?>

<!DOCTYPE html>
<!--[if IE 7 ]>    <html class="ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE   ]>    <html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<html <?php language_attributes(); ?>>
<head>
    <!-- Page Title -->
    <title><?php wp_title(' - ', true, 'right'); ?></title>

    <!-- Meta Tags -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if ( ! empty( $trav_options['favicon'] ) ): ?>
    <link rel="shortcut icon" href="<?php echo esc_url( $trav_options['favicon'] ); ?>" type="image/x-icon" />
    <?php endif; ?>

    <!-- CSS for IE -->
    <!--[if lte IE 9]>
        <link rel="stylesheet" type="text/css" href="css/ie.css" />
    <![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script type='text/javascript' src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
      <script type='text/javascript' src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
    <![endif]-->
    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>
    <div id="page-wrapper">

<?php
    $header_style = empty( $trav_options['header_style'] )?'header':$trav_options['header_style'];
    $header_file =$header_style . '.php';
    trav_get_template( $header_file, '/templates/headers/' );
?>

<!-- mobile menu -->
<?php
    $mobile_mini_menu = '<ul class="mobile-topnav container">';
	if ( ! empty( $my_account_page ) ) {
	$mobile_mini_menu .= '<li><a href="' . esc_url( $my_account_page ) . '"' . ( ( $my_account_page == '#travelo-login' ) ? ' class="soap-popupbox"' : '') . '>' . esc_html__( 'MY ACCOUNT', 'trav' ) . '</a></li>';
	}

    if ( is_user_logged_in() ) {
		$mobile_mini_menu .= '<li><a href="' . esc_url( wp_logout_url( trav_get_current_page_url() ) ) . '">' . __( 'LOGOUT', 'trav' ) . '</a></li>';
    } else {
		$mobile_mini_menu .= '<li><a href="' . $login_url . '"' . (( $login_url == '#travelo-login' )?' class="soap-popupbox"':'') . '>' . __( 'LOGIN', 'trav' ) . '</a></li>';
        if ( get_option('users_can_register') ) {
			$mobile_mini_menu .= '<li><a href="' . $signup_url . '"' . (( $signup_url == '#travelo-signup' )?' class="soap-popupbox"':'') . '>' . __( 'SIGNUP', 'trav' ) . '</a></li>';
        }
    }

    if ( ! empty( $trav_options['site_currencies'] ) && count( array_filter( $trav_options['site_currencies'] ) ) > 1 ) {
        $mobile_mini_menu .= '<li class="ribbon currency menu-color-skin">';
		$mobile_mini_menu .= '<a href="#" title="' . esc_attr( trav_get_user_currency() ) . '">' . esc_attr( trav_get_user_currency() ) . '</a>';
        $mobile_mini_menu .= '<ul class="menu mini">';
        
        $all_currencies = trav_get_all_available_currencies();
        foreach ( array_filter( $trav_options['site_currencies'] ) as $key => $content) {
            $class = "";
            if ( trav_get_user_currency() == $key ) $class = ' class="active"';
            $params = $_GET;
            $params['selected_currency'] = $key;
            $paramString = http_build_query($params, '', '&amp;');
			$mobile_mini_menu .= '<li' . $class . '><a href="' . esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) . '?' . $paramString ) . '" title="' . esc_attr( $all_currencies[$key] ) . '">' . esc_html( strtoupper( $key ) ) . '</a></li>';
        }

        $mobile_mini_menu .= '</ul>';
        $mobile_mini_menu .= '</li>';
    }

    $mobile_mini_menu .= '</ul>';
    $mobile_mini_menu = str_replace( '%', '%%', $mobile_mini_menu ); // to escape % parsing
    if ( has_nav_menu( 'header-menu' ) ) {
        wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => 'nav', 'container_class' => 'mobile-menu collapse', 'container_id' => 'mobile-menu-01', 'menu_class' => 'menu', 'menu_id' => 'mobile-primary-menu', 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>' . $mobile_mini_menu ) ); 
    } else { ?>
        <nav id="mobile-menu-01" class="mobile-menu collapse">
            <ul id="mobile-primary-menu" class="menu">
                <li class="menu-item"><a href="<?php echo esc_url( home_url() ); ?>"><?php _e('Home', "trav"); ?></a></li>
                <li class="menu-item"><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php _e('Configure', "trav"); ?></a></li>
            </ul>
			<?php echo wp_kses_post( $mobile_mini_menu ); ?>
        </nav>
<?php } ?>
<!-- mobile menu -->
</header>

<?php
if ( ! is_user_logged_in() ) {
    if ( get_option('users_can_register') ) {
    $signup_desc = __( "By signing up, I agree to Travelo's Terms of Service, Privacy Policy, Guest Refund Policy, and Host Guarantee Terms.", 'trav' ); ?>
        <div id="travelo-signup" class="travelo-modal-box travelo-box">
            <div>
                <a href="#" class="logo-modal"><?php bloginfo( 'name' );?><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo('name'); ?>"></a>
            </div>
            <form name="registerform" action="<?php echo esc_url( wp_registration_url() )?>" method="post">
                <div class="form-group">
                    <input type="text" name="user_login" class="input-text full-width" placeholder="<?php _e( 'user name', 'trav' ) ?>">
                </div>
                <div class="form-group">
                    <input type="email" name="user_email" class="input-text full-width" placeholder="<?php _e( 'email address', 'trav' ) ?>">
                </div>
                <div class="form-group">
					<p class="description"><?php echo esc_html( $signup_desc ); ?></p>
                </div>
                <input type="hidden" name="redirect_to" value="<?php echo esc_url( add_query_arg( array('checkemail' => 'confirm'), wp_login_url() ) )?>">
                <button type="submit" class="full-width btn-medium"><?php _e( 'SIGN UP', 'trav' ); ?></button>
            </form>
            <div class="seperator"></div>
            <p><?php _e( 'Already a member?', 'trav' ); ?> <a href="#travelo-login" class="goto-login soap-popupbox"><?php _e( 'Login', 'trav' ); ?></a></p>
        </div>
    <?php } ?>
    <div id="travelo-login" class="travelo-modal-box travelo-box">
        <div>
            <a href="#" class="logo-modal"><?php bloginfo( 'name' );?><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo('name'); ?>"></a>
        </div>
        <form name="loginform" action="<?php echo esc_url( wp_login_url() )?>" method="post">
            <div class="form-group">
                <input type="text" name="log" tabindex="1" class="input-text full-width" placeholder="<?php _e( 'user name', 'trav' ); ?>">
            </div>
            <div class="form-group">
                <input type="password" name="pwd" tabindex="2" class="input-text full-width" placeholder="<?php _e( 'password', 'trav' ); ?>">
            </div>
            <div class="form-group">
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-password pull-right"><?php _e( 'Forgot password?', 'trav' ); ?></a>
                <div class="checkbox checkbox-inline">
                    <label>
                        <input type="checkbox" name="rememberme" tabindex="3" value="forever"> <?php _e( 'Remember me', 'trav' ); ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <button tabindex="4" class="button btn-medium btn-login full-width"><?php _e('LOG IN', 'trav')?></button>
            </div>
			<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect_url_on_login ) ?>">
        </form>
        <?php if ( get_option('users_can_register') ) { ?>
        <div class="seperator"></div>
        <p><?php echo __( "Don't have an account?", 'trav' ); ?> <a href="#travelo-signup" class="goto-signup soap-popupbox"><?php _e( 'Sign up', 'trav' ); ?></a></p>
        <?php } ?>
    </div>
<?php } ?>
<?php trav_get_template( 'inner-1.php', '/templates/inners' ); ?>