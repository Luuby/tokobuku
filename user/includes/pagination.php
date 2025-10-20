<?php
// /user/includes/pagination.php
if (!isset($total_pages) || $total_pages <= 1) return;
$current = isset($current_page) ? intval($current_page) : 1;
$total = intval($total_pages);
$query = $_GET; unset($query['page']);
$base = strtok($_SERVER["REQUEST_URI"], '?');
function page_url($p,$q,$b){ $qq = $q; $qq['page']=$p; return $b . '?' . http_build_query($qq); }
?>
<nav class="pagination-wrap" aria-label="Pagination">
  <ul class="pagination-list">
    <?php if ($current > 1): ?>
      <li><a href="<?php echo page_url($current-1,$query,$base); ?>">&laquo; Prev</a></li>
    <?php else: ?>
      <li class="disabled">&laquo; Prev</li>
    <?php endif; ?>

    <?php
    $start = max(1, $current - 2);
    $end = min($total, $current + 2);
    if ($start > 1) { echo '<li><a href="'.page_url(1,$query,$base).'">1</a></li>'; if ($start>2) echo '<li class="dots">...</li>'; }
    for ($i=$start;$i<=$end;$i++){
      if ($i==$current) echo '<li class="active">'.$i.'</li>';
      else echo '<li><a href="'.page_url($i,$query,$base).'">'.$i.'</a></li>';
    }
    if ($end < $total) { if ($end < $total-1) echo '<li class="dots">...</li>'; echo '<li><a href="'.page_url($total,$query,$base).'">'.$total.'</a></li>'; }
    ?>

    <?php if ($current < $total): ?>
      <li><a href="<?php echo page_url($current+1,$query,$base); ?>">Next &raquo;</a></li>
    <?php else: ?>
      <li class="disabled">Next &raquo;</li>
    <?php endif; ?>
  </ul>
</nav>
