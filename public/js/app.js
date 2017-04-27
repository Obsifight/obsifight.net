/*
=== GLOBAL ===
*/
$(document).ready(function () {
  $('.dropdown').dropdown({on: 'hover'})
  $('.ui.checkbox').checkbox()
})

function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}

/*
  === CSRF ===
*/
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
})
