/*
  AJAX FORM SYSTEM
*/
function initAjaxForms() {
  $('form[data-ajax]').unbind('submit')
  $('form[data-ajax]').on('submit', function (e) {
    e.preventDefault()
    var form = $(this)
    var btn = form.find('[type="submit"]')

    // Remove (optionnal) message
    form.find('.ajax-message').remove()
    form.prepend('<div class="ajax-message"></div>')

    // Add loader
    form.addClass('dimmable')
    form.prepend('<div class="ui active inverted dimmer"><div class="ui text loader">' + localization.loading + '</div></div>')

    // Get data
    var data = form.serializeArray()

    // Submit data
    $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: data,
      contentType: 'application/json',
      dataType: 'json',
      success: function (data) {
        if (data.status)
          displaySuccess(form, data.success)
        else
          displayError(form, data.error)
        removeDimmer(form)
      },
      statusCode: {
        404: function () {
          displayError(form, localization.error.notfound)
          removeDimmer(form)
        },
        403: function () {
          displayError(form, localization.error.forbidden)
          removeDimmer(form)
        },
        500: function () {
          displayError(form, localization.error.internal)
          removeDimmer(form)
        },
        400: function () {
          displayError(form, localization.error.badrequest)
          removeDimmer(form)
        },
        405: function () {
          displayError(form, localization.error.methodnotallowed)
          removeDimmer(form)
        }
      },
      error: function () {
        displayError(form, localization.error.internal)
        removeDimmer(form)
      }
    })

    // Remove dimmer
    function removeDimmer(form) {
      form.find('.dimmer').remove()
      form.removeClass('dimmable')
    }
    // Display messages
    function displaySuccess(form, message) {
      form.find('.ajax-message').html('<div class="ui positive message"><div class="header">' + localization.success.title + '</div><p>' + message + '</p></div><div class="ui divider"></div>')
    }
    function displayError(form, message) {
      form.find('.ajax-message').html('<div class="ui negative message"><div class="header">' + localization.error.title + '</div><p>' + message + '</p></div><div class="ui divider"></div>')
    }
  })
}
initAjaxForms()
