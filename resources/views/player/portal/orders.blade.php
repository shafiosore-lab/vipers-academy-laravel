@extends('player.portal.layout')

@section('title', 'Order History - Player Portal')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Main Content Area -->
    <div class="col-12">
        <!-- Page Header -->
        <div class="portal-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h1 class="section-title">
                        <i class="fas fa-shopping-bag me-3 text-primary"></i>Order History
                    </h1>
                    <p class="section-subtitle">View and manage your purchase history and order tracking</p>
                </div>
                <a href="{{ route('products') }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-shopping-cart me-1"></i>Continue Shopping
                </a>
            </div>

            <!-- Order Statistics -->
            <div class="order-stats" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-bag text-primary"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $orderStats['total_orders'] }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign text-success"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">${{ number_format($orderStats['total_spent'], 2) }}</div>
                        <div class="stat-label">Total Spent</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $orderStats['pending_orders'] }}</div>
                        <div class="stat-label">Pending Orders</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-truck text-info"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $orderStats['delivered_orders'] }}</div>
                        <div class="stat-label">Delivered</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="200">
            <div class="section-header">
                <h3 class="section-title">Your Orders</h3>
                <div class="filter-controls">
                    <select class="form-select form-select-sm me-2" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <input type="text" class="form-control form-control-sm" id="searchOrders" placeholder="Search orders..." style="max-width: 200px;">
                </div>
            </div>

            @if($orders->count() > 0)
                <div class="orders-list">
                    @foreach($orders as $order)
                    <div class="order-card" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-number">
                                    <span class="label">Order #</span>
                                    <span class="value">{{ $order->order_number }}</span>
                                </div>
                                <div class="order-date">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $order->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="order-status">
                                <span class="badge order-status-{{ $order->order_status }}">
                                    <i class="fas fa-circle me-1"></i>{{ ucfirst($order->order_status) }}
                                </span>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success payment-status">Paid</span>
                                @else
                                    <span class="badge bg-warning payment-status">Pending Payment</span>
                                @endif
                            </div>
                        </div>

                        <div class="order-content">
                            <!-- Order Items Preview -->
                            @if($order->order_items && is_array($order->order_items))
                                <div class="order-items">
                                    <div class="items-preview">
                                        @foreach(array_slice($order->order_items, 0, 3) as $item)
                                            <div class="item-preview">
                                                <div class="item-image">
                                                    <img src="{{ asset('assets/img/placeholder-product.png') }}" alt="Product" onerror="this.src='{{ asset('assets/img/placeholder.png') }}'">
                                                </div>
                                                <div class="item-details">
                                                    <div class="item-name">{{ $item['name'] ?? 'Product' }}</div>
                                                    <div class="item-qty">Qty: {{ $item['quantity'] ?? 1 }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(count($order->order_items) > 3)
                                            <div class="more-items">
                                                <span>+{{ count($order->order_items) - 3 }} more</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="order-summary">
                                <div class="order-amount">
                                    <span class="label">Total:</span>
                                    <span class="value">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                @if($order->shipping_address)
                                    <div class="order-shipping">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <span>{{ Str::limit($order->shipping_address, 30) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="order-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetails('{{ $order->id }}')">
                                <i class="fas fa-eye me-1"></i>View Details
                            </button>
                            @if($order->order_status === 'delivered' && $order->payment_status === 'paid')
                                <button class="btn btn-sm btn-outline-success" onclick="reorderItems('{{ $order->id }}')">
                                    <i class="fas fa-redo me-1"></i>Reorder
                                </button>
                            @endif
                            @if(in_array($order->order_status, ['pending', 'processing']))
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelOrder('{{ $order->id }}')">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                            @endif
                            @if($order->order_status === 'shipped')
                                <button class="btn btn-sm btn-outline-info" onclick="trackOrder('{{ $order->order_number }}')">
                                    <i class="fas fa-truck me-1"></i>Track Package
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper" data-aos="fade-up">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-orders" data-aos="fade-up">
                    <div class="empty-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h4>No Orders Yet</h4>
                    <p>You haven't placed any orders yet. Start exploring our products to make your first purchase!</p>
                    <a href="{{ route('products') }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-shopping-cart me-1"></i>Browse Products
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="300">
            <div class="section-header">
                <h3 class="section-title">Quick Actions</h3>
            </div>

            <div class="quick-actions-grid">
                <a href="{{ route('cart.index') }}" class="quick-action-card" target="_blank">
                    <div class="action-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="action-content">
                        <h5>My Cart</h5>
                        <p>View and manage your shopping cart</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('products') }}" class="quick-action-card" target="_blank">
                    <div class="action-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="action-content">
                        <h5>Browse Store</h5>
                        <p>Explore our complete product catalog</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('player.portal.support') }}" class="quick-action-card">
                    <div class="action-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="action-content">
                        <h5>Order Support</h5>
                        <p>Get help with orders and purchases</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('player.portal.profile') }}" class="quick-action-card">
                    <div class="action-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="action-content">
                        <h5>Payment Methods</h5>
                        <p>Manage saved payment information</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Order Statistics */
.order-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 24px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    border: var(--border-light);
    transition: var(--transition-normal);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-icon:nth-child(1) { background: var(--primary-red-light); color: var(--primary-red); }
