/* =========================================================
   script.js - Interaksi UI Sunshine Kids Protection
   ========================================================= */

// Toggle navbar mobile (user)
function toggleNav() {
  document.getElementById('navMenu')?.classList.toggle('active');
  document.getElementById('navActions')?.classList.toggle('active');
}

// Toggle sidebar admin (mobile)
function toggleSidebar() {
  document.getElementById('sidebar')?.classList.toggle('active');
}

// Modal helper
function openModal(id) {
  document.getElementById(id)?.classList.add('active');
}
function closeModal(id) {
  document.getElementById(id)?.classList.remove('active');
}

// Tab switcher (perhitungan SPK)
function showTab(tabId, btn) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
  document.getElementById(tabId)?.classList.add('active');
  btn?.classList.add('active');
}

// Bobot kriteria - hitung total realtime
function updateWeightTotal() {
  const inputs = document.querySelectorAll('.weight-input');
  let total = 0;
  inputs.forEach(inp => {
    total += parseFloat(inp.value || 0);
    const valueLabel = inp.closest('.weight-row')?.querySelector('.weight-value');
    if (valueLabel) valueLabel.textContent = parseFloat(inp.value).toFixed(3);
  });
  const totalEl = document.getElementById('weightTotal');
  if (totalEl) {
    totalEl.textContent = 'Total Bobot: ' + total.toFixed(3) +
      (Math.abs(total - 1) < 0.001 ? ' ✅ (Valid, total = 1.000)' : ' ⚠️ (Total harus = 1.000)');
    totalEl.className = 'weight-total ' + (Math.abs(total - 1) < 0.001 ? 'ok' : 'bad');
  }
}

// Preview foto upload
function previewFoto(input, previewId) {
  const preview = document.getElementById(previewId);
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
  }
}

// Konfirmasi hapus
function confirmDelete(formId) {
  if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
    document.getElementById(formId).submit();
  }
}

// Reason box accordion (hasil rekomendasi)
function toggleReason(id) {
  const box = document.getElementById(id);
  if (box) box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', () => {
  updateWeightTotal();
});
