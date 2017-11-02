
function showImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#upload_img')
                .attr('src', e.target.result)
                .css("display", "inline");
            $("#fileInput").css("display", "none");
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function showFileUpload() {
    $('#fileInput').click();
    return false;
}

function showHover() {

    $('#holder')
        .removeClass("emptyHolder")
        .addClass('hoverBox');
}

function hideHover() {
    $('#holder').removeClass('hoverBox').addClass("emptyHolder");
