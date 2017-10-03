$(document).ready(function() {
  $('#buttomSearchDate').click(function(event) {
    let year =$('#year option:selected').val()
    let month =$('#month option:selected').val()
//    alert(month)
    $.ajax({
      url: 'includes/mod_cen/clases/informe.php',
      type: 'POST',
      dataType: 'json',
      data: {year:year}
    })
    .done(function() {
      console.log("success");
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });

  });
});
