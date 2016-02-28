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
      fileInputs = [],
      formErrors = [],
      $benGorFileTypes = $(form).find('[data-bengor-file-type]');

    $benGorFileTypes.each(function (index) {
      var formData = new FormData();

      $(this).find(':input').map(function () {
        if (this.type === 'file') {
          if (this.files.length > 0) {
            formData.append('file[uploaded_file]', this.files[0]);
          }
        } else {
          if (this.name.indexOf('name') > -1) {
            formData.append('file[name]', this.value);
          } else if (this.name.indexOf('file') > -1) {
            formData.append('file[file]', this.value);
            fileInputs[index] = this.name;
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
        $('input[name="' + fileInputs[index] + '"]').val(data.fileId);
        if (index + 1 === $benGorFileTypes.size()) {
          form.submit();
        }
      }).fail(function (errors) {
        formErrors = errors;
      });
    });
  });

}(jQuery));
