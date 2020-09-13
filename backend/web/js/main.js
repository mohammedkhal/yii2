$(function () {
    $('#updateButton').click(function () {
        $('#updateModal').modal('show')
            .find('#updateContent')
            .load($(this).attr('value'));
    });
});
$(function(){
    $('#modalButton').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
    });
});
$(function(){
    $('#viewButton').click(function(){
        $('#viewModal').modal('show')
        .find('#viewContent')
        .load($(this).attr('value'));
    });
});

