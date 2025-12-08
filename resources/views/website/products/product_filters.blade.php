<!-- Product Filters Component - Mobile/Additional Filters -->
<div class="product-filters">
    <!-- Mobile Filter Toggle -->
    <div class="mobile-filter-toggle d-lg-none">
        <button class="filter-toggle-btn" onclick="toggleMobileFilters()">
            <i class="fas fa-filter"></i>
            Filters
            <span class="filter-count" id="activeFilterCount">0</span>
        </button>
    </div>

    <!-- Mobile Filter Panel -->
    <div class="mobile-filter-panel d-lg-none" id="mobileFilterPanel">
        <div class="filter-panel-header">
            <h4>Filters</h4>
            <button class="close-filters" onclick="toggleMobileFilters()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="filter-panel-content">
            <!-- Quick Filters -->
            <div class="quick-filters">
                <h5>Quick Filters</h5>
                <div class="filter-tags">
                    <a href="{{ route('products.index') }}" class="filter-tag {{ !request('category') ? 'active' : '' }}">
                        All Products
                    </a>
                    <a href="{{ route('products.category', 'new') }}" class="filter-tag {{ request('category') === 'new' ? 'active' : '' }}">
                        New Arrivals
                    </a>
                    <a href="{{ route('products.category', 'old') }}" class="filter-tag {{ request('category') === 'old' ? 'active' : '' }}">
                        Classics
                    </a>
                    <a href="#" class="filter-tag" onclick="applyFilter('in_stock', '1')">
                        In Stock
                    </a>
                    <a href="#" class="filter-tag" onclick="applyFilter('free_delivery', '1')">
                        Free Delivery
                    </a>
                </div>
            </div>

            <!-- Price Range Slider -->
            <div class="price-range-filter">
                <h5>Price Range</h5>
                <div class="price-slider">
                    <div class="price-inputs">
                        <input type="number" id="mobileMinPrice" placeholder="Min" class="price-input">
                        <span>-</span>
                        <input type="number" id="mobileMaxPrice" placeholder="Max" class="price-input">
                    </div>
                    <button class="apply-price-btn" onclick="applyMobilePriceFilter()">Apply</button>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="sort-filter">
                <h5>Sort By</h5>
                <select class="sort-select" onchange="changeMobileSort(this.value)">
                    <option value="">Default</option>
                    <option value="name">Name A-Z</option>
                    <option value="name-desc">Name Z-A</option>
                    <option value="price">Price Low-High</option>
                    <option value="price-desc">Price High-Low</option>
                    <option value="newest">Newest First</option>
                </select>
            </div>
        </div>

        <div class="filter-panel-footer">
            <button class="clear-all-btn" onclick="clearMobileFilters()">Clear All</button>
            <button class="apply-filters-btn" onclick="applyMobileFilters()">Apply Filters</button>
        </div>
    </div>

    <!-- Active Filters Display -->
    <div class="active-filters" id="activeFilters">
        <!-- Dynamically populated -->
    </div>
</div>

<style>
/* Product Filters Styles */
.product-filters {
    margin-bottom: 20px;
}

/* Mobile Filter Toggle */
.mobile-filter-toggle {
    margin-bottom: 15px;
}

.filter-toggle-btn {
    width: 100%;
    background: white;
    border: 1px solid #ddd;
    padding: 12px 16px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-toggle-btn:hover {
    border-color: #ea1c4d;
    color: #ea1c4d;
}

.filter-count {
    background: #ea1c4d;
    color: white;
    border-radius: 50%;
    min-width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Mobile Filter Panel */
.mobile-filter-panel {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: white;
    z-index: 1050;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
}

.mobile-filter-panel.active {
    transform: translateX(0);
}

.filter-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.filter-panel-header h4 {
    margin: 0;
    font-size: 18px;
}

.close-filters {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #666;
}

.filter-panel-content {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
}

.filter-panel-content h5 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

/* Quick Filters */
.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.filter-tag {
    display: inline-block;
    padding: 6px 12px;
    background: #f8f9fa;
    color: #666;
    text-decoration: none;
    border-radius: 20px;
    font-size: 14px;
    transition: all 0.2s;
}

.filter-tag:hover,
.filter-tag.active {
    background: #ea1c4d;
    color: white;
}

/* Price Range */
.price-slider {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.price-inputs {
    display: flex;
    align-items: center;
    gap: 10px;
}

.price-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.apply-price-btn {
    background: #ea1c4d;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}

.apply-price-btn:hover {
    background: #c0173f;
}

/* Sort Select */
.sort-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    background: white;
}

/* Filter Panel Footer */
.filter-panel-footer {
    padding: 20px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}

.clear-all-btn,
.apply-filters-btn {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.clear-all-btn {
    background: #f8f9fa;
    color: #666;
}

.clear-all-btn:hover {
    background: #e9ecef;
}

.apply-filters-btn {
    background: #ea1c4d;
    color: white;
}

.apply-filters-btn:hover {
    background: #c0173f;
}

/* Active Filters */
.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 15px;
}

.active-filter {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    background: #ea1c4d;
    color: white;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.active-filter .remove-filter {
    cursor: pointer;
    margin-left: 5px;
    font-weight: bold;
}

.active-filter .remove-filter:hover {
    opacity: 0.8;
}

/* Responsive */
@media (min-width: 992px) {
    .mobile-filter-toggle,
    .mobile-filter-panel {
        display: none !important;
    }
}
</style>

<script>
// Mobile filters functionality
function toggleMobileFilters() {
    const panel = document.getElementById('mobileFilterPanel');
    panel.classList.toggle('active');
}

function applyFilter(key, value) {
    const url = new URL(window.location);
    url.searchParams.set(key, value);
    window.location.href = url.toString();
}

function applyMobilePriceFilter() {
    const minPrice = document.getElementById('mobileMinPrice').value;
    const maxPrice = document.getElementById('mobileMaxPrice').value;

    const url = new URL(window.location);
    if (minPrice) url.searchParams.set('min_price', minPrice);
    else url.searchParams.delete('min_price');

    if (maxPrice) url.searchParams.set('max_price', maxPrice);
    else url.searchParams.delete('max_price');

    window.location.href = url.toString();
}

function changeMobileSort(sortValue) {
    const url = new URL(window.location);
    if (sortValue) {
        url.searchParams.set('sort', sortValue);
    } else {
        url.searchParams.delete('sort');
    }
    window.location.href = url.toString();
}

function clearMobileFilters() {
    const url = new URL(window.location);
    url.search = '';
    window.location.href = url.toString();
}

function applyMobileFilters() {
    // Close panel and apply current filters
    toggleMobileFilters();
    // Filters are applied via individual functions
}

function updateActiveFilterCount() {
    const urlParams = new URLSearchParams(window.location.search);
    const count = urlParams.toString().split('&').filter(param => param !== '').length;
    document.getElementById('activeFilterCount').textContent = count;
}

function displayActiveFilters() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeFiltersContainer = document.getElementById('activeFilters');
    activeFiltersContainer.innerHTML = '';

    for (const [key, value] of urlParams) {
        if (key !== 'page') { // Don't show pagination as active filter
            const filterElement = document.createElement('span');
            filterElement.className = 'active-filter';
            filterElement.innerHTML = `
                ${key}: ${value}
                <span class="remove-filter" onclick="removeFilter('${key}')">&times;</span>
            `;
            activeFiltersContainer.appendChild(filterElement);
        }
    }
}

function removeFilter(key) {
    const url = new URL(window.location);
    url.searchParams.delete(key);
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    updateActiveFilterCount();
    displayActiveFilters();
});
</script>
