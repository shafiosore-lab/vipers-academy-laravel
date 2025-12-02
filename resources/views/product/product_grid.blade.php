<!-- Product Grid Component  -->
<div class="product-grid-container">
    <!-- Grid Header -->
    <div class="grid-header">
        <div class="results-info">
            <h2 class="results-title">
                @if(request('search'))
                    Search Results for "{{ request('search') }}"
                @elseif(request('category') === 'new')
                    New Arrivals
                @elseif(request('category') === 'old')
                    Classic Collection
                @else
                    All Products
                @endif
            </h2>
            <span class="results-count">{{ $products->total() }} products found</span>
        </div>

        <div class="grid-controls">
            <!-- View Toggle -->
            <div class="view-toggle">
                <button class="view-btn active" data-view="grid" title="Grid View">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" data-view="list" title="List View">
                    <i class="fas fa-list"></i>
                </button>
            </div>

            <!-- Sort Options -->
            <div class="sort-options">
                <select class="sort-select" onchange="changeSort(this.value)">
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name-desc" {{ request('sort') === 'name-desc' ? 'selected' : '' }}>Name Z-A</option>
                    <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price Low-High</option>
                    <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price High-Low</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-grid" data-view="grid">
        @forelse($products as $product)
        <div class="product-card">
            <div class="product-image">
                @if($product->images && count($product->images) > 0)
                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" loading="lazy">
                @else
                    <div class="no-image">
                        <i class="fas fa-image"></i>
                        <span>No Image</span>
                    </div>
                @endif

                <!-- Badges -->
                <div class="product-badges">
                    @if($product->category === 'new')
                        <span class="badge new">New</span>
                    @endif
                    @if($product->stock < 1)
                        <span class="badge out-of-stock">Out of Stock</span>
                    @elseif($product->stock <= 5)
                        <span class="badge low-stock">Only {{ $product->stock }} left</span>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <button class="quick-btn" onclick="quickView({{ $product->id }})" title="Quick View">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="quick-btn" onclick="addToWishlist({{ $product->id }})" title="Add to Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <!-- Hover Overlay -->
                <div class="product-overlay">
                    <a href="{{ route('products.show', $product) }}" class="view-details-btn">
                        View Details
                    </a>
                </div>
            </div>

            <div class="product-info">
                <h3 class="product-title">
                    <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                </h3>

                <div class="product-price">
                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                </div>

                <p class="product-description">{{ Str::limit($product->description, 80) }}</p>

                <div class="product-meta">
                    <span class="sku">SKU: {{ $product->sku ?: 'N/A' }}</span>
                    <span class="stock-status {{ $product->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                        {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </div>

                <!-- Add to Cart -->
                <div class="product-actions">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-cart-btn">
                                <i class="fas fa-cart-plus"></i>
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <button class="out-of-stock-btn" disabled>
                            <i class="fas fa-times"></i>
                            Out of Stock
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="no-products">
            <div class="no-products-content">
                <i class="fas fa-shopping-bag"></i>
                <h3>No products found</h3>
                <p>Try adjusting your search or filter criteria.</p>
                <a href="{{ route('products.index') }}" class="reset-search-btn">
                    <i class="fas fa-redo"></i>
                    View All Products
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="pagination-wrapper">
        {{ $products->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="quickViewContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<style>
/* Product Grid Styles - Alibaba Inspired */
.product-grid-container {
    flex: 1;
    padding: 20px;
}

/* Grid Header */
.grid-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.results-info h2 {
    margin: 0;
    font-size: 24px;
    color: #333;
}

.results-count {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
    display: block;
}

.grid-controls {
    display: flex;
    gap: 20px;
    align-items: center;
}

/* View Toggle */
.view-toggle {
    display: flex;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.view-btn {
    background: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.view-btn:hover,
.view-btn.active {
    background: #ea1c4d;
    color: white;
}

/* Sort Options */
.sort-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    font-size: 14px;
    min-width: 150px;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.products-grid[data-view="list"] {
    grid-template-columns: 1fr;
}

.products-grid[data-view="list"] .product-card {
    display: flex;
    flex-direction: row;
}

.products-grid[data-view="list"] .product-image {
    flex: 0 0 200px;
}

.products-grid[data-view="list"] .product-info {
    flex: 1;
    padding-left: 20px;
}

/* Product Card */
.product-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #ea1c4d;
}

/* Product Image */
.product-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.no-image {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #666;
}

.no-image i {
    font-size: 48px;
    margin-bottom: 10px;
}

/* Badges */
.product-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge.new {
    background: #ea1c4d;
    color: white;
}

.badge.out-of-stock {
    background: #dc3545;
    color: white;
}

.badge.low-stock {
    background: #ffc107;
    color: #333;
}

/* Quick Actions */
.quick-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .quick-actions {
    opacity: 1;
}

.quick-btn {
    background: rgba(255,255,255,0.9);
    border: none;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #666;
}

.quick-btn:hover {
    background: #ea1c4d;
    color: white;
    transform: scale(1.1);
}

/* Product Overlay */
.product-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(234,28,77,0.9));
    padding: 20px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    display: flex;
    align-items: flex-end;
}

.product-card:hover .product-overlay {
    transform: translateY(0);
}

.view-details-btn {
    background: white;
    color: #ea1c4d;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
}

.view-details-btn:hover {
    background: #ea1c4d;
    color: white;
}

/* Product Info */
.product-info {
    padding: 20px;
}

.product-title {
    margin: 0 0 10px 0;
    font-size: 16px;
    font-weight: 600;
}

.product-title a {
    color: #333;
    text-decoration: none;
}

.product-title a:hover {
    color: #ea1c4d;
}

.product-price {
    margin-bottom: 10px;
}

.current-price {
    font-size: 18px;
    font-weight: 700;
    color: #ea1c4d;
}

.product-description {
    color: #666;
    font-size: 14px;
    line-height: 1.4;
    margin-bottom: 15px;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #999;
    margin-bottom: 15px;
}

.stock-status.in-stock {
    color: #28a745;
}

.stock-status.out-of-stock {
    color: #dc3545;
}

/* Product Actions */
.product-actions {
    margin-top: 15px;
}

.add-to-cart-btn,
.out-of-stock-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.add-to-cart-btn {
    background: #ea1c4d;
    color: white;
}

.add-to-cart-btn:hover {
    background: #c0173f;
    transform: translateY(-2px);
}

.out-of-stock-btn {
    background: #6c757d;
    color: white;
    cursor: not-allowed;
}

/* No Products */
.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.no-products-content i {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 20px;
}

.no-products-content h3 {
    color: #666;
    margin-bottom: 10px;
}

.no-products-content p {
    color: #999;
    margin-bottom: 20px;
}

.reset-search-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ea1c4d;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s;
}

