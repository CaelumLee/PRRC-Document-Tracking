$(document).ready(function(){
    $('#docu-type-table').DataTable({
        pagingType: "simple",
        dom: '<div>pt',
        pageLength: 15,
        language:{
            paginate:{
                previous: "<i class='material-icons'>chevron_left</i>",
                next: "<i class='material-icons'>chevron_right</i>"
            }
        }
    });
    $('.modal').modal();
});

$(document).on('click', '.edit', function(){
    var dataID = $(this).data('id');
    var docuType = $(this).data('name');
    $('#disabled').val(docuType);
});

$(document).on('click', '.delete', function(){
    var dataID = $(this).data('id');
    // $.ajax({
    //     type:'POST',
    //     url:'/jsonFile',
    //     data: {
    //       dataID : dataID,
    //       '_token' : '<?php echo csrf_token() ?>'
    //     },
    //     success:function(data){
    //       $("#File_to_place").html(data.File_Uploads);
    //     },
    //     fail:function(err){
    //       console.log(err);
    //     }
    // });
});