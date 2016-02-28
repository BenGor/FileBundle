/*
 * This file is part of the BenGorFileBundle bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

'use strict';

(function ($) {

  $('form[data-bengor-file-action]').submit(function (event) {
    event.preventDefault();

    var
      form = this,
      fileInput,
      formErrors = [];

    $(form).find('[data-bengor-file-type]').each(function () {
      var formData = new FormData();

      $(this).find(':input').map(function () {
        var input = this;

        if (input.type === 'file') {
          if (input.files.length > 0) {
            formData.append('file[uploaded_file]', input.files[0]);
          }
        } else {
          if (input.name.indexOf('name') > -1) {
            formData.append('file[name]', input.value);
          } else if (input.name.indexOf('file') > -1) {
            formData.append('file[file]', input.value);
            fileInput = input.name;
          }
        }
      });

      $.ajax({
        url: $(form).data('bengorFileAction'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false
      }).done(function (data) {
        $('input[name="' + fileInput + '"]').val(data.fileId);
      }).fail(function (errors) {
        formErrors = errors;
      });
    });
    console.log($('input[name="' + fileInput + '"]').val());
    form.submit();
  });

}(jQuery));
