// Editing and creating settings
$('body').on('beforeSubmit', '#paramForm, #createSettingForm', function () {
    var form = $(this);
    if (form.find('.has-error').length) {
        return false;
    }
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(data){ 
            if (form.attr('id') == 'paramForm') {
                if (data.name == 'skin') {
                    location.reload();
                } else {
                    $('#modalContent').html(data.message);$('#' + data.name).text(data.value);
                }
            } else {
                $('#modalContent').html(data);
            } 
        }
    });
    return false;
});
function settings(label,field,url){
    $.ajax({
       type: 'POST',
       cache: false,
       url: url,
       data: {field: field},
       success: function(data) {
           $('#modalContent').html(data);
           $('#modal').modal('show').find('#modalTitle').text(label);
       }
    });
}
function createSetting(title,url){
    $.ajax({
       type: 'POST',
       cache: false,
       url: url,
       success: function(data) {
           $('#modalContent').html(data);
           $('#modal').modal('show').find('#modalTitle').text(title);
       }
    });
}

// conclusion of the confirm dialog in the style of bootstrap
yii.confirm = function (message, ok, cancel) {
    krajeeDialog.confirm(message, function (confirmed) {
        if (confirmed) {
            !ok || ok();
        } else {
            !cancel || cancel();
        }
    });
    return false;
}

// Update datepicker on created_at field after pjax ends
if ($('input[id$="created_at"]').length) {
    $(document).on('pjax:success', function() {
        $('input[id$="created_at"]').datepicker($.extend({}, {"dateFormat":"dd.mm.yy"}));
    });
}
if ($('input[id$="birth_date"]').length) {
    $(document).on('pjax:success', function() {
        $('input[id$="birth_date"]').datepicker($.extend({}, {"dateFormat":"dd.mm.yy"}));
    });
}