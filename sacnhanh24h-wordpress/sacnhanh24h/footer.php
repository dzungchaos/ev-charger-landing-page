<footer>
  <div class="wrap">
    <div class="f-grid">
      <div>
        <div class="f-brand">
          <img src="<?php echo esc_url( s24_logo_url() ); ?>" alt="<?php echo esc_attr( s24_mod( 'brand_name' ) ); ?>">
          <span class="brand-name"><?php echo esc_html( s24_mod( 'brand_name' ) ); ?><small><?php echo esc_html( s24_mod( 'brand_tagline' ) ); ?></small></span>
        </div>
        <p style="margin-top:16px">
          <?php echo esc_html( s24_mod( 'company' ) ); ?><br>
          <?php echo esc_html( implode( ' · ', array_filter( array( s24_mod( 'email' ), s24_mod( 'website' ) ) ) ) ); ?>
        </p>
        <?php if ( s24_mod( 'footer_text' ) ) : ?><p><?php echo esc_html( s24_mod( 'footer_text' ) ); ?></p><?php endif; ?>
        <?php if ( s24_mod( 'play_link' ) || s24_mod( 'appstore_link' ) ) : ?>
          <div class="stores">
            <?php if ( s24_mod( 'play_link' ) ) : ?><a class="store" href="<?php echo esc_url( s24_mod( 'play_link' ) ); ?>" target="_blank" rel="noopener">Google Play</a><?php endif; ?>
            <?php if ( s24_mod( 'appstore_link' ) ) : ?><a class="store" href="<?php echo esc_url( s24_mod( 'appstore_link' ) ); ?>" target="_blank" rel="noopener">App Store</a><?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
      <div>
        <h5>Liên kết nhanh</h5>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a></li>
          <li><a href="<?php echo esc_url( s24_anchor( 'tram-sac' ) ); ?>">Trạm sạc</a></li>
          <li><a href="<?php echo esc_url( s24_anchor( 'bang-gia' ) ); ?>">Bảng giá</a></li>
          <li><a href="<?php echo esc_url( s24_anchor( 'du-an' ) ); ?>">Dự án</a></li>
          <li><a href="<?php echo esc_url( s24_anchor( 'lien-he' ) ); ?>">Liên hệ</a></li>
        </ul>
      </div>
      <div>
        <h5>Kết nối</h5>
        <ul>
          <?php if ( s24_mod( 'hotline' ) ) : ?><li>Hotline: <?php echo esc_html( s24_mod( 'hotline' ) ); ?></li><?php endif; ?>
          <?php if ( s24_mod( 'email' ) ) : ?><li><a href="mailto:<?php echo esc_attr( s24_mod( 'email' ) ); ?>"><?php echo esc_html( s24_mod( 'email' ) ); ?></a></li><?php endif; ?>
          <?php if ( s24_mod( 'facebook' ) ) : ?><li><a href="<?php echo esc_url( s24_mod( 'facebook' ) ); ?>" target="_blank" rel="noopener">Facebook</a></li><?php endif; ?>
          <?php if ( s24_mod( 'zalo' ) ) : ?><li><a href="<?php echo esc_url( s24_mod( 'zalo' ) ); ?>" target="_blank" rel="noopener">Zalo</a></li><?php endif; ?>
          <?php if ( s24_mod( 'youtube' ) ) : ?><li><a href="<?php echo esc_url( s24_mod( 'youtube' ) ); ?>" target="_blank" rel="noopener">YouTube</a></li><?php endif; ?>
        </ul>
      </div>
      <div>
        <h5>Thanh toán</h5>
        <ul>
          <?php if ( s24_mod( 'bank_name' ) ) : ?><li><?php echo esc_html( s24_mod( 'bank_name' ) ); ?></li><?php endif; ?>
          <?php if ( s24_mod( 'bank_account' ) ) : ?><li>STK: <?php echo esc_html( s24_mod( 'bank_account' ) ); ?></li><?php endif; ?>
          <?php if ( s24_mod( 'bank_holder' ) ) : ?><li><?php echo esc_html( s24_mod( 'bank_holder' ) ); ?></li><?php endif; ?>
        </ul>
      </div>
    </div>
    <div class="copyright">
      <span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( s24_mod( 'copyright' ) ); ?></span>
      <span><?php echo esc_html( s24_mod( 'website' ) ); ?></span>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
