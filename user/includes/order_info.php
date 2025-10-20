<div class="col-md-6">
    <p><span class="label-bold">Tanggal:</span> <?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></p>
    <p><span class="label-bold">Status:</span> 
        <span class="status-badge status-<?php echo htmlspecialchars($order['status'] ?: 'pending'); ?>">
            <?php echo htmlspecialchars(ucfirst($order['status'] ?: 'pending')); ?>
        </span>
    </p>
    <p><span class="label-bold">Metode Pembayaran:</span> <?php echo htmlspecialchars(str_replace('_', ' ', ucfirst($order['payment_method']))); ?></p>
    <p><span class="label-bold">Total Harga:</span> Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></p>
</div>
<div class="col-md-6">
    <?php if (!empty($order['shipping_address'])): ?>
        <p><span class="label-bold">Alamat Pengiriman:</span><br />
        <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
    <?php endif; ?>
</div>
