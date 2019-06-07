var popUpContent = `
    <div class="appdownload">
        <img src="{{path}}/assets/images/Logo-small.png" style="margin-right:5px;"/>
        <div class="text_sc">
            <h5>Install Aplikasi Baru Kami!</h5> 
            <div style="padding-top: 5px; font-size: 11.5px;">
                Dapatkan pinjaman sampai dengan 8 juta rupiah.
            </div>
        </div>
        <button type="button" class="btn btn-secondary app-button" onClick="window.open('https://go.onelink.me/app/StaticBannerDownloadButton');">Download</button>  
        <a href="#" class="close" style="line-height: 1.25;" id="closepopup">x</a>
    </div>
`
function initPopup(){
    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i)
        },
        any: function () {
            return isMobile.Android()
        }
    }
    if (isMobile.Android() && sessionStorage.getItem('showpopup') === null) {
        setTimeout(function () {
            jQuery('.appdownload').addClass('showpopup')
        }, 2000)
    }
}

function initPopUpBanner(staticPath){
    jQuery('#page').after(popUpContent.replace("{{path}}", staticPath));
    jQuery('#closepopup').click(function () {
        jQuery('.appdownload').removeClass('showpopup');
        hidepopup();
    })
    initPopup();
}


function hidepopup () {
    sessionStorage.setItem('showpopup', 0)
}