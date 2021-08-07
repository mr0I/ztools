jQuery(document).ready(function($) {

    const rateDependentCheckbox = $('#ztools_is_rate_dependent');
    const optionGroupFields = $('.option_group_fields');

    if (rateDependentCheckbox.is(':checked'))  optionGroupFields.fadeIn();
    rateDependentCheckbox.on('change' , function () {
        let is_checked = $(this).is(':checked');
        if (is_checked){
            optionGroupFields.fadeIn();
        } else {
            optionGroupFields.fadeOut();
        }
    })
});