.stat-icon:nth-child(2) { background: rgba(16, 185, 129, 0.1); color: var(--success); }
.stat-icon:nth-child(3) { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
.stat-icon:nth-child(4) { background: rgba(59, 130, 246, 0.1); color: var(--info); }

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: var(--text-secondary);
    font-weight: 500;
}

/* Orders List */
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.order-card {
    background: white;
    border: var(--border-light);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-normal);
}

.order-card:hover {
    box-shadow: var(--shadow);
    transform: translateY(-2px);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: var(--bg-tertiary);
    border-bottom: var(--border-light);
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.order-number {
    display: flex;
    align-items: center;
    gap: 8px;
}

.order-number .label {
    font-size: 14px;
    color: var(--text-muted);
    font-weight: 500;
}

.order-number .value {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
}

.order-date {
    font-size: 14px;
    color: var(--text-secondary);
}

.order-status {
    display: flex;
    gap: 8px;
}

.order-status-pending { background: var(--warning); color: white; }
.order-status-processing { background: var(--info); color: white; }
.order-status-shipped { background: var(--primary-red); color: white; }
.order-status-delivered { background: var(--success); color: white; }
.order-status-cancelled { background: var(--secondary); color: white; }

.order-status-cancelled,
.order-status-refunded {
    background: var(--danger);
    color: white;
}

.badge {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.payment-status {
    font-size: 11px !important;
    padding: 4px 8px !important;
}

.order-content {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.order-items {
    flex: 1;
}

.items-preview {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.item-preview {
    display: flex;
    gap: 12px;
    padding: 12px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    flex: 1;
    min-width: 200px;
}

.item-image {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
}

.item-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 2px;
}

.item-qty {
    font-size: 12px;
    color: var(--text-secondary);
}

.more-items {
    padding: 12px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    font-size: 12px;
    color: var(--text-secondary);
    font-weight: 600;
    text-align: center;
}

.order-summary {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 150px;
}

.order-amount {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-amount .label {
    font-size: 14px;
    color: var(--text-secondary);
}

.order-amount .value {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-red);
}

.order-shipping {
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
}

.order-actions {
    display: flex;
    gap: 8px;
    padding: 20px;
    background: var(--bg-tertiary);
    justify-content: flex-end;
}

.order-actions .btn {
    font-size: 12px;
    padding: 6px 12px;
}

/* Empty State */
.empty-orders {
    text-align: center;
    padding: 60px 20px;
}

.empty-orders .empty-icon {
    width: 80px;
    height: 80px;
    background: var(--bg-tertiary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 32px;
    color: var(--text-muted);
}

.empty-orders h4 {
    color: var(--text-primary);
    margin-bottom: 12px;
    font-weight: 600;
}

.empty-orders p {
    color: var(--text-secondary);
    margin-bottom: 24px;
}

/* Quick Actions */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
}

.quick-action-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: white;
    border: var(--border-light);
    border-radius: 12px;
    text-decoration: none;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-sm);
}

.quick-action-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
    border-color: var(--primary-red);
}

.action-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary-red-light), rgba(234, 28, 77, 0.1));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--primary-red);
    flex-shrink: 0;
}

.action-content {
    flex: 1;
}

.action-content h5 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

.action-content p {
    margin: 0;
    font-size: 13px;
    color: var(--text-secondary);
}

.action-arrow {
    color: var(--text-muted);
    transition: var(--transition-normal);
}

.quick-action-card:hover .action-arrow {
    color: var(--primary-red);
    transform: translateX(4px);
}

/* Pagination */
.pagination-wrapper {
    margin-top: 24px;
    text-align: center;
}

.pagination-wrapper .pagination {
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .order-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .stat-card {
        padding: 16px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }

    .stat-number {
        font-size: 24px;
    }

    .order-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }

    .order-status {
        align-self: flex-end;
    }

    .order-content {
        flex-direction: column;
        gap: 16px;
    }

    .order-summary {
        min-width: unset;
        flex-direction: row;
        justify-content: space-between;
    }

    .order-actions {
        justify-content: center;
        flex-wrap: wrap;
    }

    .quick-actions-grid {
        grid-template-columns: 1fr;
    }

    .quick-action-card {
        padding: 20px;
    }

    .item-preview {
        min-width: unset;
        flex: 1;
    }
}
</style>

<script>
function viewOrderDetails(orderId) {
    // Placeholder for order details modal/view
    alert('Order details feature coming soon! Order ID: ' + orderId);
}

function reorderItems(orderId) {
    // Placeholder for reordering functionality
    alert('Reorder feature coming soon! Order ID: ' + orderId);
}

function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        // Placeholder for order cancellation
        alert('Order cancellation feature coming soon! Order ID: ' + orderId);
    }
}

function trackOrder(orderNumber) {
    // Placeholder for tracking functionality
    window.open('https://example.com/track/' + orderNumber, '_blank');
}

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const orderCards = document.querySelectorAll('.order-card');

    orderCards.forEach(card => {
        const cardStatus = card.querySelector('.order-status .badge').textContent.trim().toLowerCase();
        if (status === '' || cardStatus.startsWith(status)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Search functionality
document.getElementById('searchOrders').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const orderCards = document.querySelectorAll('.order-card');

    orderCards.forEach(card => {
        const orderNumber = card.querySelector('.order-number .value').textContent.trim().toLowerCase();
        const orderDate = card.querySelector('.order-date').textContent.trim().toLowerCase();

        if (orderNumber.includes(searchTerm) || orderDate.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>
@endsection
