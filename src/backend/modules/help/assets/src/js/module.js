/**
 * Created by antony on 7/6/16.
 */
(function () {

    var btn = $("#help-search-button");
    btn.on('click', function () {
        var input = $("#help-search-input");
        // limit empty and single character values
        if (input.val().length < 2 || MyApp.utils.empty(input.val().trim())) {
            input.focus();
            return false;
        }
    });

    var helpForm = $("#help-search-form");
    var targetUrl = helpForm.attr('action');
    var type = helpForm.attr('method') || 'GET';
    var container = $('#search-content');

    helpForm.on('submit', function (e) {

        e.preventDefault();

        $.ajax({
            url: targetUrl,
            type: type,
            data: helpForm.serialize(),
            success: function (response) {

                // replace container elements with the html partial content from the request
                container.html(response);
                MyApp.utils.stopBlockUI();
            },
            error: function (XHR) {
                var err = MyApp.utils.renderAlert('error', 'A server error has occurred. Please try again later');
                container.html(err.html);
                MyApp.utils.stopBlockUI();
            },
            beforeSend: function () {
                MyApp.utils.startBlockUI();
            }
        })
    });


    $('a[data-toggle="tab"]:first').trigger("click");
    //$('[data-tabajax="1"]:first').click();

    // https://stackoverflow.com/questions/31832227/jquery-smooth-scrolling-anchor-navigation
    // $('a[href^="#"]').on('click',function (e) {
    //     e.preventDefault();
    //     var target = this.hash;
    //     $target = $(target);
    //     $('html, body').stop().animate({
    //         'scrollTop':  $target.offset().top
    //     }, 500, 'swing', function () {
    //         window.location.hash = target;
    //     });
    // });


    // open collapsible when anchor is linked to new page/tab
    //var anchor = window.location.hash.replace("#", "");
    //$(".collapse").collapse('hide');
    //$("#" + anchor).collapse('show');
})();