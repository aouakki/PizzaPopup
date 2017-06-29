/**
 * Created by abdo on 29/06/17.
 */
/// popup show or not

console.log("loaded");
$(document).ready(function () {

    setTimeout(function () {

        if (typeof $.cookie('gotcoupon') == "undefined") {
            $("#dialog-message").dialog({
                modal: true,
                draggable: false
            });

        }

    }, 3000);

});

var ShowCoupon = function () {
    $("#subscribemsg").slideUp();
    $("#couponmsg").slideDown("slow");
    $.cookie('gotcoupon', 'true', {expires: 7});
}


// facebook api


// In your onload handler

window.fbAsyncInit = function () {
    FB.init({
        appId: '1440924312657226',
        autoLogAppEvents: true,
        xfbml: true,
        version: 'v2.9'
    });
    FB.AppEvents.logPageView();
    FB.Event.subscribe('edge.create', ShowCoupon);
};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=144092431265722";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

/// twitter

document.getElementById('twitterjs').addEventListener('load', function () {
    twttr.ready(
        function (twttr) {
            twttr.events.bind('follow', ShowCoupon);
        }
    );
})

// handle popup events

$("#sendyform").submit(function (e) {
    var email = $("#emailsendy").val();
    e.preventDefault();
    $("#subscribesendy").prop('disabled', true);
    var url = $("#sendyurl").val();

    $.post(ajaxurl, {
        'action': 'subscribe_to_sendy',
        'email': email
    }, function (data) {
        console.log(data);
        if (data == "1") {
            ShowCoupon();
        } else if (data == "Already subscribed.") {
            $(".sendyerrormsg").html(data);
            $(".sendyerror").show("slow");
        }
        $("#subscribesendy").prop('disabled', false);
    });
})
