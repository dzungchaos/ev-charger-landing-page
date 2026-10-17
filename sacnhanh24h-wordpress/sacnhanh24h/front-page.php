<?php
/**
 * Trang chủ. Nội dung chỉnh trong Giao diện → Tùy biến → "Trang chủ Sacnhanh24h"
 * và các mục Trạm sạc, Dự án, Đối tác, Ban lãnh đạo, Hãng xe.
 */
get_header();
$m = 's24_mod';
?>

<?php if ( s24_show( 'hero' ) ) : ?>
<section class="hero">
  <div class="wrap">
    <?php if ( $m( 'hero_banner' ) ) : ?>
      <div class="hero-banner">
        <img src="<?php echo esc_url( $m( 'hero_banner' ) ); ?>" alt="<?php echo esc_attr( $m( 'company' ) ); ?>" fetchpriority="high">
      </div>
    <?php endif; ?>
    <div class="hero-intro">
      <h1><?php echo esc_html( $m( 'hero_title_1' ) ); ?><br><em><?php echo esc_html( $m( 'hero_title_2' ) ); ?></em></h1>
      <div>
        <p><?php echo esc_html( $m( 'hero_text' ) ); ?></p>
        <div class="hero-cta">
          <?php if ( $m( 'hero_btn1_text' ) ) : ?><a class="btn lime" href="<?php echo esc_url( s24_link( 'hero_btn1_link' ) ); ?>"><?php echo esc_html( $m( 'hero_btn1_text' ) ); ?></a><?php endif; ?>
          <?php if ( $m( 'hero_btn2_text' ) ) : ?><a class="btn ghost" href="<?php echo esc_url( s24_link( 'hero_btn2_link' ) ); ?>"><?php echo esc_html( $m( 'hero_btn2_text' ) ); ?></a><?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( s24_show( 'pricing' ) ) : ?>
<section class="pricing" id="bang-gia" style="--pricing-mark:url('<?php echo esc_url( s24_logo_url() ); ?>')">
  <div class="wrap price-box">
    <div>
      <span class="kicker"><?php echo esc_html( $m( 'price_kicker' ) ); ?></span>
      <h2><?php echo esc_html( $m( 'price_title' ) ); ?></h2>
      <p><?php echo esc_html( $m( 'price_text' ) ); ?></p>
      <?php if ( $m( 'price_btn_text' ) ) : ?><a class="btn lime" href="<?php echo esc_url( s24_link( 'price_btn_link' ) ); ?>"><?php echo esc_html( $m( 'price_btn_text' ) ); ?></a><?php endif; ?>
    </div>
    <div>
      <div class="tou">
        <?php for ( $i = 1; $i <= 3; $i++ ) : if ( ! $m( "tier{$i}_name" ) && ! $m( "tier{$i}_price" ) ) { continue; } ?>
          <div class="tou-card<?php echo 1 === $i ? ' low' : ''; ?>">
            <h4><?php echo esc_html( $m( "tier{$i}_name" ) ); ?></h4>
            <div class="time"><?php echo esc_html( $m( "tier{$i}_time" ) ); ?></div>
            <div class="val"><?php echo esc_html( $m( "tier{$i}_price" ) ); ?><small> <?php echo esc_html( $m( 'price_unit' ) ); ?></small></div>
          </div>
        <?php endfor; ?>
      </div>
      <?php if ( $m( 'price_note' ) ) : ?><div class="note"><?php echo esc_html( $m( 'price_note' ) ); ?></div><?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( s24_show( 'stations' ) ) : ?>
<section class="stations" id="tram-sac">
  <div class="wrap">
    <h2><?php echo esc_html( $m( 'stations_title1' ) ); ?> <span><?php echo esc_html( $m( 'stations_title2' ) ); ?></span></h2>
    <div class="filters">
      <form class="search" id="searchForm" role="search">
        <input id="q" type="search" placeholder="Tìm kiếm tên trạm sạc" aria-label="Tìm kiếm tên trạm sạc">
        <button type="submit" aria-label="Tìm kiếm"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg></button>
      </form>
      <select class="select" id="province" aria-label="Tỉnh/Thành phố"><option value="">Tỉnh/Thành phố</option></select>
      <select class="select" id="district" aria-label="Quận/Huyện" disabled><option value="">Quận/Huyện</option></select>
    </div>
    <div class="st-grid">
      <div class="st-list-wrap">
        <p class="st-count"><span id="count">0</span> trạm sạc</p>
        <div class="st-list" id="list"></div>
      </div>
      <div id="map" aria-label="Bản đồ trạm sạc"></div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( s24_show( 'about' ) ) : ?>
