require(['jquery', 'jquery/ui'], function($) {
    $(document).ready(function() {
        // Fetch the minimum and maximum price values from data attributes
        var sliderContainer = $('.price-slider-container');
        var minPrice = sliderContainer.data('min-price');
        var maxPrice = sliderContainer.data('max-price');

        // Initialize the jQuery UI slider
        $("#price-slider").slider({
            range: true,  // Enables range selection
            min: minPrice,  // Set the minimum price
            max: maxPrice,  // Set the maximum price
            values: [minPrice, maxPrice],  // Initial slider positions
            slide: function(event, ui) {  // Event fired as the slider is moved
                // Update the displayed minimum and maximum price values
                $("#min-price-display").text(ui.values[0]);
                $("#max-price-display").text(ui.values[1]);

                // Update the hidden input field that will be submitted with the form
                $("#price-range").val(ui.values[0] + "-" + ui.values[1]);
            }
        });
    });
});
