<?php
// Get filter parameters from URL if they exist
$size = isset($_GET['size']) ? $_GET['size'] : 'all';
$priceRange = isset($_GET['price']) ? $_GET['price'] : 'all';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';

// Get price min and max from URL if they exist
$priceMin = isset($_GET['price_min']) ? $_GET['price_min'] : '7990000';
$priceMax = isset($_GET['price_max']) ? $_GET['price_max'] : '149990000';

// Get available sizes from database if not provided
$sizes = isset($sizes) ? $sizes : ['36', '37', '38', '39', '40', '41', '42', '43'];
?>

<div class="col-lg-3 col-xl-3">
    <div class="row">
        <div class="col-sm-12">
            <div class="side border mb-1">
                <h3>Size</h3>
                <select class="form-control filter-control" id="shoeSize" name="size">
                    <option value="all" <?php echo $size === 'all' ? 'selected' : ''; ?>>Tất cả</option>
                    <?php foreach ($sizes as $sizeOption): ?>
                        <option value="<?php echo $sizeOption; ?>" <?php echo $size == $sizeOption ? 'selected' : ''; ?>><?php echo $sizeOption; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="side border mb-1">
                <h3>Price Range</h3>
                <div class="price-range-container">
                    <p>Hoặc nhập khoảng giá phù hợp với bạn:</p>
                    <div class="price-inputs-container d-flex mb-2">
                        <input type="text" id="priceMin" name="price_min" class="form-control" value="7.990.000đ">
                        <span class="mx-2 my-auto">~</span>
                        <input type="text" id="priceMax" name="price_max" class="form-control" value="149.990.000đ">
                    </div>
                    <div class="range-slider">
                        <input type="range" id="priceSlider" min="0" max="150000000" step="1000000" class="form-control-range">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="side border mb-1">
                <h3>Sort By</h3>
                <select class="form-control filter-control" id="sortBy" name="sort">
                    <option value="price_asc" <?php echo $sortBy === 'price_asc' ? 'selected' : ''; ?>>Giá thấp đến cao</option>
                    <option value="price_desc" <?php echo $sortBy === 'price_desc' ? 'selected' : ''; ?>>Giá cao đến thấp</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <button id="applyFilters" class="btn btn-primary btn-block">Apply Filters</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyButton = document.getElementById('applyFilters');
    const filterControls = document.querySelectorAll('.filter-control');
    const priceMinInput = document.getElementById('priceMin');
    const priceMaxInput = document.getElementById('priceMax');
    const priceSlider = document.getElementById('priceSlider');
    
    // Default min and max values
    const minPrice = 0;
    const maxPrice = 150000000;
    
    // Format price inputs with dots as thousands separator and đ symbol
    function formatPriceInput(input) {
        // Remove non-numeric characters
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format with dots and đ
        if (value) {
            input.value = Number(value).toLocaleString('vi-VN').replace(/,/g, '.') + 'đ';
        }
        
        return value;
    }
    
    // Set slider value based on min and max inputs
    const updateSliderFromInputs = () => {
        if (!priceSlider) return;
        
        const minValue = parseInt(priceMinInput.value.replace(/[^\d]/g, '')) || minPrice;
        const maxValue = parseInt(priceMaxInput.value.replace(/[^\d]/g, '')) || maxPrice;
        
        // Calculate a value for the single slider that represents the position between min and max
        const percentage = (minValue + maxValue) / 2;
        priceSlider.value = percentage;
    };
    
    // Update price range when slider changes
    if (priceSlider) {
        priceSlider.addEventListener('input', function() {
            // This is a simple implementation for a single slider that updates both min and max
            const sliderValue = parseInt(this.value);
            const range = maxPrice - minPrice;
            
            // Create a range around the slider value
            const rangeWidth = range * 0.2; // Adjust this value to control the range width
            let newMin = Math.max(minPrice, Math.round((sliderValue - rangeWidth/2) / 1000000) * 1000000);
            let newMax = Math.min(maxPrice, Math.round((sliderValue + rangeWidth/2) / 1000000) * 1000000);
            
            // Update the input fields
            priceMinInput.value = Number(newMin).toLocaleString('vi-VN').replace(/,/g, '.') + 'đ';
            priceMaxInput.value = Number(newMax).toLocaleString('vi-VN').replace(/,/g, '.') + 'đ';
        });
    }
    
    // Initialize formatting on inputs
    if (priceMinInput) formatPriceInput(priceMinInput);
    if (priceMaxInput) formatPriceInput(priceMaxInput);
    
    // Update slider when inputs change
    if (priceMinInput) {
        priceMinInput.addEventListener('blur', function() {
            formatPriceInput(this);
            updateSliderFromInputs();
        });
    }
    
    if (priceMaxInput) {
        priceMaxInput.addEventListener('blur', function() {
            formatPriceInput(this);
            updateSliderFromInputs();
        });
    }
    
    // Initialize slider position
    updateSliderFromInputs();
    
    if (applyButton) {
        applyButton.addEventListener('click', function() {
            const size = document.getElementById('shoeSize').value;
            const sort = document.getElementById('sortBy').value;
            
            // Get price values (remove non-numeric characters)
            const priceMin = priceMinInput ? priceMinInput.value.replace(/[^\d]/g, '') : '';
            const priceMax = priceMaxInput ? priceMaxInput.value.replace(/[^\d]/g, '') : '';
            
            // Get current URL without query parameters
            let url = window.location.href.split('?')[0];
            
            // Add filter parameters
            url += `?size=${size}&price_min=${priceMin}&price_max=${priceMax}&sort=${sort}`;
            
            window.location.href = url;
        });
    }
});
</script> 