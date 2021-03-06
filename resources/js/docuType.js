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
    $('#docutype_id').val(dataID);
});

$(document).on('click', '.delete', function(){
    var dataID = $(this).data('id');
    var docuType = $(this).data('name');
    var isDisabled = $(this).data('is_disabled');
    if(isDisabled == 0){
        var p = 'Disable ' + docuType + ' in the lists';
    }
    else{
        var p = 'Enable ' + docuType + ' in the lists';
    }
    $('#title-placeholder').text(p);
    $('#docutype_id_disable').val(dataID);
});