<section class="about" id="gioi-thieu">
  <div class="wrap">
    <div>
      <span class="kicker"><?php echo esc_html( $m( 'about_kicker' ) ); ?></span>
      <h2 class="sec-title"><?php echo esc_html( $m( 'about_title1' ) ); ?> <span><?php echo esc_html( $m( 'about_title2' ) ); ?></span></h2>
      <?php echo wpautop( esc_html( $m( 'about_text' ) ) ); ?>
      <div class="target">
        <?php for ( $i = 1; $i <= 3; $i++ ) : if ( ! $m( "stat{$i}_num" ) ) { continue; } ?>
          <div><strong><?php echo esc_html( $m( "stat{$i}_num" ) ); ?></strong><span><?php echo esc_html( $m( "stat{$i}_label" ) ); ?></span></div>
        <?php endfor; ?>
      </div>
    </div>
    <?php if ( $m( 'about_image' ) ) : ?>
      <div class="about-photo"><img src="<?php echo esc_url( $m( 'about_image' ) ); ?>" alt="" loading="lazy"></div>
    <?php else : ?>
      <div class="about-logo"><img src="<?php echo esc_url( s24_logo_url() ); ?>" alt="" loading="lazy"></div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php $partners = s24_show( 'partners' ) ? s24_items( 'doi_tac' ) : array(); if ( $partners ) : ?>
<section id="doi-tac">
  <div class="wrap">
    <span class="kicker"><?php echo esc_html( $m( 'partners_kicker' ) ); ?></span>
    <h2 class="sec-title"><?php echo esc_html( $m( 'partners_title' ) ); ?></h2>
    <?php foreach ( $partners as $i => $p ) :
      $btn  = get_post_meta( $p->ID, 'nut_text', true );
      $link = get_post_meta( $p->ID, 'nut_link', true ); ?>
      <div class="partner">
        <div>
          <h3><?php echo esc_html( get_the_title( $p ) ); ?></h3>
          <?php echo wp_kses_post( wpautop( $p->post_content ) ); ?>
          <?php if ( $btn && $link ) : ?><a class="btn ghost" href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $btn ); ?></a><?php endif; ?>
        </div>
        <?php echo s24_media( $p->ID, 'Ảnh đối tác' ); // phpcs:ignore ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php $projects = s24_show( 'projects' ) ? s24_items( 'du_an' ) : array(); if ( $projects ) :
  $groups = array( 'tm' => array(), 'ch' => array() );
  foreach ( $projects as $p ) { $groups[ 'ch' === get_post_meta( $p->ID, 'nhom', true ) ? 'ch' : 'tm' ][] = $p; }
  $tabs = array_filter( array( 'tm' => $m( 'projects_tab1' ), 'ch' => $m( 'projects_tab2' ) ), function ( $label, $k ) use ( $groups ) { return ! empty( $groups[ $k ] ); }, ARRAY_FILTER_USE_BOTH );
  $first = key( $tabs ); ?>
<section class="projects" id="du-an">
  <div class="wrap">
    <span class="kicker"><?php echo esc_html( $m( 'projects_kicker' ) ); ?></span>
    <h2 class="sec-title"><?php echo esc_html( $m( 'projects_title' ) ); ?></h2>
    <?php if ( count( $tabs ) > 1 ) : ?>
      <div class="tabs" role="tablist">
        <?php foreach ( $tabs as $k => $label ) : ?>
          <button class="tab" role="tab" aria-selected="<?php echo $k === $first ? 'true' : 'false'; ?>" data-tab="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?></button>
        <?php endforeach; ?>
      </div>
    <?php else : ?><div style="height:28px"></div><?php endif; ?>
    <?php foreach ( $tabs as $k => $label ) : ?>
      <div class="proj-grid" data-panel="<?php echo esc_attr( $k ); ?>"<?php echo $k === $first ? '' : ' hidden'; ?>>
        <?php foreach ( $groups[ $k ] as $p ) : ?>
          <article class="proj">
            <?php echo s24_media( $p->ID, 'Ảnh dự án', 'medium_large' ); // phpcs:ignore ?>
            <div class="proj-body">
              <div class="proj-loc"><?php echo esc_html( get_post_meta( $p->ID, 'dia_diem', true ) ); ?></div>
              <h4><?php echo esc_html( get_the_title( $p ) ); ?></h4>
              <p><?php echo esc_html( wp_strip_all_tags( $p->post_content ) ); ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php $cars = s24_show( 'cars' ) ? s24_items( 'hang_xe' ) : array(); if ( $cars ) : ?>
<section>
  <div class="wrap">
    <span class="kicker"><?php echo esc_html( $m( 'cars_kicker' ) ); ?></span>
    <h2 class="sec-title"><?php echo esc_html( $m( 'cars_title' ) ); ?></h2>
    <div class="brands-row">
      <?php foreach ( $cars as $c ) : ?>
        <div class="car"><?php
          if ( has_post_thumbnail( $c->ID ) ) {
            echo get_the_post_thumbnail( $c->ID, 'medium', array( 'alt' => esc_attr( get_the_title( $c ) ), 'loading' => 'lazy' ) );
          } else {
            echo esc_html( get_the_title( $c ) );
          } ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( s24_show( 'values' ) ) :
  $icons = array(
    '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-6.2-7-11a7 7 0 0114 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>',
    '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg>',
    '<svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L4 14h7l-1 8 9-12h-7z"/></svg>',
  ); ?>
