<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vipers Merchandise - #1 Football Shop in Western Kenya')</title>
    <meta name="description" content="@yield('meta_description', 'Official Vipers Academy merchandise with fast delivery across Western Kenya. Lipa Polepole payment options available.')">

    <!-- Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
</head>

<body>

    <!-- Product Header -->
    @include('product.header')

    <!-- Main Content Area -->
    <main class="product-main-content">
        <div class="container-fluid">
            <div class="content-layout">
                <!-- Sidebar Filters (Desktop) -->
                <aside class="product-sidebar-wrapper d-none d-lg-block">
                    @include('product.sidebar')
                </aside>

                <!-- Main Content -->
                <div class="product-content-wrapper">
                    <!-- Mobile Filters -->
                    @include('product.product_filters')

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <!-- Product Footer -->
    @include('product.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <script>
        // ---------- Config ----------
        const AOS_OPTIONS = {
            duration: 800,
            once: true,
            offset: 50
        };

        // ---------- Initialize AOS ----------
        AOS.init(AOS_OPTIONS);

        // ---------- Mobile Menu Toggle (from header) ----------
        const mobileToggle = document.getElementById('mobileToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                mobileMenu.classList.toggle('active');
            });

            // Close when clicking outside
            document.addEventListener('click', (e) => {
                const insideNavbar = e.target.closest('.main-header');
                const insideMenu = e.target.closest('.mobile-menu');
                if (!insideNavbar && !insideMenu) mobileMenu.classList.remove('active');
            }, {
                passive: true
            });

            // Prevent close when clicking inside menu
            mobileMenu.addEventListener('click', (e) => e.stopPropagation());
        }

        // ---------- Newsletter Subscription ----------
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Thank you for subscribing to Vipers Merchandise newsletter!');
                e.target.reset();
            });
        }

        // ---------- Cart Count Update ----------
        function updateCartCount() {
            fetch('{{ route("cart.summary") }}')
                .then(response => response.json())
                .then(data => {
                    const cartCountElements = document.querySelectorAll('.cart-count');
                    cartCountElements.forEach(element => {
                        const count = data.count || 0;
                        element.textContent = count > 99 ? '99+' : count;
                        element.classList.toggle('empty', count === 0);
                    });
                })
                .catch(error => {
                    console.log('Error updating cart count:', error);
                });
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        // Update cart count after adding/removing items
        window.addEventListener('cartUpdated', function() {
            updateCartCount();
        });

        // ---------- Installment Modal Function ----------
        function showInstallmentModal() {
            const modal = new bootstrap.Modal(document.getElementById('installmentModal'));
            modal.show();
        }

        // ---------- Responsive Adjustments ----------
        function handleResponsive() {
            const sidebar = document.querySelector('.product-sidebar-wrapper');
            const content = document.querySelector('.product-content-wrapper');

            if (window.innerWidth < 992) {
                // Mobile: sidebar is hidden, content takes full width
                if (sidebar) sidebar.style.display = 'none';
                if (content) content.style.marginLeft = '0';
            } else {
                // Desktop: show sidebar, adjust content margin
                if (sidebar) sidebar.style.display = 'block';
                if (content) content.style.marginLeft = '280px';
            }
        }

        // Handle responsive on load and resize
        window.addEventListener('load', handleResponsive);
        window.addEventListener('resize', handleResponsive);
    </script>

    @stack('scripts')
</body>

</html>
