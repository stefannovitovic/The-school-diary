$(document).ready(function(){
    $("#validation_form").validate({
    rules: {
      dtime: 'required',
      reason: 'required',
    },
    messages: {
      dtime: 'Izaberite datum',
      reason: 'Navedite razlog zakazivanja',
    },
    submitHandler: function(form) {
      form.submit();
    }
  });




})