/*
  === NAVBAR ===
*/
$(document).ready(function () {
  $('.dropdown').dropdown({on: 'hover'})
})

/*
  === CSRF ===
*/
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
})
