// Smooth scroll ke section books saat klik CTA hero
document.addEventListener('DOMContentLoaded', function() {
    const heroBtn = document.querySelector('.hero-section .btn');
    if (heroBtn) {
        heroBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const booksSection = document.getElementById('books');
            if (booksSection) {
                booksSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Loading spinner saat submit search
    const searchForm = document.querySelector('.search-bar form');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mencari...';
                btn.disabled = true;
                // Reset setelah 2 detik (simulasi; ganti dengan AJAX jika perlu)
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 2000);
            }
        });
    }

    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});