let keranjang = JSON.parse(localStorage.getItem('keranjang')) || [];

function updateUI() {
    const badge = document.getElementById('cart-badge');
    const list = document.getElementById('cartItemsList');
    const footer = document.getElementById('cartFooter');
    const totalDisplay = document.getElementById('totalPrice');

    if (keranjang.length > 0) {
        if(badge) { badge.style.display = 'flex'; badge.innerText = keranjang.length; }
        if(footer) footer.style.display = 'block';

        let html = '';
        let total = 0;
        keranjang.forEach((item, index) => {
            total += item.price;
            html += `
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        <p>Rp ${item.price.toLocaleString('id-ID')}</p>
                    </div>
                    <i class="fas fa-trash" onclick="hapusItem(${index})"></i>
                </div>`;
        });
        list.innerHTML = html;
        totalDisplay.innerText = 'Rp ' + total.toLocaleString('id-ID');
    } else {
        if(badge) badge.style.display = 'none';
        if(footer) footer.style.display = 'none';
        list.innerHTML = '<div style="text-align:center; padding:30px;"><p style="color:#888;">Keranjang kosong.</p></div>';
    }
}

// Fungsi GAS untuk cek metode bayar & munculkan QR
function cekMetode() {
    const metode = document.getElementById('metode_bayar').value;
    const qrDiv = document.getElementById('qr-preview');
    if(qrDiv) {
        qrDiv.style.display = (metode === "QRIS / Transfer") ? "block" : "none";
    }
}

function tambahItem(btn) {
    const card = btn.closest('.product-card');
    const item = {
        name: card.querySelector('.item-name').innerText,
        price: parseInt(card.querySelector('.item-price').innerText.replace(/\./g, '')),
        image: card.querySelector('.item-img').src
    };
    keranjang.push(item);
    localStorage.setItem('keranjang', JSON.stringify(keranjang));
    
    showToast("✅ " + item.name + " Ditambah");
    animateBadge();
    updateUI();
}

function hapusItem(index) {
    keranjang.splice(index, 1);
    localStorage.setItem('keranjang', JSON.stringify(keranjang));
    updateUI();
}

function showToast(msg) {
    const toast = document.getElementById('toast-notif');
    if(toast) {
        toast.innerText = msg;
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 2500);
    }
}

function animateBadge() {
    const btn = document.getElementById('openCart');
    if(btn) {
        btn.classList.add('cart-bounce');
        setTimeout(() => { btn.classList.remove('cart-bounce'); }, 500);
    }
}

function checkoutWA() {
    if (keranjang.length === 0) return;

    // 1. Ambil data dari form
    const namaUser = document.getElementById('nama_pelanggan').value;
    const hpUser = document.getElementById('hp_pelanggan').value;
    const alamatUser = document.getElementById('alamat_pelanggan').value;
    const metodeBayar = document.getElementById('metode_bayar').value;

    // 2. Validasi Lengkap
    if (!namaUser || !hpUser || !alamatUser || !metodeBayar) {
        alert("Ups! Mohon isi Nama, No. HP, Alamat, dan Metode Bayar dulu ya kak.");
        return;
    }

    // 3. Susun Tanggal
    const opsiTanggal = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const tanggal = new Date().toLocaleDateString('id-ID', opsiTanggal);
    
    // 4. Susun Pesan
    let pesan = "🥦 *PESANAN BARU - SAYURPRAYA* 🥦\n";
    pesan += "------------------------------------------\n";
    pesan += `📅 *Hari/Tgl:* ${tanggal}\n`;
    pesan += "------------------------------------------\n\n";
    
    pesan += "👤 *DATA PENERIMA:*\n";
    pesan += `• *Nama:* ${namaUser}\n`;
    pesan += `• *No. HP:* ${hpUser}\n`;
    pesan += `• *Alamat:* ${alamatUser}\n\n`;

    pesan += "📋 *DAFTAR BELANJA:*\n";
    let total = 0;
    keranjang.forEach((item) => {
        pesan += `• ${item.name} → Rp ${item.price.toLocaleString('id-ID')}\n`;
        total += item.price;
    });
    
    pesan += "\n------------------------------------------\n";
    pesan += `💰 *TOTAL TAGIHAN: Rp ${total.toLocaleString('id-ID')}*\n`;
    pesan += `💳 *METODE BAYAR:* ${metodeBayar}\n`;
    pesan += "------------------------------------------\n\n";
    
    if(metodeBayar === "QRIS / Transfer") {
        pesan += "Sesuai pilihan, saya sudah screenshot QR DANA-nya kak. Mohon dicek ya! ✅\n\n";
    }
    pesan += "Mohon segera dikonfirmasi dan diproses ya kak. Terima kasih! 🙏✨";
    
    // 5. Kirim ke WhatsApp
    const nomorWA = "628895896973"; 
    const url = "https://api.whatsapp.com/send?phone=" + nomorWA + "&text=" + encodeURIComponent(pesan);
    
    window.open(url, '_blank');
}

// Modal Toggle & Inisialisasi
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('cartModal');
    const openBtn = document.getElementById('openCart');
    const closeBtn = document.getElementById('closeCart');

    if(openBtn) openBtn.onclick = () => modal.style.display = "block";
    if(closeBtn) closeBtn.onclick = () => modal.style.display = "none";
    
    window.onclick = (e) => { 
        if(e.target == modal) modal.style.display = "none"; 
    };
    
    updateUI();
});