    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-gray-500 text-sm">
                PelacakDuit v1.0.0 - Pencatatan Keuangan Sederhana &copy; <?= date('Y') ?>
            </p>
        </div>
    </footer>

    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('[class*="bg-green-50"], [class*="bg-red-50"]');
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>
