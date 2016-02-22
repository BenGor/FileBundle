'use strict';

(function ($) {

  $('form[name=upload]').submit(function () {
    var
      form = this,
      formData = new FormData();

    $(this).find('input').each(function () {
      var input = $(this).get(0);

      if (input.type == 'file') {
        if (input.files.length > 0) {
          formData.append(input.name, input.files[0]);
        }
      } else {
        formData.append(input.name, input.value);
      }
    });

    $.ajax({
      url: '/ajax/upload',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false
    }).done(function (data) {
      console.log(data);

      form.submit();
    }).fail(function (errors) {
      console.log(errors.responseJSON);
    });

    return false;
  });

}(jQuery));
