$(document).ready(function () {
  $('#closepopup').click(function () {
    $('.appdownload').removeClass('showpopup')
    hidepopup()
    $('#scrollToTop').addClass('goingDown')
  })

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
      $('.appdownload').addClass('showpopup')
    }, 2000)
  }
})
function hidepopup () {
  sessionStorage.setItem('showpopup', 0)
}
