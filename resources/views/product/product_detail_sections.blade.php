<!-- Product Detail Sections - Alibaba Style -->
<div class="product-detail-container">
    <div class="product-detail-wrapper">
        <!-- Product Images Section -->
        <div class="product-images-section">
            <div class="main-image-container">
                @if($product->images && count($product->images) > 0)
                    <div id="productImageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image) }}" class="d-block w-100 main-product-image"
                                         alt="{{ $product->name }}" onclick="openImageModal(this.src)">
                                </div>
                            @endforeach
                        </div>
                        @if(count($product->images) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productImageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productImageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="no-image-placeholder">
                        <i class="fas fa-image"></i>
                        <p>No images available</p>
                    </div>
                @endif
            </div>

            <!-- Thumbnail Images -->
            @if($product->images && count($product->images) > 1)
                <div class="thumbnail-images">
                    @foreach($product->images as $index => $image)
                        <img src="{{ asset('storage/' . $image) }}" class="thumbnail-image {{ $index === 0 ? 'active' : '' }}"
                             alt="Thumbnail {{ $index + 1 }}" onclick="changeMainImage({{ $index }})">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info Section -->
        <div class="product-info-section">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products') }}">Merchandise</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>

            <!-- Product Title and Badges -->
            <div class="product-header">
                <h1 class="product-title">{{ $product->name }}</h1>
                <div class="product-badges">
                    @if($product->category === 'new')
                        <span class="badge new-badge">New Arrival</span>
                    @endif
                    @if($product->stock > 0)
                        <span class="badge in-stock-badge">In Stock</span>
                    @else
                        <span class="badge out-of-stock-badge">Out of Stock</span>
                    @endif
                </div>
            </div>

            <!-- Product Rating -->
            <div class="product-rating">
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span class="rating-text">4.5 (120 reviews)</span>
                </div>
            </div>

            <!-- Product Price -->
            <div class="product-price-section">
                <div class="price-container">
                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                    <span class="original-price">$99.99</span>
                    <span class="discount-badge">20% OFF</span>
                </div>
                <div class="price-info">
                    <span class="installment-info">
                        <i class="fas fa-hand-holding-usd"></i>
                        Lipa Polepole available
                    </span>
                </div>
            </div>

            <!-- Product Details -->
            <div class="product-details">
                <div class="detail-row">
                    <span class="detail-label">SKU:</span>
                    <span class="detail-value">{{ $product->sku ?: 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Stock:</span>
                    <span class="detail-value {{ $product->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                        {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Category:</span>
                    <span class="detail-value">{{ ucfirst($product->category) }}</span>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="quantity-section">
                <label class="quantity-label">Quantity:</label>
                <div class="quantity-selector">
                    <button class="qty-btn" onclick="changeQuantity(-1)">-</button>
                    <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="qty-input">
                    <button class="qty-btn" onclick="changeQuantity(1)">+</button>
                </div>
                <span class="stock-info">{{ $product->stock }} available</span>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="cart_quantity" value="1">
                        <button type="submit" class="btn-add-to-cart">
                            <i class="fas fa-cart-plus"></i>
                            Add to Cart
                        </button>
                    </form>
                @else
                    <button class="btn-out-of-stock" disabled>
                        <i class="fas fa-times"></i>
                        Out of Stock
                    </button>
                @endif

                <button class="btn-buy-now" onclick="buyNow()">
                    <i class="fas fa-bolt"></i>
                    Buy Now
                </button>

                <button class="btn-wishlist" onclick="addToWishlist({{ $product->id }})">
                    <i class="fas fa-heart"></i>
                    Add to Wishlist
                </button>
            </div>

            <!-- Delivery Info -->
            <div class="delivery-info">
                <div class="delivery-option">
                    <i class="fas fa-truck"></i>
                    <div>
                        <strong>Free Delivery</strong>
                        <p>Orders over KSh 2,000 in Western Kenya</p>
                    </div>
                </div>
                <div class="delivery-option">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Express Delivery</strong>
                        <p>1-2 business days</p>
                    </div>
                </div>
                <div class="delivery-option">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Quality Guarantee</strong>
                        <p>30-day return policy</p>
                    </div>
                </div>
            </div>

            <!-- Share Product -->
            <div class="share-section">
                <span class="share-label">Share:</span>
                <div class="share-buttons">
                    <button class="share-btn" onclick="shareOnWhatsApp()">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    <button class="share-btn" onclick="shareOnFacebook()">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button class="share-btn" onclick="shareOnTwitter()">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button class="share-btn" onclick="copyProductLink()">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description Tabs -->
    <div class="product-tabs-section">
        <div class="tabs-container">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="switchTab('description')">Description</button>
                <button class="tab-btn" onclick="switchTab('specifications')">Specifications</button>
                <button class="tab-btn" onclick="switchTab('reviews')">Reviews (120)</button>
                <button class="tab-btn" onclick="switchTab('shipping')">Shipping & Returns</button>
            </div>

            <div class="tab-content">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-pane active">
                    <div class="product-description">
                        <h3>Product Description</h3>
                        <p>{{ $product->description }}</p>

                        <div class="features-list">
                            <h4>Key Features:</h4>
                            <ul>
                                <li>Premium quality materials</li>
                                <li>Official Vipers Academy merchandise</li>
                                <li>Comfortable and durable</li>
                                <li>Perfect for training and matches</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div id="specifications-tab" class="tab-pane">
                    <div class="product-specifications">
                        <h3>Specifications</h3>
                        <table class="specs-table">
                            <tr>
                                <td>Material</td>
                                <td>Premium Cotton</td>
                            </tr>
                            <tr>
                                <td>Size</td>
                                <td>S, M, L, XL, XXL</td>
                            </tr>
                            <tr>
                                <td>Color</td>
                                <td>{{ $product->category === 'new' ? 'Red & White' : 'Classic Green' }}</td>
                            </tr>
                            <tr>
                                <td>Care Instructions</td>
                                <td>Machine washable</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="reviews-tab" class="tab-pane">
                    <div class="product-reviews">
                        <h3>Customer Reviews</h3>
                        <div class="reviews-summary">
                            <div class="rating-overview">
                                <div class="average-rating">
                                    <span class="rating-number">4.5</span>
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="total-reviews">120 reviews</span>
                                </div>
                            </div>
                        </div>

                        <div class="reviews-list">
                            <!-- Sample reviews -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <span class="reviewer-name">John M.</span>
                                        <div class="review-stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <span class="review-date">2 weeks ago</span>
                                </div>
                                <p class="review-text">Great quality merchandise! Perfect fit and fast delivery to Western Kenya.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Tab -->
                <div id="shipping-tab" class="tab-pane">
                    <div class="shipping-info">
                        <h3>Shipping & Returns</h3>

                        <div class="shipping-details">
                            <h4>Delivery Options</h4>
                            <div class="shipping-option">
                                <h5>Standard Delivery</h5>
                                <p>3-5 business days - Free for orders over KSh 2,000</p>
                                <p>KSh 300 for orders under KSh 2,000</p>
                            </div>
                            <div class="shipping-option">
                                <h5>Express Delivery</h5>
                                <p>1-2 business days - KSh 500</p>
                            </div>
                        </div>

                        <div class="returns-policy">
                            <h4>Returns Policy</h4>
                            <p>We offer a 30-day return policy for all merchandise. Items must be in original condition with tags attached.</p>
                            <p>Contact our customer service for return authorization.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="related-products-section">
        <h2 class="section-title">Related Products</h2>
        <div class="related-products-grid">
            @foreach($relatedProducts as $relatedProduct)
                <div class="related-product-card">
                    <div class="related-product-image">
                        @if($relatedProduct->images && count($relatedProduct->images) > 0)
                            <img src="{{ asset('storage/' . $relatedProduct->images[0]) }}" alt="{{ $relatedProduct->name }}">
                        @else
                            <div class="no-image">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="related-product-info">
                        <h4>{{ $relatedProduct->name }}</h4>
                        <div class="related-product-price">${{ number_format($relatedProduct->price, 2) }}</div>
                        <a href="{{ route('products.show', $relatedProduct) }}" class="view-related-btn">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="w-100" alt="Product Image">
            </div>
        </div>
    </div>
</div>

<style>
/* Product Detail Sections Styles */
.product-detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.product-detail-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 60px;
}

/* Product Images */
.product-images-section {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.main-image-container {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.main-product-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.main-product-image:hover {
    transform: scale(1.05);
}

.no-image-placeholder {
    width: 100%;
    height: 400px;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #666;
}

.no-image-placeholder i {
    font-size: 64px;
    margin-bottom: 15px;
}

.thumbnail-images {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 5px 0;
}

.thumbnail-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s;
}

.thumbnail-image.active {
    border-color: #ea1c4d;
}

.thumbnail-image:hover {
    border-color: #ea1c4d;
}

/* Product Info */
.product-info-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.breadcrumb-nav {
    margin-bottom: 10px;
}

.product-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.product-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin: 0;
    flex: 1;
}

.product-badges {
    display: flex;
    flex-direction: column;
    gap: 5px;
    align-items: flex-end;
}

.badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.new-badge {
    background: #ea1c4d;
    color: white;
}

.in-stock-badge {
    background: #28a745;
    color: white;
}

.out-of-stock-badge {
    background: #dc3545;
    color: white;
}

/* Rating */
.product-rating {
    margin: 10px 0;
}

.stars {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #ffc107;
}

.rating-text {
    margin-left: 10px;
    color: #666;
    font-size: 14px;
}

/* Price */
.product-price-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.price-container {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
}

.current-price {
    font-size: 32px;
    font-weight: 700;
    color: #ea1c4d;
}

.original-price {
    font-size: 20px;
    color: #999;
    text-decoration: line-through;
}

.discount-badge {
    background: #ea1c4d;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.installment-info {
    color: #666;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Details */
.product-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.detail-label {
    font-weight: 600;
    color: #666;
}

.detail-value.in-stock {
    color: #28a745;
}

.detail-value.out-of-stock {
    color: #dc3545;
}

/* Quantity */
.quantity-section {
    display: flex;
    align-items: center;
    gap: 15px;
}

.quantity-label {
    font-weight: 600;
    color: #333;
}

.quantity-selector {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.qty-btn {
    background: #f8f9fa;
    border: none;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.qty-btn:hover {
    background: #ea1c4d;
    color: white;
}

.qty-input {
    width: 60px;
    height: 35px;
    border: none;
    text-align: center;
    font-weight: 600;
}

.stock-info {
    color: #666;
    font-size: 14px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-add-to-cart,
.btn-buy-now,
.btn-wishlist,
.btn-out-of-stock {
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-add-to-cart {
    background: #ea1c4d;
    color: white;
    flex: 1;
}

.btn-add-to-cart:hover {
    background: #c0173f;
    transform: translateY(-2px);
}

.btn-buy-now {
    background: #ffc107;
    color: #333;
}

.btn-buy-now:hover {
    background: #e6a845;
    transform: translateY(-2px);
}

.btn-wishlist {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #ddd;
}

.btn-wishlist:hover {
    background: #ea1c4d;
    color: white;
    border-color: #ea1c4d;
}

.btn-out-of-stock {
    background: #6c757d;
    color: white;
    cursor: not-allowed;
    flex: 1;
}

/* Delivery Info */
.delivery-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.delivery-option {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.delivery-option i {
    color: #ea1c4d;
    font-size: 20px;
    margin-top: 3px;
}

.delivery-option strong {
    display: block;
    color: #333;
}

.delivery-option p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

/* Share */
.share-section {
    display: flex;
    align-items: center;
    gap: 15px;
}

.share-label {
    font-weight: 600;
    color: #666;
}

.share-buttons {
    display: flex;
    gap: 10px;
}

.share-btn {
    width: 35px;
    height: 35px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #666;
}

.share-btn:hover {
    border-color: #ea1c4d;
    color: #ea1c4d;
    transform: scale(1.1);
}

/* Tabs */
.product-tabs-section {
    margin-top: 40px;
}

.tabs-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.tab-buttons {
    display: flex;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.tab-btn {
    flex: 1;
    padding: 15px 20px;
    background: none;
    border: none;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
    border-bottom: 3px solid transparent;
}

.tab-btn.active {
    color: #ea1c4d;
    border-bottom-color: #ea1c4d;
    background: white;
}

.tab-content {
    padding: 30px;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

/* Specifications */
.specs-table {
    width: 100%;
    border-collapse: collapse;
}

.specs-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.specs-table td:first-child {
    font-weight: 600;
    color: #666;
    width: 30%;
}

/* Reviews */
.reviews-summary {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.average-rating {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.rating-number {
    font-size: 48px;
    font-weight: 700;
    color: #333;
}

.total-reviews {
    color: #666;
    margin-top: 5px;
}

.review-item {
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.reviewer-name {
    font-weight: 600;
    color: #333;
}

.review-date {
    color: #666;
    font-size: 14px;
}

.review-text {
    color: #555;
    line-height: 1.6;
}

/* Related Products */
.related-products-section {
    margin-top: 60px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
}

.related-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.related-product-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.related-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #ea1c4d;
}

.related-product-image {
    height: 200px;
    overflow: hidden;
}

.related-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-product-card:hover .related-product-image img {
    transform: scale(1.05);
}

.related-product-info {
    padding: 15px;
    text-align: center;
}

.related-product-info h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.related-product-price {
    font-size: 18px;
    font-weight: 700;
    color: #ea1c4d;
    margin-bottom: 15px;
}

.view-related-btn {
    display: inline-block;
    padding: 8px 16px;
    background: #ea1c4d;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 500;
    transition: background 0.2s;
}

.view-related-btn:hover {
    background: #c0173f;
}

/* Responsive */
@media (max-width: 768px) {
    .product-detail-wrapper {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .product-title {
        font-size: 24px;
    }

    .current-price {
        font-size: 28px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .tab-buttons {
        flex-direction: column;
    }

    .related-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}
</style>

<script>
// Product detail functionality
function changeMainImage(index) {
    const carousel = document.getElementById('productImageCarousel');
    const items = carousel.querySelectorAll('.carousel-item');
    const thumbnails = document.querySelectorAll('.thumbnail-image');

    // Update carousel
    items.forEach((item, i) => {
        item.classList.toggle('active', i === index);
    });

    // Update thumbnails
    thumbnails.forEach((thumb, i) => {
        thumb.classList.toggle('active', i === index);
    });
}

function changeQuantity(delta) {
    const input = document.getElementById('quantity');
    const cartInput = document.getElementById('cart_quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.max);

    let newValue = currentValue + delta;
    newValue = Math.max(1, Math.min(maxValue, newValue));

    input.value = newValue;
    cartInput.value = newValue;
}

function buyNow() {
    // Implement buy now functionality
    alert('Buy now functionality coming soon!');
}

function addToWishlist(productId) {
    // Implement wishlist functionality
    alert('Wishlist functionality coming soon!');
}

function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');

    // Add active class to clicked button
    event.target.classList.add('active');
}

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

function shareOnWhatsApp() {
    const url = window.location.href;
    const text = `Check out this ${document.querySelector('.product-title').textContent} from Vipers Merchandise!`;
    window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`);
}

function shareOnFacebook() {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`);
}

function shareOnTwitter() {
    const text = `Check out this ${document.querySelector('.product-title').textContent} from Vipers Merchandise!`;
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(window.location.href)}`);
}

function copyProductLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Product link copied to clipboard!');
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart form handling
    const addToCartForm = document.querySelector('.add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const button = this.querySelector('.btn-add-to-cart');
            const originalText = button.innerHTML;

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
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
        });
    }
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
