function openEditModal(id, name, price, category) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_category').value = category;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Tutup jika klik area di luar kotak putih
window.onclick = function(event) {
    if (event.target == document.getElementById('editModal')) {
        closeEditModal();
    }
}