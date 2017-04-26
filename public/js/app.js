/*
=== GLOBAL ===
*/
$(document).ready(function () {
  $('.dropdown').dropdown({on: 'hover'})
  $('.ui.checkbox').checkbox()
})

/*
  === CSRF ===
*/
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
})
