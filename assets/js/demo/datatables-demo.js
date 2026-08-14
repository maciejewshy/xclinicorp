// Call the dataTables jQuery plugin
$(document).ready(function() {
   $('#dataTable').DataTable({
     order:['0', 'desc']
   });

  $('#dtSetor').DataTable({
     iDisplayLength:4,
     aLengthMenu:[2,3,4,5,7,10],
     order:['0', 'desc']
  });

  $('#dtMensagem').DataTable({
     iDisplayLength:10,
     aLengthMenu:[2,3,4,5,7,10,15,20],
     order:['0', 'desc']
  });
});