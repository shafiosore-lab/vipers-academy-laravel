

<!-- Product Detail Sections -->
<div class="container my-4">
    <div class="row">
        <!-- Product Images Section -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ asset('storage/' . $product->images[0]) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                    @else
                        <div class="p-5 bg-light rounded">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="mt-2">No images available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Info Section -->
        <div class="col-lg-6">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Merchandise</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>

            <!-- Product Title and Badges -->
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h1 class="h2">{{ $product->name }}</h1>
                <div>
                    @if($product->category === 'new')
                        <span class="badge bg-danger">New Arrival</span>
                    @endif
                    @if($product->stock > 0)
                        <span class="badge bg-success">In Stock</span>
                    @else
                        <span class="badge bg-secondary">Out of Stock</span>
                    @endif
                </div>
            </div>

            <!-- Product Rating -->
            <div class="mb-3">
                <div class="text-warning">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span class="text-muted ms-2">4.5 (120 reviews)</span>
                </div>
            </div>

            <!-- Product Price -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="h3 text-danger mb-0">${{ number_format($product->price, 2) }}</span>
                        <span class="text-muted text-decoration-line-through">$99.99</span>
                        <span class="badge bg-danger">20% OFF</span>
                    </div>
                    <div class="text-muted">
                        <i class="fas fa-hand-holding-usd"></i>
                        Lipa Polepole available
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="mb-4">
                <div class="row g-2">
                    <div class="col-sm-4"><strong>SKU:</strong></div>
                    <div class="col-sm-8">{{ $product->sku ?: 'N/A' }}</div>

                    <div class="col-sm-4"><strong>Stock:</strong></div>
                    <div class="col-sm-8 {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}
                    </div>

                    <div class="col-sm-4"><strong>Category:</strong></div>
                    <div class="col-sm-8">{{ ucfirst($product->category) }}</div>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="mb-4">
                <label class="form-label fw-bold">Quantity:</label>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group" style="width: 140px;">
                        <button class="btn btn-outline-secondary" type="button">-</button>
                        <input type="number" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
                        <button class="btn btn-outline-secondary" type="button">+</button>
                    </div>
                    <small class="text-muted">{{ $product->stock }} available</small>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 mb-4">
                @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-cart-plus"></i>
                            Add to Cart
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-lg w-100" disabled>
                        <i class="fas fa-times"></i>
                        Out of Stock
                    </button>
                @endif

                <button class="btn btn-warning btn-lg">
                    <i class="fas fa-bolt"></i>
                    Buy Now
                </button>

                <button class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-heart"></i>
                    Add to Wishlist
                </button>
            </div>

            <!-- Delivery Info -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="text-center p-3 border rounded">
                        <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                        <div class="fw-bold">Free Delivery</div>
                        <small class="text-muted">Orders over KSh 2,000</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 border rounded">
                        <i class="fas fa-clock fa-2x text-info mb-2"></i>
                        <div class="fw-bold">Express Delivery</div>
                        <small class="text-muted">1-2 business days</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3 border rounded">
                        <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                        <div class="fw-bold">Quality Guarantee</div>
                        <small class="text-muted">30-day return policy</small>
                    </div>
                </div>
            </div>

            <!-- Share Product -->
            <div class="d-flex align-items-center gap-3">
                <span class="fw-bold">Share:</span>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description Tabs -->
    <div class="mt-5">
        <ul class="nav nav-tabs" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">Specifications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews (120)</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">Shipping & Returns</button>
            </li>
        </ul>

        <div class="tab-content mt-4" id="productTabsContent">
            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="description" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h4>Product Description</h4>
                        <p>{{ $product->description }}</p>

                        <h5>Key Features:</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Premium quality materials</li>
                            <li><i class="fas fa-check text-success me-2"></i>Official Vipers Academy merchandise</li>
                            <li><i class="fas fa-check text-success me-2"></i>Comfortable and durable</li>
                            <li><i class="fas fa-check text-success me-2"></i>Perfect for training and matches</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Specifications Tab -->
            <div class="tab-pane fade" id="specifications" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h4>Specifications</h4>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td class="fw-bold">Material</td>
                                    <td>Premium Cotton</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Size</td>
                                    <td>S, M, L, XL, XXL</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Color</td>
                                    <td>{{ $product->category === 'new' ? 'Red & White' : 'Classic Green' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Care Instructions</td>
                                    <td>Machine washable</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h4>Customer Reviews</h4>
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3">
                                <div class="h2 mb-0">4.5</div>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <small class="text-muted">120 reviews</small>
                            </div>
                        </div>

                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>John M.</strong>
                                    <div class="text-warning small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <small class="text-muted">2 weeks ago</small>
                            </div>
                            <p class="mt-2 mb-0">Great quality merchandise! Perfect fit and fast delivery to Western Kenya.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Tab -->
            <div class="tab-pane fade" id="shipping" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h4>Shipping & Returns</h4>

                        <h5>Delivery Options</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="border p-3 rounded">
                                    <h6>Standard Delivery</h6>
                                    <p class="mb-1">3-5 business days</p>
                                    <small class="text-muted">Free for orders over KSh 2,000<br>KSh 300 for orders under KSh 2,000</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border p-3 rounded">
                                    <h6>Express Delivery</h6>
                                    <p class="mb-1">1-2 business days</p>
                                    <small class="text-muted">KSh 500</small>
                                </div>
                            </div>
                        </div>

                        <h5>Returns Policy</h5>
                        <p>We offer a 30-day return policy for all merchandise. Items must be in original condition with tags attached.</p>
                        <p>Contact our customer service for return authorization.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="text-center mb-4">Related Products</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100">
                        <div class="card-img-top" style="height: 200px; overflow: hidden;">
                            @if($relatedProduct->images && count($relatedProduct->images) > 0)
                                <img src="{{ asset('storage/' . $relatedProduct->images[0]) }}" class="w-100 h-100 object-fit-cover" alt="{{ $relatedProduct->name }}">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                            <p class="card-text text-danger fw-bold mb-3">${{ number_format($relatedProduct->price, 2) }}</p>
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary mt-auto">View Details</a>
                        </div>
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


