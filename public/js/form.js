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
    var data = objectifyForm(form.serializeArray())

    // ReCaptcha
    if (typeof grecaptcha !== "undefined" && typeof grecaptcha.getResponse() !== "undefined")
      data['g-recaptcha-response'] = grecaptcha.getResponse()

    // Submit data
    $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: JSON.stringify(data),
      contentType: 'application/json',
      dataType: 'json',
      success: function (response) {
        if (response.status) {
          if (form.attr('data-ajax-custom-callback'))
            window[form.attr('data-ajax-custom-callback')](data, response)
          displaySuccess(form, response.success)
          if (response.redirect)
            window.location = response.redirect
        } else {
          displayError(form, response.error)
        }
      },
      statusCode: {
        404: function () {
          displayError(form, localization.error.notfound)
        },
        403: function () {
          displayError(form, localization.error.forbidden)
        },
        500: function () {
          displayError(form, localization.error.internal)
        },
        400: function () {
          displayError(form, localization.error.badrequest)
        },
        405: function () {
          displayError(form, localization.error.methodnotallowed)
        }
      },
      error: function () {
        displayError(form, localization.error.internal)
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
      removeDimmer(form)
    }
    function displayError(form, message) {
      form.find('.ajax-message').html('<div class="ui negative message"><div class="header">' + localization.error.title + '</div><p>' + message + '</p></div><div class="ui divider"></div>')
      removeDimmer(form)
      if (typeof grecaptcha !== "undefined")
        grecaptcha.reset()
    }
  })
}
initAjaxForms()


function objectifyForm(formArray) { //serialize data function
  var returnArray = {};
  for (var i = 0; i < formArray.length; i++){
    returnArray[formArray[i]['name']] = formArray[i]['value'];
  }
  return returnArray;
}
