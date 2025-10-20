<?php if ($order_items->num_rows === 0): ?>
    <div class="alert alert-info">Tidak ada item dalam pesanan ini.</div>
<?php else: ?>
    <h4>Item Pesanan</h4>
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Gambar</th>
                <th>Judul Buku</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            while ($item = $order_items->fetch_assoc()):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td>
                    <?php if ($item['image'] && file_exists(__DIR__ . '/../assets/images/' . $item['image'])): ?>
                        <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="Gambar Buku" class="img-thumb" />
                    <?php else: ?>
                        <img src="../assets/no-image.png" alt="No Image" class="img-thumb" />
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($item['title']); ?></td>
                <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
            <tr class="table-info">
                <td colspan="4" class="text-end fw-bold">Total Harga</td>
                <td class="fw-bold">Rp <?php echo number_format($total, 0, ',', '.'); ?></td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>
