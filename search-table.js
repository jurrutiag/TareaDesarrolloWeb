$("#search").keyup(function() {
    
    if ($(this).val().length >= 3) {
        updateScreen();
        $.ajax({
            url: "../async/search.php",
            type: "POST",
            data: {
                search: $(this).val(),
                main: false
            },
            success: function(result) {
                $("#search-result").html(result);
            }
        });
    } else {
        updateScreen();
    }
});

function updateScreen() {
    if ($("#search").val().length < 3) {
        $("#search-result").hide();
        $("#table-result").show();
    } else {
        $("#search-result").show();
        $("#table-result").hide();
    }
}