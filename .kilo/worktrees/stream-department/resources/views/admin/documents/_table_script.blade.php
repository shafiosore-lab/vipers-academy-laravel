<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize bulk actions for all tabs
    initializeBulkActions();

    // Initialize filters for all tab contents
    ['all', 'active', 'mandatory', 'expiring'].forEach(tab => {
        initializeTabFilters(tab);
    });

    // Handle tab changes
    const tabs = document.querySelectorAll('#documentTabs a[data-bs-toggle="tab"]');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('href').substring(1);
            reinitializeEventListeners(target);
        });
    });
});

function initializeBulkActions() {
    const bulkActions = document.getElementById('bulkActions');
    const checkboxes = document.querySelectorAll('.document-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllall');

    // Handle individual checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsVisibility);
    });

    // Handle select all checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateBulkActionsVisibility();
        });
    }

    function updateBulkActionsVisibility() {
        const checkedBoxes = document.querySelectorAll('.document-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkActions.classList.remove('hidden');
            document.getElementById('selectedCount').textContent = checkedBoxes.length + ' documents selected';
        } else {
            bulkActions.classList.add('hidden');
        }
    }
}

function initializeTabFilters(tab) {
    const searchInput = document.getElementById(`search${tab}`);
    const categorySelect = document.getElementById(`category${tab}`);
    const languageSelect = document.getElementById(`language${tab}`);
    const statusSelect = document.getElementById(`status${tab}`);
    const sortBySelect = document.getElementById(`sortBy${tab}`);

    let debounceTimer;

    function applyFilters() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const params = new URLSearchParams(window.location.search);

            if (searchInput && searchInput.value) {
                params.set('search', searchInput.value);
            } else {
                params.delete('search');
            }

            if (categorySelect && categorySelect.value) {
                params.set('category', categorySelect.value);
            } else {
                params.delete('category');
            }

            if (languageSelect && languageSelect.value) {
                params.set('language', languageSelect.value);
            } else {
                params.delete('language');
            }

            if (statusSelect && statusSelect.value) {
                params.set('status', statusSelect.value);
            } else {
                params.delete('status');
            }

            if (sortBySelect && sortBySelect.value) {
                params.set('sort_by', sortBySelect.value);
            } else {
                params.delete('sort_by');
            }

            // Keep current sort direction
            params.set('sort_direction', params.get('sort_direction') || 'desc');

            // Reload with new parameters
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }, 500);
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (categorySelect) categorySelect.addEventListener('change', applyFilters);
    if (languageSelect) languageSelect.addEventListener('change', applyFilters);
    if (statusSelect) statusSelect.addEventListener('change', applyFilters);
    if (sortBySelect) sortBySelect.addEventListener('change', applyFilters);
}

// Bulk operations
function bulkAction(action) {
    const selectedDocs = document.querySelectorAll('.document-checkbox:checked');
    if (selectedDocs.length === 0) {
        alert('Please select at least one document.');
        return;
    }

    const documentIds = Array.from(selectedDocs).map(cb => cb.value);

    if (confirm(`Are you sure you want to ${action} ${selectedDocs.length} selected document(s)?`)) {
        fetch(`{{ route("admin.documents.bulk") }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                action: action,
                document_ids: documentIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Operation failed'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
        });
    }
}

// Bulk categorize modal
function bulkModal() {
    const modal = new bootstrap.Modal(document.getElementById('bulkCategorizeModal'));
    modal.show();
}

function submitBulkCategorize() {
    const selectedDocs = document.querySelectorAll('.document-checkbox:checked');
    const category = document.getElementById('bulkCategory').value;

    if (selectedDocs.length === 0 || !category) {
        alert('Please select documents and choose a category.');
        return;
    }

    const documentIds = Array.from(selectedDocs).map(cb => cb.value);

    const data = {
        action: 'categorize',
        document_ids: documentIds,
        category: category
    };

    fetch(`{{ route("admin.documents.bulk") }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('bulkCategorizeModal')).hide();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Categorization failed'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    });
}

// Individual document actions
function deleteDocument(id, title) {
    if (confirm(`Are you sure you want to delete "${title}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route("admin.documents.index") }}/${id}`;

        // Create a hidden method field for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        // CSRF token
        const csrfField = document.createElement('input');
        csrfField.type = 'hidden';
        csrfField.name = '_token';
        csrfField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfField);

        document.body.appendChild(form);
        form.submit();
    }
}

function previewDocument(id) {
    window.open(`{{ route("admin.documents.preview", ":id") }}`.replace(':id', id), '_blank');
}

// Utility functions
function clearFilters(tab) {
    const searchInput = document.getElementById(`search${tab}`);
    const categorySelect = document.getElementById(`category${tab}`);
    const languageSelect = document.getElementById(`language${tab}`);
    const statusSelect = document.getElementById(`status${tab}`);
    const sortBySelect = document.getElementById(`sortBy${tab}`);

    if (searchInput) searchInput.value = '';
    if (categorySelect) categorySelect.value = '';
    if (languageSelect) languageSelect.value = '';
    if (statusSelect) statusSelect.value = '';
    if (sortBySelect) sortBySelect.value = 'created_at';

    // Remove all query parameters and reload
    window.location.href = window.location.pathname;
}

function toggleSortOrder(tab) {
    const params = new URLSearchParams(window.location.search);
    const currentDirection = params.get('sort_direction') || 'desc';
    const newDirection = currentDirection === 'desc' ? 'asc' : 'desc';

    params.set('sort_direction', newDirection);
    window.location.href = `${window.location.pathname}?${params.toString()}`;
}

function reinitializeEventListeners(tab) {
    // Reinitialize event listeners for the newly active tab
    initializeBulkActions();
    initializeTabFilters(tab);
}

// Real-time statistics updates (optional)
function updateStatistics() {
    fetch(`{{ route("admin.documents.statistics") }}`)
        .then(response => response.json())
        .then(data => {
            // Update statistics in the UI
            console.log('Statistics updated:', data);
        })
        .catch(error => console.error('Error updating statistics:', error));
}

// Refresh statistics every 5 minutes
setInterval(updateStatistics, 300000);
</script>
