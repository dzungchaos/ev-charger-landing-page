#!/usr/bin/env bash
# ==========================================================
#  Cài WordPress + theme Sacnhanh24h lên server Ubuntu/Debian
#  Chạy:  bash /root/sacnhanh24h-wordpress/install.sh
#  Chạy lại lần sau = chỉ cập nhật theme (không mất nội dung).
# ==========================================================
set -euo pipefail

DOMAIN="sacnhanh24h.vn"
WEBROOT="/var/www/sacnhanh24h-wp"
NGINX_CONF="/etc/nginx/sites-available/sacnhanh24h"
DB_NAME="sacnhanh24h"
DB_USER="sacnhanh24h"
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
THEME_SRC="$DIR/sacnhanh24h"

say()  { printf '\n\033[1;32m>> %s\033[0m\n' "$*"; }
warn() { printf '\n\033[1;33m!! %s\033[0m\n' "$*"; }
die()  { printf '\n\033[1;31mLỖI: %s\033[0m\n' "$*"; exit 1; }

[ "$(id -u)" -eq 0 ] || die "Hãy đăng nhập bằng tài khoản root rồi chạy lại."
command -v apt-get >/dev/null || die "Script này dành cho Ubuntu/Debian. Server của bạn dùng hệ điều hành khác."
[ -f "$THEME_SRC/style.css" ] || die "Không thấy thư mục theme 'sacnhanh24h' cạnh file install.sh."

# ---------- 1. Cài phần mềm ----------
say "Bước 1/6: Cài PHP, MariaDB (cơ sở dữ liệu), Nginx… (2–5 phút)"
export DEBIAN_FRONTEND=noninteractive
apt-get update -y -q
apt-get install -y -q nginx mariadb-server php-fpm php-mysql php-xml php-mbstring php-curl php-gd php-zip php-intl \
  unzip curl openssl certbot python3-certbot-nginx
apt-get install -y -q php-imagick >/dev/null 2>&1 || true

PHPV="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
SOCK="/run/php/php${PHPV}-fpm.sock"
systemctl enable --now "php${PHPV}-fpm" mariadb nginx >/dev/null 2>&1 || true

# Cho phép tải ảnh lớn tới 64MB
INI="/etc/php/${PHPV}/fpm/php.ini"
if [ -f "$INI" ]; then
  sed -i -E 's/^;?upload_max_filesize *=.*/upload_max_filesize = 64M/; s/^;?post_max_size *=.*/post_max_size = 64M/; s/^;?memory_limit *=.*/memory_limit = 256M/; s/^;?max_execution_time *=.*/max_execution_time = 120/' "$INI"
  systemctl restart "php${PHPV}-fpm"
fi

# ---------- 2. WP-CLI ----------
say "Bước 2/6: Cài công cụ quản lý WordPress (WP-CLI)"
if ! command -v wp >/dev/null; then
  curl -fsSL -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  chmod +x /usr/local/bin/wp
fi
WP="wp --allow-root --path=$WEBROOT"

# ---------- Chế độ cập nhật theme ----------
if [ -f "$WEBROOT/wp-config.php" ] && $WP core is-installed >/dev/null 2>&1; then
  say "WordPress đã có sẵn → chỉ cập nhật theme Sacnhanh24h (nội dung giữ nguyên)"
  rm -rf "$WEBROOT/wp-content/themes/sacnhanh24h"
  cp -r "$THEME_SRC" "$WEBROOT/wp-content/themes/sacnhanh24h"
  chown -R www-data:www-data "$WEBROOT/wp-content/themes/sacnhanh24h"
  # Tạo các trang chính sách còn thiếu; trang đã có không bị ghi đè
  $WP eval 's24_seed_policy_pages();'
  $WP cache flush >/dev/null 2>&1 || true
  say "XONG. Mở https://$DOMAIN rồi bấm Ctrl + F5 để xem bản mới."
  exit 0
fi

# ---------- 3. Thông tin tài khoản quản trị ----------
say "Bước 3/6: Tạo tài khoản quản trị website"
while true; do
  read -rp "Tên đăng nhập quản trị (không dấu, không cách, ví dụ quantri24h): " ADMIN_USER
  ADMIN_USER="${ADMIN_USER:-quantri24h}"
  [[ "$ADMIN_USER" =~ ^[a-zA-Z0-9_.-]{4,30}$ ]] && break
  echo "Tên chỉ gồm chữ không dấu, số, dấu _ . - và dài 4–30 ký tự."
done
while true; do
  read -rsp "Mật khẩu quản trị (ít nhất 8 ký tự, màn hình sẽ không hiện chữ): " ADMIN_PASS; echo
  read -rsp "Nhập lại mật khẩu: " ADMIN_PASS2; echo
  if [ "$ADMIN_PASS" != "$ADMIN_PASS2" ]; then echo "Hai lần nhập không khớp, nhập lại."; continue; fi
  if [ "${#ADMIN_PASS}" -lt 8 ]; then echo "Mật khẩu quá ngắn, nhập lại."; continue; fi
  break
done
read -rp "Email quản trị [sacnhanh24h@gmail.com]: " ADMIN_EMAIL
ADMIN_EMAIL="${ADMIN_EMAIL:-sacnhanh24h@gmail.com}"