.reset-search-btn:hover {
    background: #c0173f;
    transform: translateY(-2px);
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

/* Responsive */
@media (max-width: 768px) {
    .grid-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }

    .products-grid[data-view="list"] {
        grid-template-columns: 1fr;
    }

    .products-grid[data-view="list"] .product-card {
        flex-direction: column;
    }

    .products-grid[data-view="list"] .product-image {
        flex: none;
    }

    .products-grid[data-view="list"] .product-info {
        padding-left: 0;
    }
}
</style>

<script>
// Grid functionality
function changeSort(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
}

function quickView(productId) {
    // Load quick view content via AJAX
    fetch(`/products/${productId}/quick-view`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('quickViewContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
            modal.show();
        })
        .catch(error => console.error('Error loading quick view:', error));
}

function addToWishlist(productId) {
    // Implement wishlist functionality
    alert('Wishlist functionality coming soon!');
}

// View toggle
document.addEventListener('DOMContentLoaded', function() {
    const viewBtns = document.querySelectorAll('.view-btn');
    const productsGrid = document.querySelector('.products-grid');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;

            // Update buttons
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Update grid
            productsGrid.setAttribute('data-view', view);
        });
    });

    // Add to cart form handling
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('add-to-cart-form')) {
            e.preventDefault();

            const form = e.target;
            const button = form.querySelector('.add-to-cart-btn');
            const originalText = button.innerHTML;

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCount = document.getElementById('header-cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count || 0;
                    }

                    // Show success message
                    showNotification('Product added to cart!', 'success');
                } else {
                    showNotification(data.message || 'Error adding to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding to cart', 'error');
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }
    });
});

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>
