$("#search").keyup(function() {
    
    if ($(this).val().length >= 3) {
        updateScreen();
        $.ajax({
            url: "async/search.php",
            type: "POST",
            data: {
                search: $(this).val()
            },
            success: function(result) {
                $("#search-result").html(result);
            }
        });
    } else {
        updateScreen();
    }
})

function updateScreen() {
    if ($(window).width() < 768 && $("#search").val().length >= 3) {
        $("#sidebar").hide();
    } else {
        $("#sidebar").show();
    }
    if ($("#search").val().length < 3) {
        $("#search-result").html("");
        $("#map").show();
        $("#sidebar").show();
        $("#search-result").hide();
    } else {
        $("#map").hide();
        $("#search-result").show();
    }
}
$(window).resize(updateScreen);
updateScreen();