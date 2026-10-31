<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="topbar">
  <div class="wrap">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <img src="<?php echo esc_url( s24_logo_url() ); ?>" alt="<?php echo esc_attr( s24_mod( 'brand_name' ) ); ?>">
      <span class="brand-name"><?php echo esc_html( s24_mod( 'brand_name' ) ); ?><small><?php echo esc_html( s24_mod( 'brand_tagline' ) ); ?></small></span>
    </a>
    <nav id="nav" aria-label="Menu chính">
      <?php
      wp_nav_menu( array(
          'theme_location' => 'primary',
          'container'      => false,
          'depth'          => 2,
          'fallback_cb'    => 's24_menu_fallback',
      ) );
      ?>
    </nav>
    <?php if ( s24_mod( 'header_btn_text' ) ) : ?>
      <a class="btn" href="<?php echo esc_url( s24_link( 'header_btn_link' ) ); ?>"><?php echo esc_html( s24_mod( 'header_btn_text' ) ); ?></a>
    <?php endif; ?>
    <button class="menu-btn" id="menuBtn" aria-label="Mở menu" aria-expanded="false">☰</button>
  </div>
</header>
