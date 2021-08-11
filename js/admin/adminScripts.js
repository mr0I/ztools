jQuery(document).ready(function($) {

    /* woocommerce option_group settings */
    const rateDependentCheckbox = $('#ztools_is_rate_dependent');
    const optionGroupFields = $('.option_group_fields');
    const regularPriceInput = $('input#_regular_price');
    const salePriceInput = $('input#_sale_price');
    const optionGroup = $('.option_group');
    const currencyRateLabel =optionGroup.find('#currency_rate');
    const currencyRegularPriceLabel =optionGroup.find('#regular_currency_rate');
    const currencySalePriceLabel =optionGroup.find('#sale_currency_rate');


    if (rateDependentCheckbox.is(':checked'))  rateDependentChecked();
    rateDependentCheckbox.on('change' , function () {
        let is_checked = $(this).is(':checked');
        if (is_checked){
            rateDependentChecked();
        } else {
            rateDependentUnChecked();
        }
    });

    function rateDependentChecked() {
        optionGroupFields.fadeIn();
        regularPriceInput.attr('disabled' , true);
        salePriceInput.attr('disabled' , true);
    }
    function rateDependentUnChecked() {
        optionGroupFields.fadeOut();
        regularPriceInput.attr('disabled' , false);
        salePriceInput.attr('disabled' , false);
    }

    optionGroup.find('#ztools_currency_type').on('change' , function () {
        let currencyType = $(this).val();
        let regularPrice = optionGroup.find('#ztools_currency_input').val();
        let salePrice = optionGroup.find('#ztools_special_currency_input').val();

        if (currencyType !== '0') {
            fetch(PlanetAdminAjax.ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
                body: new URLSearchParams({
                    action: 'getCurrencyRate',
                    security : PlanetAdminAjax.security,
                    currencyType: currencyType,
                    regularPrice: Number(regularPrice),
                    salePrice: Number(salePrice)
                })
            }).then((resp) => resp.json())
                .then(function(res) {
                    if (res.result === 'Done'){
                        currencyRateLabel.find('code').text(res.currency_rate);
                        currencyRegularPriceLabel.find('code').text(res.regular_price);
                        currencySalePriceLabel.find('code').text(res.sale_price);
                    } else {
                        alert('خطا در دریافت اطلاعات!!!');
                    }
                })
                .catch(function(error) {
                    console.log(JSON.stringify(error));
                });
        } else {
            currencyRateLabel.find('code').html('---');
            currencyRegularPriceLabel.find('code').text('---');
            currencySalePriceLabel.find('code').text('---');
        }
    });

    optionGroup.find('#ztools_currency_input').on('input' , function () {
        let currencyInput = $(this).val();
        let currencyType = optionGroup.find('#ztools_currency_type').val();

        if (currencyType !== '0') {
            fetch(PlanetAdminAjax.ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
                body: new URLSearchParams({
                    action: 'getRegularPrice',
                    security : PlanetAdminAjax.security,
                    currencyType: currencyType,
                    currencyInput: currencyInput
                })
            }).then((resp) => resp.json())
                .then(function(res) {
                    if (res.result === 'Done'){
                        currencyRegularPriceLabel.find('code').text(res.regular_price);
                    } else {
                        alert('خطا در دریافت اطلاعات!!!');
                    }
                })
                .catch(function(error) {
                    console.log(JSON.stringify(error));
                });
        } else {
            currencyRegularPriceLabel.find('code').html('---');
        }

    });

    optionGroup.find('#ztools_special_currency_input').on('input' , function () {
        let currencyInput = $(this).val();
        let currencyType = optionGroup.find('#ztools_currency_type').val();

        if (currencyType !== '0') {
            fetch(PlanetAdminAjax.ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
                body: new URLSearchParams({
                    action: 'getSalePrice',
                    security : PlanetAdminAjax.security,
                    currencyType: currencyType,
                    currencyInput: currencyInput
                })
            }).then((resp) => resp.json())
                .then(function(res) {
                    if (res.result === 'Done'){
                        currencySalePriceLabel.find('code').text(res.sale_price);
                    } else {
                        alert('خطا در دریافت اطلاعات!!!');
                    }
                })
                .catch(function(error) {
                    console.log(JSON.stringify(error));
                });
        } else {
            currencySalePriceLabel.find('code').html('---');
        }
    });
    /* woocommerce option_group settings */

});
