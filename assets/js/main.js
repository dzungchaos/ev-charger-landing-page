/* Sacnhanh24h — main.js */
(function(){
const $=id=>document.getElementById(id);
const STATIONS=window.STATIONS||[];

/* ---------- Bản đồ trạm sạc ----------
   Nền bản đồ: OpenStreetMap (miễn phí, không cần API key).
   Muốn đổi sang nền Google Maps: dùng Google Maps JavaScript API với API key của bạn. */
const pinIcon=L.divIcon({className:"pin",iconSize:[46,58],iconAnchor:[23,56],popupAnchor:[0,-50],
  html:`<svg width="46" height="58" viewBox="0 0 46 58" style="filter:drop-shadow(0 3px 4px rgba(0,0,0,.25))"><path d="M23 56S4 35 4 21a19 19 0 0138 0c0 14-19 35-19 35z" fill="#fff" stroke="#2f9a2c" stroke-width="3"/><image href="assets/img/logo.png" x="10" y="8" width="26" height="28"/></svg>`});

const map=L.map("map",{scrollWheelZoom:false}).setView([16,106.5],6);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",{maxZoom:19,attribution:"&copy; OpenStreetMap"}).addTo(map);
map.on("click",()=>map.scrollWheelZoom.enable());

const dirUrl=s=>`https://www.google.com/maps/dir/?api=1&destination=${s.lat},${s.lng}`;
const markers={};
STATIONS.forEach(s=>{
  markers[s.id]=L.marker([s.lat,s.lng],{icon:pinIcon}).bindPopup(
    `<div class="pop"><h4>${s.name}</h4><p>${s.address}</p><p>★ ${s.rating.toFixed(1)} · ${s.free}/${s.total} cổng sạc khả dụng</p><a href="${dirUrl(s)}" target="_blank" rel="noopener">Chỉ đường bằng Google Maps →</a></div>`);
  markers[s.id].on("click",()=>highlight(s.id,false));
});

/* Bộ lọc */
const byVi=(a,b)=>a.localeCompare(b,"vi");
[...new Set(STATIONS.map(s=>s.province))].sort(byVi).forEach(p=>$("province").add(new Option(p,p)));
$("province").addEventListener("change",()=>{
  const p=$("province").value,d=$("district");
  d.length=1;d.disabled=!p;
  if(p)[...new Set(STATIONS.filter(s=>s.province===p).map(s=>s.district))].sort(byVi).forEach(x=>d.add(new Option(x,x)));
  render();
});
$("district").addEventListener("change",render);
$("q").addEventListener("input",render);
$("searchForm").addEventListener("submit",e=>{e.preventDefault();render()});

const norm=t=>t.normalize("NFD").replace(/[̀-ͯ]/g,"").replace(/đ/gi,"d").toLowerCase();

const ICON_PIN='<svg width="20" height="20" viewBox="0 0 24 24" fill="#2f9a2c"><path d="M12 2a7 7 0 00-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 00-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>';
const ICON_STAR='<svg width="20" height="20" viewBox="0 0 24 24" fill="#f2a516"><path d="M12 2l3 6.9 7.4.6-5.6 4.9 1.7 7.3L12 17.8 5.5 21.7l1.7-7.3L1.6 9.5 9 8.9z"/></svg>';
const ICON_PLUG='<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2f9a2c" stroke-width="2"><rect x="3" y="3" width="11" height="18" rx="1.5"/><path d="M9 8l-2.5 4h3L8 16"/><path d="M14 9h2.5a1.5 1.5 0 011.5 1.5V16a1.5 1.5 0 003 0V7l-2-2"/></svg>';

function render(){
  const q=norm($("q").value.trim()),p=$("province").value,d=$("district").value;
  const res=STATIONS.filter(s=>(!p||s.province===p)&&(!d||s.district===d)&&(!q||norm(s.name+" "+s.address).includes(q)));
  $("count").textContent=res.length;
  STATIONS.forEach(s=>res.includes(s)?markers[s.id].addTo(map):markers[s.id].remove());
  $("list").innerHTML=res.length?res.map(s=>`
    <button class="st-card" data-id="${s.id}" type="button">
      <h3>${s.name}</h3>
      <div class="st-row">${ICON_PIN}<span>${s.address}</span></div>
      <div class="st-meta">
        <span class="rate">${ICON_STAR}${s.rating.toFixed(1)}</span><span>|</span>
        <span class="ports ${s.free?"":"full"}">${ICON_PLUG}${s.free}/${s.total} Cổng sạc khả dụng</span>
      </div>
      <div class="st-actions"><a href="${dirUrl(s)}" target="_blank" rel="noopener" data-stop>Chỉ đường →</a></div>
    </button>`).join("")
    :`<div class="empty">Không tìm thấy trạm sạc phù hợp. Thử từ khóa hoặc khu vực khác.</div>`;
  if(res.length)map.fitBounds(L.latLngBounds(res.map(s=>[s.lat,s.lng])),{padding:[50,50],maxZoom:14});
}
$("list").addEventListener("click",e=>{
  if(e.target.closest("[data-stop]"))return;
  const c=e.target.closest(".st-card");if(c)highlight(+c.dataset.id,true);
});
function highlight(id,fly){
  document.querySelectorAll(".st-card").forEach(c=>c.classList.toggle("active",+c.dataset.id===id));
  const s=STATIONS[id];
  if(fly){map.flyTo([s.lat,s.lng],15,{duration:.8});setTimeout(()=>markers[id].openPopup(),850);}
  else{const c=document.querySelector(`.st-card[data-id="${id}"]`);c&&c.scrollIntoView({block:"nearest",behavior:"smooth"});}
}
render();

/* ---------- Dự án ---------- */
const PROJECTS={
  tm:[["Hà Nội","Trung tâm thương mại","Trạm sạc nhanh DC 120kW phục vụ khách mua sắm và nhân viên tòa nhà."],
      ["Bình Dương","Khu đô thị xanh","Hai trạm sạc DC công suất cao tại dự án phức hợp đa công năng."],
      ["Hà Nội","Khách sạn trung tâm","Hai cổng sạc DC 60kW ngay khu phố cổ, thuận tiện cho khách lưu trú."],
      ["Đồng Nai","Nhà máy xanh","Hệ thống sạc cho đội xe điện và nhân viên, góp phần đạt chứng nhận công trình xanh."],
      ["Bà Rịa - Vũng Tàu","Khu công nghiệp","Bộ sạc hai cổng lắp tại bãi đỗ xe, vận hành 24/7."],
      ["Hà Nội","Showroom ô tô","Trạm sạc phục vụ khách hàng lái thử và nhận xe điện."]],
  ch:[["TP. Hồ Chí Minh","Khu biệt thự ven sông","Bộ sạc AC tại nhà, khảo sát, lắp đặt và bảo trì trọn gói."],
      ["TP. Hồ Chí Minh","Căn hộ cao cấp","Trạm sạc tầng hầm cho cư dân, thanh toán qua ứng dụng."],
      ["Hà Nội","Khu nhà phố","Hệ thống sạc chia sẻ, tính tiền điện theo từng hộ."]]
};
function renderProj(k){
  $("projGrid").innerHTML=PROJECTS[k].map(p=>`<article class="proj"><div class="ph">Ảnh dự án</div><div class="proj-body"><div class="proj-loc">${p[0]}</div><h4>${p[1]}</h4><p>${p[2]}</p></div></article>`).join("");
}
document.querySelectorAll(".tab").forEach(t=>t.addEventListener("click",()=>{
  document.querySelectorAll(".tab").forEach(x=>x.setAttribute("aria-selected",String(x===t)));
  renderProj(t.dataset.tab);
}));
renderProj("tm");

/* ---------- Khác ---------- */
$("menuBtn").addEventListener("click",()=>$("nav").classList.toggle("open"));
document.querySelectorAll("#nav a").forEach(a=>a.addEventListener("click",()=>$("nav").classList.remove("open")));

$("copyBank").addEventListener("click",async e=>{
  const btn=e.currentTarget;
  try{await navigator.clipboard.writeText("69688788");btn.textContent="Đã sao chép";}
  catch(_){const r=document.createRange();r.selectNodeContents($("bankNum"));const s=getSelection();s.removeAllRanges();s.addRange(r);btn.textContent="Đã chọn số";}
  setTimeout(()=>btn.textContent="Sao chép",1800);
});

/* Form liên hệ: mở ứng dụng email gửi tới sacnhanh24h@gmail.com.
   Khi có máy chủ/backend, thay đoạn này bằng lệnh gửi dữ liệu thật. */
$("contactForm").addEventListener("submit",e=>{
  e.preventDefault();
  const body=`Họ tên: ${$("cName").value}\nSố điện thoại: ${$("cPhone").value}\nEmail: ${$("cEmail").value}\n\n${$("cMsg").value}`;
  location.href=`mailto:sacnhanh24h@gmail.com?subject=${encodeURIComponent("Yêu cầu tư vấn trạm sạc - "+$("cName").value)}&body=${encodeURIComponent(body)}`;
  $("ok").hidden=false;
});
$("year").textContent=new Date().getFullYear();
})();
