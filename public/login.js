var facebookAppId = '532649330950584';
var googleClientId = '457590547778-hck4psc1cr0637g26h4bdehmklluju6o.apps.googleusercontent.com';

var googleUser = {};

function googleSignIn(googleUser) {
    const id_token = googleUser.getAuthResponse().id_token;
    const email = googleUser.getBasicProfile().getEmail();

    postToPage({ id_token: id_token, email: email });
}

function signOut() {
    FB.getLoginStatus(response => {
        if (response.status === 'connected') {
            FB.logout();
        }
    });

    gapi.load('auth2', function () {        
        gapi.auth2.init({
            client_id: googleClientId
        }).then(() => {
            var auth2 = gapi.auth2.getAuthInstance();
            auth2.signOut().then(() => {
                auth2.disconnect();
                window.location.pathname = 'logout.php';
            });
            
        });
    });
}

window.fbAsyncInit = function () {
    FB.init({
        appId: facebookAppId,
        cookie: true,
        xfbml: true,
        version: 'v8.0'
    });

    FB.AppEvents.logPageView();
    FB.Event.subscribe('xfbml.render', () => $(".loading").remove());
};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) { return; }
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function fbSignIn() {
    FB.getLoginStatus(function (response) {
        if (response.status === 'connected') {
            accessToken = response.authResponse.accessToken;
            postToPage({ access_token: accessToken });
        }
    });
}


function postToPage(valuesObj, address="") {
    let form = $(`<form action="${address}" method="POST"/>`);

    for (const [key, value] of Object.entries(valuesObj)) {
        form.append($(`<input type="hidden" name="${key}" />`).val(value));
    };

    form.appendTo($(document.body)).submit();
}