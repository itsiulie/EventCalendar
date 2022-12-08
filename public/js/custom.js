(function ($) {
    'use strict';

    //ADD EVENT BUTTON
    $('#add_event').click(function(){
        $('#eventModal').modal('toggle');
    });

})(jQuery);

   // MODAL FORM VALIDATION
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
})()

