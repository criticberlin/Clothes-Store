/**
 * Category Hierarchy Manager for Product Form
 * Handles the dynamic loading of category dropdowns based on hierarchy
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get the category selector elements
    const mainCategorySelect = document.getElementById('main_category');
    const clothingTypeSelect = document.getElementById('clothing_type');
    const itemTypeSelect = document.getElementById('item_type');
    
    if (!mainCategorySelect) return; // Exit if not on a page with category selectors
    
    // Function to load child categories
    function loadChildCategories(parentId, targetSelect, type) {
        // Clear the target select
        targetSelect.innerHTML = '';
        
        // Add default empty option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '-- Select ' + type + ' --';
        targetSelect.appendChild(defaultOption);
        
        if (!parentId) {
            targetSelect.disabled = true;
            return;
        }
        
        // Enable the target select
        targetSelect.disabled = false;
        
        // Show loading indicator
        const loadingOption = document.createElement('option');
        loadingOption.value = '';
        loadingOption.textContent = 'Loading...';
        targetSelect.appendChild(loadingOption);
        
        // Fetch child categories via AJAX
        fetch(`/admin/categories/children?parent_id=${parentId}`)
            .then(response => response.json())
            .then(data => {
                // Remove loading option
                targetSelect.removeChild(loadingOption);
                
                // Add options for each child category
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    targetSelect.appendChild(option);
                });
                
                // If there's a selected value stored in a data attribute, select it
                const selectedValue = targetSelect.dataset.selected;
                if (selectedValue) {
                    targetSelect.value = selectedValue;
                    
                    // Trigger change event to load next level if needed
                    const event = new Event('change');
                    targetSelect.dispatchEvent(event);
                    
                    // Clear the data attribute to prevent reselection on future loads
                    delete targetSelect.dataset.selected;
                }
            })
            .catch(error => {
                console.error('Error loading categories:', error);
                const errorOption = document.createElement('option');
                errorOption.value = '';
                errorOption.textContent = 'Error loading categories';
                targetSelect.appendChild(errorOption);
            });
    }
    
    // Event listener for main category select
    mainCategorySelect.addEventListener('change', function() {
        const parentId = this.value;
        
        // Load clothing types
        loadChildCategories(parentId, clothingTypeSelect, 'Clothing Type');
        
        // Disable and clear item types
        itemTypeSelect.innerHTML = '';
        const defaultItemOption = document.createElement('option');
        defaultItemOption.value = '';
        defaultItemOption.textContent = '-- Select Item Type --';
        itemTypeSelect.appendChild(defaultItemOption);
        itemTypeSelect.disabled = true;
    });
    
    // Event listener for clothing type select
    clothingTypeSelect.addEventListener('change', function() {
        const parentId = this.value;
        
        // Load item types
        loadChildCategories(parentId, itemTypeSelect, 'Item Type');
    });
    
    // Initialize by triggering change on main category if it has a value
    if (mainCategorySelect.value) {
        const event = new Event('change');
        mainCategorySelect.dispatchEvent(event);
    }
}); 