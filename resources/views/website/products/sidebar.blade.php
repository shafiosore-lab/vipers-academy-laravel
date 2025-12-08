<!-- Product Sidebar Filters -->
<aside class="product-sidebar">
    <div class="sidebar-content">
        <div class="sidebar-header">
            <h2 class="sidebar-title">
                <i class="fas fa-filter"></i>
                Filters
            </h2>
            <button class="clear-filters-btn" onclick="clearAllFilters()" id="clearFiltersBtn" style="display: none;">
                <i class="fas fa-times"></i>
                Clear All
            </button>
        </div>

        <!-- Category Filters -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="fas fa-th"></i>
                Categories
            </h3>
            <div class="filter-content">
                <div class="category-filter">
                    <a href="{{ route('products.index') }}" class="category-link {{ !request('category') ? 'active' : '' }}">
                        <span class="category-name">All Products</span>
                        <span class="category-count">0</span>
                    </a>
                    <a href="{{ route('products.category', 'jerseys') }}" class="category-link {{ request('category') === 'jerseys' ? 'active' : '' }}">
                        <span class="category-name">Jerseys</span>
                        <span class="category-count">0</span>
                    </a>
                    <a href="{{ route('products.category', 'training') }}" class="category-link {{ request('category') === 'training' ? 'active' : '' }}">
                        <span class="category-name">Training Gear</span>
                        <span class="category-count">0</span>
                    </a>
                    <a href="{{ route('products.category', 'accessories') }}" class="category-link {{ request('category') === 'accessories' ? 'active' : '' }}">
                        <span class="category-name">Accessories</span>
                        <span class="category-count">0</span>
                    </a>
                    <a href="{{ route('products.category', 'footwear') }}" class="category-link {{ request('category') === 'footwear' ? 'active' : '' }}">
                        <span class="category-name">Footwear</span>
                        <span class="category-count">0</span>
                    </a>
                    <a href="{{ route('products.category', 'equipment') }}" class="category-link {{ request('category') === 'equipment' ? 'active' : '' }}">
                        <span class="category-name">Equipment</span>
                        <span class="category-count">0</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Price Range -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="fas fa-dollar-sign"></i>
                Price Range
            </h3>
            <div class="filter-content">
                <div class="price-range-inputs">
                    <div class="price-input-group">
                        <label for="minPrice">Min Price (KSh)</label>
                        <input type="number" id="minPrice" class="price-input" placeholder="0" value="{{ request('min_price') }}">
                    </div>
                    <div class="price-input-group">
                        <label for="maxPrice">Max Price (KSh)</label>
                        <input type="number" id="maxPrice" class="price-input" placeholder="50000" value="{{ request('max_price') }}">
                    </div>
                    <button class="apply-price-btn" onclick="applyPriceFilter()">
                        <i class="fas fa-check"></i>
                        Apply
                    </button>
                </div>
            </div>
        </div>

        <!-- Availability -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="fas fa-check-circle"></i>
                Availability
            </h3>
            <div class="filter-content">
                <div class="availability-filters">
                    <label class="filter-checkbox">
                        <input type="checkbox" onchange="applyCheckboxFilter('in_stock', this.checked)" {{ request('in_stock') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        In Stock
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" onchange="applyCheckboxFilter('on_sale', this.checked)" {{ request('on_sale') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        On Sale
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" onchange="applyCheckboxFilter('new_arrival', this.checked)" {{ request('new_arrival') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        New Arrivals
                    </label>
                </div>
            </div>
        </div>

        <!-- Size Filters -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="fas fa-tshirt"></i>
                Size
            </h3>
            <div class="filter-content">
                <div class="size-filters">
                    <label class="size-filter {{ request('size') === 'xs' ? 'active' : '' }}">
                        <input type="radio" name="size" value="xs" onchange="applySizeFilter(this.value)" {{ request('size') === 'xs' ? 'checked' : '' }}>
                        XS
                    </label>
                    <label class="size-filter {{ request('size') === 's' ? 'active' : '' }}">
                        <input type="radio" name="size" value="s" onchange="applySizeFilter(this.value)" {{ request('size') === 's' ? 'checked' : '' }}>
                        S
                    </label>
                    <label class="size-filter {{ request('size') === 'm' ? 'active' : '' }}">
                        <input type="radio" name="size" value="m" onchange="applySizeFilter(this.value)" {{ request('size') === 'm' ? 'checked' : '' }}>
                        M
                    </label>
                    <label class="size-filter {{ request('size') === 'l' ? 'active' : '' }}">
                        <input type="radio" name="size" value="l" onchange="applySizeFilter(this.value)" {{ request('size') === 'l' ? 'checked' : '' }}>
                        L
                    </label>
                    <label class="size-filter {{ request('size') === 'xl' ? 'active' : '' }}">
                        <input type="radio" name="size" value="xl" onchange="applySizeFilter(this.value)" {{ request('size') === 'xl' ? 'checked' : '' }}>
                        XL
                    </label>
                    <label class="size-filter {{ request('size') === 'xxl' ? 'active' : '' }}">
                        <input type="radio" name="size" value="xxl" onchange="applySizeFilter(this.value)" {{ request('size') === 'xxl' ? 'checked' : '' }}>
                        XXL
                    </label>
                </div>
            </div>
        </div>

        <!-- Sort Options -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="fas fa-sort"></i>
                Sort By
            </h3>
            <div class="filter-content">
                <select class="sort-select" onchange="applySort(this.value)">
                    <option value="">Default</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                </select>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="fas fa-link"></i>
                Quick Links
            </h3>
            <div class="filter-content">
                <div class="quick-links">
                    <a href="{{ route('products.index', ['filter' => 'sale']) }}" class="quick-link">
                        <i class="fas fa-tag"></i>
                        <span>Sale Items</span>
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}" class="quick-link">
                        <i class="fas fa-star"></i>
                        <span>New Arrivals</span>
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'bestsellers']) }}" class="quick-link">
                        <i class="fas fa-trophy"></i>
                        <span>Bestsellers</span>
                    </a>
                    <a href="{{ route('products.index', ['filter' => 'clearance']) }}" class="quick-link">
                        <i class="fas fa-percent"></i>
                        <span>Clearance</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</aside>
