'use strict';

(function ($) {

  $('form').submit(function () {
    var
      form = this,
      formData = new FormData();

    $(this).find('input[id*=user_photo]').each(function () {
      var input = $(this).get(0);

      if (input.type == 'file') {
        if (input.files.length > 0) {
          formData.append('upload[uploaded_file]', input.files[0]);
        }
      } else {
        if (input.name === 'user[photo][name]') {
          formData.append('upload[name]', input.value);
        } else if (input.name === 'user[photo][file]') {
          formData.append('upload[file]', input.value);
        }
      }
    });

    $.ajax({
      url: '/ajax/upload',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false
    }).done(function (data) {
      $("#user_photo_file").val(data.fileId);
      form.submit();
    }).fail(function (errors) {
      console.log(errors.responseJSON);
    });

    return false;
  });

}(jQuery));