<section class="values">
  <div class="wrap values-grid">
    <?php for ( $i = 1; $i <= 3; $i++ ) : if ( ! $m( "value{$i}_title" ) ) { continue; } ?>
      <div class="value">
        <div class="ic"><?php echo $icons[ $i - 1 ]; // phpcs:ignore ?></div>
        <h3><?php echo esc_html( $m( "value{$i}_title" ) ); ?></h3>
        <p><?php echo esc_html( $m( "value{$i}_text" ) ); ?></p>
      </div>
    <?php endfor; ?>
  </div>
</section>
<?php endif; ?>

<?php $leaders = s24_show( 'leaders' ) ? s24_items( 'lanh_dao' ) : array(); if ( $leaders ) : ?>
<section>
  <div class="wrap">
    <span class="kicker"><?php echo esc_html( $m( 'leaders_kicker' ) ); ?></span>
    <h2 class="sec-title"><?php echo esc_html( $m( 'leaders_title' ) ); ?></h2>
    <div class="leaders">
      <?php foreach ( $leaders as $l ) : $name = get_the_title( $l ); ?>
        <div class="leader">
          <?php if ( has_post_thumbnail( $l->ID ) ) : ?>
            <div class="avatar"><?php echo get_the_post_thumbnail( $l->ID, 'medium', array( 'alt' => esc_attr( $name ), 'loading' => 'lazy' ) ); ?></div>
          <?php else : ?>
            <div class="avatar"><?php echo esc_html( mb_strtoupper( mb_substr( trim( preg_replace( '/[^\p{L}\s]/u', '', $name ) ) ?: 'S', 0, 1 ) ) ); ?></div>
          <?php endif; ?>
          <h4><?php echo esc_html( $name ); ?></h4>
          <div class="role"><?php echo esc_html( get_post_meta( $l->ID, 'chuc_vu', true ) ); ?></div>
          <p><?php echo esc_html( wp_strip_all_tags( $l->post_content ) ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( s24_show( 'contact' ) ) : ?>
<section class="contact" id="lien-he">
  <div class="wrap">
    <div>
      <span class="kicker"><?php echo esc_html( $m( 'contact_kicker' ) ); ?></span>
      <h2 class="sec-title"><?php echo esc_html( $m( 'contact_title' ) ); ?></h2>
      <dl class="info">
        <?php
        $rows = array(
          'Công ty'     => esc_html( $m( 'company' ) ),
          'Thương hiệu' => esc_html( $m( 'brand' ) ),
          'Email'       => $m( 'email' ) ? '<a href="mailto:' . esc_attr( $m( 'email' ) ) . '">' . esc_html( $m( 'email' ) ) . '</a>' : '',
          'Website'     => $m( 'website' ) ? '<a href="' . esc_url( 'https://' . preg_replace( '#^https?://#', '', $m( 'website' ) ) ) . '">' . esc_html( $m( 'website' ) ) . '</a>' : '',
          'Địa chỉ'     => nl2br( esc_html( $m( 'address' ) ) ),
          'Hotline'     => esc_html( $m( 'hotline' ) ),
        );
        foreach ( $rows as $label => $val ) {
          if ( '' === trim( wp_strip_all_tags( $val ) ) ) { continue; }
          echo '<dt>' . esc_html( $label ) . '</dt><dd>' . $val . '</dd>'; // phpcs:ignore
        }
        ?>
      </dl>
      <?php if ( $m( 'bank_account' ) ) : ?>
        <div class="bank">
          <span class="lbl">Tài khoản ngân hàng</span>
          <div class="num"><span id="bankNum"><?php echo esc_html( $m( 'bank_account' ) ); ?></span><button class="copy-btn" id="copyBank" type="button">Sao chép</button></div>
          <div><?php echo esc_html( implode( ' · ', array_filter( array( $m( 'bank_name' ) ? 'Ngân hàng ' . $m( 'bank_name' ) : '', $m( 'bank_holder' ) ? 'Chủ tài khoản: ' . $m( 'bank_holder' ) : '' ) ) ) ); ?></div>
        </div>
      <?php endif; ?>
    </div>
    <form class="contact-form" id="contactForm" novalidate>
      <h3><?php echo esc_html( $m( 'form_title' ) ); ?></h3>
      <input id="cName" name="name" required placeholder="Họ và tên" autocomplete="name">
      <input id="cPhone" name="phone" required placeholder="Số điện thoại" inputmode="tel" autocomplete="tel">
      <input id="cEmail" name="email" type="email" placeholder="Email" autocomplete="email">
      <textarea id="cMsg" name="message" placeholder="Bạn cần lắp trạm sạc ở đâu, cho loại xe nào?"></textarea>
      <input type="text" name="website_url" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <button class="btn" type="submit" id="cSubmit">Gửi yêu cầu</button>
      <div class="form-msg" id="formMsg" role="status" hidden></div>
    </form>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
