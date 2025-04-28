<?php
// Get filter parameters from URL if they exist
$size = isset($_GET['size']) ? $_GET['size'] : 'all';
$priceRange = isset($_GET['price']) ? $_GET['price'] : 'all';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';

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
                <select class="form-control filter-control" id="priceRange" name="price">
                    <option value="all" <?php echo $priceRange === 'all' ? 'selected' : ''; ?>>Tất cả</option>
                    <option value="under_1m" <?php echo $priceRange === 'under_1m' ? 'selected' : ''; ?>>Under 1,000,000đ</option>
                    <option value="1m_2m" <?php echo $priceRange === '1m_2m' ? 'selected' : ''; ?>>1,000,000đ - 2,000,000đ</option>
                    <option value="2m_3m" <?php echo $priceRange === '2m_3m' ? 'selected' : ''; ?>>2,000,000đ - 3,000,000đ</option>
                    <option value="over_3m" <?php echo $priceRange === 'over_3m' ? 'selected' : ''; ?>>Over 3,000,000đ</option>
                </select>
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
    
    applyButton.addEventListener('click', function() {
        const size = document.getElementById('shoeSize').value;
        const price = document.getElementById('priceRange').value;
        const sort = document.getElementById('sortBy').value;
        
        // Get current URL without query parameters
        let url = window.location.href.split('?')[0];
        
        // Add filter parameters
        url += `?size=${size}&price=${price}&sort=${sort}`;
        
        window.location.href = url;
    });
});
</script> 