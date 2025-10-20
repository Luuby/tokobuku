</main> <!-- end page-main -->

<footer class="footer bw-footer py-4">
  <div class="container text-center">
    <small class="text-muted">&copy; <?php echo date('Y'); ?> Bookstore • All rights reserved.</small>
  </div>
</footer>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Small interactive scripts -->
<script>
  // smooth scroll for CTA
  document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click', function(e){
      const target = document.querySelector(this.getAttribute('href'));
      if(target){ e.preventDefault(); target.scrollIntoView({behavior:'smooth', block:'start'}); }
    })
  });
</script>

</body>
</html>