# ---------- 4. Cơ sở dữ liệu + WordPress ----------
say "Bước 4/6: Tạo cơ sở dữ liệu và cài WordPress tiếng Việt"
DB_PASS="$(openssl rand -hex 16)"
mysql -uroot <<SQL
DROP DATABASE IF EXISTS \`${DB_NAME}\`;
CREATE DATABASE \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL
umask 077
printf 'Database: %s\nUser: %s\nPassword: %s\n' "$DB_NAME" "$DB_USER" "$DB_PASS" > /root/sacnhanh24h-database.txt
umask 022

rm -rf "$WEBROOT"; mkdir -p "$WEBROOT"
$WP core download --locale=vi --quiet
$WP config create --dbname="$DB_NAME" --dbuser="$DB_USER" --dbpass="$DB_PASS" --dbhost=localhost --locale=vi --quiet --extra-php <<'PHP'
define( 'FS_METHOD', 'direct' );
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'DISALLOW_FILE_EDIT', true );
PHP
$WP core install --url="http://$DOMAIN" --title="Sacnhanh24h" --admin_user="$ADMIN_USER" \
  --admin_password="$ADMIN_PASS" --admin_email="$ADMIN_EMAIL" --skip-email --quiet
$WP option update blogdescription "Trạm sạc nhanh xe điện 24/7" --quiet
$WP option update timezone_string "Asia/Ho_Chi_Minh" --quiet
$WP option update date_format "d/m/Y" --quiet
$WP option update time_format "H:i" --quiet
$WP option update default_comment_status closed --quiet
$WP rewrite structure '/%postname%/' --quiet
$WP post delete 1 2 --force --quiet >/dev/null 2>&1 || true
$WP plugin delete akismet hello --quiet >/dev/null 2>&1 || true

say "Bước 5/6: Cài theme Sacnhanh24h và nội dung mẫu"
cp -r "$THEME_SRC" "$WEBROOT/wp-content/themes/sacnhanh24h"
$WP theme activate sacnhanh24h --quiet
$WP eval 's24_seed_content();'
$WP eval 's24_seed_policy_pages();'
chown -R www-data:www-data "$WEBROOT"
find "$WEBROOT" -type d -exec chmod 755 {} \;
find "$WEBROOT" -type f -exec chmod 644 {} \;
chmod 640 "$WEBROOT/wp-config.php"

# ---------- 6. Nginx + HTTPS ----------
say "Bước 6/6: Cấu hình web server và HTTPS"
[ -f "$NGINX_CONF" ] && cp "$NGINX_CONF" "/root/nginx-sacnhanh24h-ban-cu.conf"
cat > "$NGINX_CONF" <<'NGINX'
server {
    listen 80;
    server_name __DOMAIN__ www.__DOMAIN__;
    root __ROOT__;
    index index.php index.html;
    client_max_body_size 64M;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:__SOCK__;
    }
    location = /xmlrpc.php { deny all; }
    location ~ /\.(ht|git|env) { deny all; }
    location ~* \.(jpg|jpeg|png|gif|webp|svg|ico|css|js|woff2?)$ {
        expires 30d;
        access_log off;
        try_files $uri =404;
    }
}
NGINX
sed -i "s#__DOMAIN__#${DOMAIN}#g; s#__ROOT__#${WEBROOT}#g; s#__SOCK__#${SOCK}#g" "$NGINX_CONF"
ln -sf "$NGINX_CONF" /etc/nginx/sites-enabled/sacnhanh24h
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl reload nginx
if command -v ufw >/dev/null; then ufw allow 80 >/dev/null 2>&1 || true; ufw allow 443 >/dev/null 2>&1 || true; fi

SCHEME="http"
if [ -d "/etc/letsencrypt/live/$DOMAIN" ]; then
  DOMS="$(certbot certificates --cert-name "$DOMAIN" 2>/dev/null | awk -F': ' '/Domains:/{print $2; exit}')"
  DARGS=""; for d in ${DOMS:-$DOMAIN}; do DARGS="$DARGS -d $d"; done
  # shellcheck disable=SC2086
  if certbot --nginx --cert-name "$DOMAIN" $DARGS --reinstall --redirect --non-interactive --agree-tos -m "$ADMIN_EMAIL"; then
    SCHEME="https"
  fi
else
  if certbot --nginx -d "$DOMAIN" -d "www.$DOMAIN" --redirect --non-interactive --agree-tos -m "$ADMIN_EMAIL" \
     || certbot --nginx -d "$DOMAIN" --redirect --non-interactive --agree-tos -m "$ADMIN_EMAIL"; then
    SCHEME="https"
  fi
fi
if [ "$SCHEME" = "https" ]; then
  $WP option update home "https://$DOMAIN" --quiet
  $WP option update siteurl "https://$DOMAIN" --quiet
else
  warn "Chưa bật được HTTPS. Website vẫn chạy ở http://$DOMAIN. Khi tên miền đã trỏ đúng, chạy: certbot --nginx -d $DOMAIN -d www.$DOMAIN"
fi
systemctl reload nginx

cat <<DONE

==========================================================
  CÀI ĐẶT XONG!
  Website:        ${SCHEME}://${DOMAIN}
  Trang quản trị: ${SCHEME}://${DOMAIN}/wp-admin
  Tên đăng nhập:  ${ADMIN_USER}
  Mật khẩu:       (mật khẩu bạn vừa nhập)

  Website tĩnh cũ vẫn được giữ ở /var/www/sacnhanh24h
  Thông tin cơ sở dữ liệu lưu tại /root/sacnhanh24h-database.txt
==========================================================
DONE
