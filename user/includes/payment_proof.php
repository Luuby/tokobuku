<?php 
$payment_proof_path = __DIR__ . '/../assets/uploads/payment_proofs/' . $order['payment_proof'];
if (!empty($order['payment_proof']) && file_exists($payment_proof_path)): ?>
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h5>Bukti Transaksi</h5>
        <p><span class="label-bold">File:</span> <?php echo htmlspecialchars($order['payment_proof']); ?></p>
        <img src="../assets/uploads/payment_proofs/<?php echo htmlspecialchars($order['payment_proof']); ?>" 
             alt="Bukti Pembayaran" 
             class="payment-proof-img" 
             onerror="this.src='../assets/no-image.png'; this.alt='Gambar tidak ditemukan';" />
        <p class="text-muted small mt-2">Jika gambar tidak muncul, periksa file di folder assets/uploads/payment_proofs.</p>
    </div>
<?php elseif (!empty($order['payment_proof'])): ?>
    <div class="alert alert-warning">
        <span class="label-bold">Bukti Pembayaran:</span> File <?php echo htmlspecialchars($order['payment_proof']); ?> terdaftar, tapi tidak ditemukan di server.
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <span class="label-bold">Bukti Pembayaran:</span> Belum ada bukti pembayaran (mungkin COD atau belum diupload).
    </div>
<?php endif; ?>
