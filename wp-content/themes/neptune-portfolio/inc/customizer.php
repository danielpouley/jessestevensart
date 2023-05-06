<?php
/**
 * neptune Theme Customizer
 *
 * @package Neptune WP
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function neptune_portfolio_customize_register( $wp_customize ) {


	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'neptune_portfolio_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'neptune_portfolio_customize_partial_blogdescription',
		) );
	}

	// View PRO Version
	$wp_customize->add_section( 'neptune-portfolio_style_view_pro', array(
		'title'       => '' . esc_html__( 'Get Premium', 'neptune-portfolio' ),
		'priority'    => 2,
		'description' => sprintf(
			__( '<div class="upsell-container">
					<h2>Upgrade to premium</h2>
					<p>Upgrade to premium and get:</p>
					<ul class="upsell-features">
                            <li>
                            	<h4>Portfolio Plugin</h4>
                            	<div class="description">A premium plugin for actual portfolios and gallery like the demo</div>
                            </li>

                            <li>
                            	<h4>Galleries & Albums</h4>
                            	<div class="description">Upload galleries/Albums in your portfolios with a single click</div>
                            </li>
                            


                            <li>
                            	<h4>Tech Support</h4>
                            	<div class="description">Help with setting up the theme if you have any issues</div>
                            </li>
                            
                    </ul> %s </div>', 'neptune-portfolio' ),
			sprintf( '<a href="%1$s" target="_blank" class="button button-primary">%2$s</a>', esc_url( neptune_portfolio_get_pro_link() ), esc_html__( 'View neptune-portfolio PRO', 'neptune-portfolio' ) )
		),
	) );

	$wp_customize->add_setting( 'neptune-portfolio_pro_desc', array(
		'default'           => '',
		'sanitize_callback' => 'neptune-portfolio_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'neptune-portfolio_pro_desc', array(
		'section' => 'neptune-portfolio_style_view_pro',
		'type'    => 'hidden',
	) );
}
add_action( 'customize_register', 'neptune_portfolio_customize_register' );





function neptune_portfolio_settings( $wp_customize ) {

//Header & Footer Colors

	$wp_customize->add_setting( 'header_footer' , array(
	    'default'   => '#fff',
	    'transport' => 'refresh',
	    'sanitize_callback' => 'sanitize_hex_color',
	) );


	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
	'header_footer', 
	array(
		'label'      => __( 'Header & Footer Background Color', 'neptune-portfolio' ),
		'section'    => 'colors',
		'settings'   => 'header_footer',
	) ) 
	);

//Header & Footer Colors

	$wp_customize->add_setting( 'text_color' , array(
	    'default'   => '#000',
	    'transport' => 'refresh',
	    'sanitize_callback' => 'sanitize_hex_color',
	) );


	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 
	'text_color', 
	array(
		'label'      => __( 'Header & Footer Text Color', 'neptune-portfolio' ),
		'section'    => 'colors',
		'settings'   => 'text_color',
	) ) 
	);
}
add_action( 'customize_register', 'neptune_portfolio_settings' );
/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function neptune_portfolio_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function neptune_portfolio_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function neptune_portfolio_customize_preview_js() {
	wp_enqueue_script( 'neptune-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'neptune_portfolio_customize_preview_js' );

//Adds inline CSS the right way.
function neptune_portfolio_customizer_styles() {
	wp_enqueue_style( 'neptune-portfolio-style', get_stylesheet_uri() );
        $hfcolor = esc_attr(get_theme_mod( 'header_footer' ));
        $textcolor = esc_attr(get_theme_mod( 'text_color' ));
        $custom_css = "
                #masthead,
        		.footer-overlay{
                        background: {$hfcolor};
                }
                .site-branding a,
                #cssmenu>ul>li.active>a,
                #cssmenu>ul>li>a {
                	color: {$textcolor};
                }
                ";
        wp_add_inline_style( 'neptune-portfolio-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'neptune_portfolio_customizer_styles' );

/**
 * Admin CSS
 */
function neptune_portfolio_customizer_assets() {
    wp_enqueue_style( 'neptune-portfolio_customizer_style', get_template_directory_uri() . '/css/admin.css', null, '1.0.1', false );
}
add_action( 'customize_controls_enqueue_scripts', 'neptune_portfolio_customizer_assets' );
/**
 * Generate a link to the Noah Lite info page.
 */
function neptune_portfolio_get_pro_link() {
    return 'https://neptunewp.com/downloads/neptune-portfolio';
}
