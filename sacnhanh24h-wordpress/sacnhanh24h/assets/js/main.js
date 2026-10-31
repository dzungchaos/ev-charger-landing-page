/* Sacnhanh24h WordPress theme — main.js */
(function () {
  var $ = function (id) { return document.getElementById(id); };

  /* Menu điện thoại (mọi trang) */
  var menuBtn = $("menuBtn"), nav = $("nav");
  if (menuBtn && nav) {
    menuBtn.addEventListener("click", function () {
      var open = nav.classList.toggle("open");
      menuBtn.setAttribute("aria-expanded", open ? "true" : "false");
    });
    nav.querySelectorAll("li:not(.menu-item-has-children) > a").forEach(function (a) {
      a.addEventListener("click", function () { nav.classList.remove("open"); });
    });
    /* Mục có menu con (vd. "Chính sách"): bấm để mở/đóng; rời chuột, bấm ra ngoài hoặc Esc thì đóng */
    nav.querySelectorAll(".menu-item-has-children > a").forEach(function (a) {
      var li = a.parentNode;
      var set = function (open) { li.classList.toggle("open", open); a.setAttribute("aria-expanded", open ? "true" : "false"); };
      a.setAttribute("aria-haspopup", "true");
      set(false);
      a.addEventListener("click", function (e) { e.preventDefault(); set(!li.classList.contains("open")); });
      li.addEventListener("mouseleave", function () { if (!menuBtn.offsetParent) set(false); });
      li.addEventListener("focusout", function (e) { if (!li.contains(e.relatedTarget)) set(false); });
      li.addEventListener("keydown", function (e) { if (e.key === "Escape") { set(false); a.focus(); } });
      document.addEventListener("click", function (e) { if (!li.contains(e.target)) set(false); });
    });
  }

  var cfg = window.S24;
  if (!cfg) return; // các trang không phải trang chủ

  /* ---------- Bản đồ trạm sạc ---------- */
  if ($("map") && window.L) {
    var STATIONS = cfg.stations || [];
    var esc = function (t) { return String(t).replace(/[&<>"']/g, function (c) { return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]; }); };
    var pinIcon = L.divIcon({
      className: "pin", iconSize: [46, 58], iconAnchor: [23, 56], popupAnchor: [0, -50],
      html: '<svg width="46" height="58" viewBox="0 0 46 58" style="filter:drop-shadow(0 3px 4px rgba(0,0,0,.25))"><path d="M23 56S4 35 4 21a19 19 0 0138 0c0 14-19 35-19 35z" fill="#fff" stroke="#2f9a2c" stroke-width="3"/><image href="' + esc(cfg.pin) + '" x="10" y="8" width="26" height="28"/></svg>'
    });
    var map = L.map("map", { scrollWheelZoom: false }).setView([16, 106.5], 6);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { maxZoom: 19, attribution: "&copy; OpenStreetMap" }).addTo(map);
    map.on("click", function () { map.scrollWheelZoom.enable(); });

    var dirUrl = function (s) { return "https://www.google.com/maps/dir/?api=1&destination=" + s.lat + "," + s.lng; };
    var markers = {};
    STATIONS.forEach(function (s) {
      markers[s.id] = L.marker([s.lat, s.lng], { icon: pinIcon }).bindPopup(
        '<div class="pop"><h4>' + esc(s.name) + "</h4><p>" + esc(s.address) + "</p><p>★ " + s.rating.toFixed(1) + " · " + s.free + "/" + s.total +
        ' cổng sạc khả dụng</p><a href="' + dirUrl(s) + '" target="_blank" rel="noopener">Chỉ đường bằng Google Maps →</a></div>');
      markers[s.id].on("click", function () { highlight(s.id, false); });
    });

    var byVi = function (a, b) { return a.localeCompare(b, "vi"); };
    var uniq = function (arr) { return arr.filter(function (v, i) { return v && arr.indexOf(v) === i; }); };
    uniq(STATIONS.map(function (s) { return s.province; })).sort(byVi).forEach(function (p) { $("province").add(new Option(p, p)); });
    $("province").addEventListener("change", function () {
      var p = $("province").value, d = $("district");
      d.length = 1; d.disabled = !p;
      if (p) uniq(STATIONS.filter(function (s) { return s.province === p; }).map(function (s) { return s.district; })).sort(byVi).forEach(function (x) { d.add(new Option(x, x)); });
      render();
    });
    $("district").addEventListener("change", render);
    $("q").addEventListener("input", render);
    $("searchForm").addEventListener("submit", function (e) { e.preventDefault(); render(); });

    var norm = function (t) { return t.normalize("NFD").replace(/[̀-ͯ]/g, "").replace(/đ/gi, "d").toLowerCase(); };
    var ICON_PIN = '<svg width="20" height="20" viewBox="0 0 24 24" fill="#2f9a2c"><path d="M12 2a7 7 0 00-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 00-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>';
    var ICON_STAR = '<svg width="20" height="20" viewBox="0 0 24 24" fill="#f2a516"><path d="M12 2l3 6.9 7.4.6-5.6 4.9 1.7 7.3L12 17.8 5.5 21.7l1.7-7.3L1.6 9.5 9 8.9z"/></svg>';
    var ICON_PLUG = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2f9a2c" stroke-width="2"><rect x="3" y="3" width="11" height="18" rx="1.5"/><path d="M9 8l-2.5 4h3L8 16"/><path d="M14 9h2.5a1.5 1.5 0 011.5 1.5V16a1.5 1.5 0 003 0V7l-2-2"/></svg>';

    function render() {
      var q = norm($("q").value.trim()), p = $("province").value, d = $("district").value;
      var res = STATIONS.filter(function (s) {
        return (!p || s.province === p) && (!d || s.district === d) && (!q || norm(s.name + " " + s.address).indexOf(q) > -1);
      });
      $("count").textContent = res.length;
      STATIONS.forEach(function (s) { res.indexOf(s) > -1 ? markers[s.id].addTo(map) : markers[s.id].remove(); });
      $("list").innerHTML = res.length ? res.map(function (s) {
        return '<button class="st-card" data-id="' + s.id + '" type="button"><h3>' + esc(s.name) + '</h3>' +
          '<div class="st-row">' + ICON_PIN + "<span>" + esc(s.address) + "</span></div>" +
          '<div class="st-meta"><span class="rate">' + ICON_STAR + s.rating.toFixed(1) + "</span><span>|</span>" +
          '<span class="ports ' + (s.free ? "" : "full") + '">' + ICON_PLUG + s.free + "/" + s.total + " Cổng sạc khả dụng</span></div>" +
          '<div class="st-actions"><a href="' + dirUrl(s) + '" target="_blank" rel="noopener" data-stop>Chỉ đường →</a></div></button>';
      }).join("") : '<div class="empty">Không tìm thấy trạm sạc phù hợp. Thử từ khóa hoặc khu vực khác.</div>';
      if (res.length) map.fitBounds(L.latLngBounds(res.map(function (s) { return [s.lat, s.lng]; })), { padding: [50, 50], maxZoom: 14 });
    }
    $("list").addEventListener("click", function (e) {
      if (e.target.closest("[data-stop]")) return;
      var c = e.target.closest(".st-card"); if (c) highlight(+c.dataset.id, true);
    });
    function highlight(id, fly) {
      document.querySelectorAll(".st-card").forEach(function (c) { c.classList.toggle("active", +c.dataset.id === id); });
      var s = STATIONS[id];
      if (fly) { map.flyTo([s.lat, s.lng], 15, { duration: 0.8 }); setTimeout(function () { markers[id].openPopup(); }, 850); }
      else { var c = document.querySelector('.st-card[data-id="' + id + '"]'); if (c) c.scrollIntoView({ block: "nearest", behavior: "smooth" }); }
    }
    render();
  }

  /* ---------- Tab dự án ---------- */
  document.querySelectorAll(".tab").forEach(function (t) {
    t.addEventListener("click", function () {
      document.querySelectorAll(".tab").forEach(function (x) { x.setAttribute("aria-selected", x === t ? "true" : "false"); });
      document.querySelectorAll("[data-panel]").forEach(function (p) { p.hidden = p.dataset.panel !== t.dataset.tab; });
    });
  });

  /* ---------- Sao chép số tài khoản ---------- */
  var copyBtn = $("copyBank");
  if (copyBtn) copyBtn.addEventListener("click", function () {
    var done = function (txt) { copyBtn.textContent = txt; setTimeout(function () { copyBtn.textContent = "Sao chép"; }, 1800); };
    var fallback = function () {
      var r = document.createRange(); r.selectNodeContents($("bankNum"));
      var s = getSelection(); s.removeAllRanges(); s.addRange(r); done("Đã chọn số");
    };
    if (navigator.clipboard) navigator.clipboard.writeText($("bankNum").textContent.trim()).then(function () { done("Đã sao chép"); }, fallback);
    else fallback();
  });

  /* ---------- Form tư vấn ---------- */
  var form = $("contactForm");
  if (form) form.addEventListener("submit", function (e) {
    e.preventDefault();
    var msg = $("formMsg"), btn = $("cSubmit");
    var show = function (text, err) { msg.textContent = text; msg.className = "form-msg" + (err ? " err" : ""); msg.hidden = false; };
    if (!$("cName").value.trim() || !$("cPhone").value.trim()) { show("Vui lòng nhập họ tên và số điện thoại.", true); return; }
    var data = new FormData(form);
    data.append("action", "s24_contact");
    data.append("nonce", cfg.nonce);
    btn.disabled = true; btn.textContent = "Đang gửi…";
    fetch(cfg.ajax, { method: "POST", body: data, credentials: "same-origin" })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res && res.success) { show(res.data.msg, false); form.reset(); }
        else { show((res && res.data && res.data.msg) || "Chưa gửi được. Vui lòng thử lại.", true); }
      })
      .catch(function () { show("Mất kết nối. Vui lòng thử lại.", true); })
      .then(function () { btn.disabled = false; btn.textContent = "Gửi yêu cầu"; });
  });
})();
