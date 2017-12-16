@extends('layouts.app')

@section('title', __('shop.title'))

@section('content')
    <div class="ui container page-content">

        <div class="ui fluid big steps" style="margin-bottom: 40px">
            <div class="active step" data-step="1">
                <i class="list icon"></i>
                <div class="content">
                    <div class="title">Étape 1</div>
                    <div class="description">Choississez le moyen de paiement</div>
                </div>
            </div>
            <div class="disabled step" data-step="2">
                <i class="money icon"></i>
                <div class="content">
                    <div class="title">Étape 2</div>
                    <div class="description">Choississez le montant</div>
                </div>
            </div>
            <div class="disabled step" data-step="3">
                <i class="payment icon"></i>
                <div class="content">
                    <div class="title">Étape 3</div>
                    <div class="description">Payez et recevez vos points</div>
                </div>
            </div>
        </div>

        <div class="ui four column grid">
            <div class="row" id="step-1">

                <div class="column price-table" data-payment-method="paypal">
                    <h3>@lang('shop.credit.add.method.paypal')</h3>
                    <p style="padding-top: 20px;">
                        <img src="{{ url('/img/logo-paypal.png') }}" alt="paypal logo" height="100">
                    </p>
                </div>

                <div class="column price-table" data-payment-method="dedipass">
                    <h3>@lang('shop.credit.add.method.dedipass')</h3>
                    <p style="padding-top: 20px;">
                        <img src="{{ url('/img/logo-dedipass.png') }}" alt="dedipass logo" height="100">
                    </p>
                </div>

                <div class="column price-table" data-payment-method="paysafecard">
                    <h3>@lang('shop.credit.add.method.paysafecard')</h3>
                    <p style="padding-top: 20px;">
                        <img src="{{ url('/img/logo-paysafecard-2.png') }}" alt="paysafecard logo" height="100">
                    </p>
                </div>

                <div class="column price-table" data-payment-method="hipayWallet">
                    <h3>@lang('shop.credit.add.method.hipay')</h3>
                    <p style="padding: 0px;">
                        <img src="{{ url('/img/logo-hipay.png') }}" alt="hipay logo" height="150">
                    </p>
                </div>

            </div>
        </div>

        <div class="ui four column grid" id="step-2" style="display: none;">
            <div class="row">

                <div class="row step-2-method" data-payment-method="paypal" style="display:none;">
                    <div class="ui grid">
                        <?php
                        foreach ($offers['PAYPAL'] as $money => $amount) {
                            echo '<div class="four wide column" style="padding: 5px;">';
                            echo '<a class="btn-pay" data-amount="' . $amount . '" data-money="' . $money . '" data-paypal-email="' . env('PAYPAL_EMAIL') . '">';
                            echo '<div style="float: left;">';
                            echo '<i class="icon euro" style="line-height: normal;"></i>';
                            echo '</div>';
                            echo '<div style="float: left;">';
                            echo '<h5>' . number_format($amount, 2, '.', ' ') . ' €</h5>';
                            echo '<span>Obtenez ' . number_format($money, 0, ',', ' ') . ' points</span>';
                            echo '</div>';
                            echo '</a>';
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <div class="ui two column grid">
                        <div class="row">
                            <div class="column">
                                <div class="ui info message">
                                    <p>
                                        @lang('shop.credit.add.paypal.info')
                                    </p>
                                </div>
                            </div>
                            <div class="column">
                                <a href="#" class="ui obsifight button disabled step3"
                                   data-payment-method="paypal" style="font-size: 20px;width: 100%;">
                                    <i class="icon paypal card"></i>
                                    <span>@lang('shop.credit.add.btn.pay.empty')</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row step-2-method" data-payment-method="hipayWallet" style="display:none;">
                    <div class="ui grid">
                        <?php
                        foreach ($offers['HIPAY'] as $money => $data) {
                            $amount = $data['amount'];
                            echo '<div class="four wide column" style="padding: 5px;">';
                            echo '<a class="btn-pay" data-amount="' . $amount . '" data-offer-data=\'' . json_encode(array('sign' => $data['data'][1], 'data' => $data['data'][0])) . '\'>';
                            echo '<div style="float: left;">';
                            echo '<i class="icon euro" style="line-height: normal;"></i>';
                            echo '</div>';
                            echo '<div style="float: left;">';
                            echo '<h5>' . number_format($amount, 2, '.', ' ') . ' €</h5>';
                            echo '<span>Obtenez ' . number_format($money, 0, ',', ' ') . ' points</span>';
                            echo '</div>';
                            echo '</a>';
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <div class="ui two column grid">
                        <div class="row">
                            <div class="column">
                                <div class="ui info message">
                                    <p>
                                        @lang('shop.credit.add.hipay.info')
                                    </p>
                                </div>
                            </div>
                            <div class="column">
                                <a href="#" class="ui obsifight button disabled step3"
                                   data-payment-method="hipayWallet" style="font-size: 20px;width: 100%;">
                                    <i class="icon paypal card"></i>
                                    <span>@lang('shop.credit.add.btn.pay.empty')</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column step-2-method" data-payment-method="paysafecard" style="display:none;width: 100%">
                    <div class="column" style="width: 40%;margin-left: auto;margin-right: auto;">

                        <div class="ui form">
                            <div class="field">
                                <label>@lang('shop.credit.add.paysafecard.amount')</label>
                                <input type="number" step="0.1" name="amount" value="10">
                            </div>
                        </div>

                        <div class="ui form" style="margin-top: 10px;margin-bottom: 10px;">
                            <div class="field">
                                <label>@lang('shop.credit.add.paysafecard.currency')</label>
                                <input type="text" name="currency" value="EUR" disabled>
                            </div>
                        </div>

                        <img src="{{ url('/img/logo-paysafecard.png') }}" alt="paysafecard logo" height="40">
                        <div style="float: right;">
                            <a href="#" class="ui obsifight button step3"
                               data-payment-method="paysafecard" style="font-size: 20px;width: 100%;">
                                <i class="icon euro"></i>
                                <span>@lang('shop.credit.add.btn.pay.empty')</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="row setup-content" id="step-3" style="display: none;">

            <div class="step-3-method" data-payment-method="hipayWallet" style="display:none;">

                <h2 class="ui header" style="margin: 20px;">
                    <i class="wpforms icon"></i>
                    <div class="content">
                        @lang('shop.credit.add.hipay.terms.title')
                        <div class="sub header">@lang('shop.credit.add.hipay.terms.subtitle')</div>
                    </div>
                </h2>

                <div class="ui checkbox" style="margin: 10px;">
                    <input type="checkbox" name="hipay_wallet_term_1">
                    <label> @lang('shop.credit.add.hipay.terms.first')</label>
                </div>

                <div class="ui checkbox" style="margin: 10px;">
                    <input type="checkbox" name="hipay_wallet_term_2">
                    <label> @lang('shop.credit.add.hipay.terms.second')</label>
                </div>

                <div class="ui checkbox" style="margin: 10px;">
                    <input type="checkbox" name="hipay_wallet_term_3">
                    <label> @lang('shop.credit.add.hipay.terms.third')</label>
                </div>

                <div class="ui info message">
                    <p>@lang('shop.credit.add.hipay.terms.infos')</p>
                </div>

                <div class="text-center">
                    <?php
                    echo "<form target='_blank' action='https://payment.hipay.com/index/form/' method='post'>";
                    echo "<input type='hidden' name='mode' value='MODE_C' />";
                    echo "<input type='hidden' name='website_id' value='" . env('HIPAY_WEBSITE_ID') . "' />";
                    echo "<input type='hidden' name='sign' value='' />";
                    echo "<input type='hidden' name='data' value='' />";
                    echo '<button type="submit" class="ui obsifight button disabled pay" data-payment-method="hipayWallet" style="font-size: 25px;">';
                    echo '<i class="icon credit card alternative"></i>';
                    echo '<span>' . __("shop.credit.add.btn.pay.empty") . '</span>';
                    echo '</button>';
                    echo "</form>";
                    ?>
                </div>

            </div>

            <div class="step-3-method" data-payment-method="paypal" style="display:none;">

                <h2 class="ui header" style="margin: 20px;">
                    <i class="wpforms icon"></i>
                    <div class="content">
                        @lang('shop.credit.add.paypal.terms.title')
                        <div class="sub header">@lang('shop.credit.add.paypal.terms.subtitle')</div>
                    </div>
                </h2>

                <div class="ui checkbox" style="margin: 10px;">
                    <input type="checkbox" name="paypal_term_1">
                    <label> @lang('shop.credit.add.paypal.terms.first')</label>
                </div>

                <div class="ui checkbox" style="margin: 10px;">
                    <input type="checkbox" name="paypal_term_2">
                    <label> @lang('shop.credit.add.paypal.terms.second')</label>
                </div>

                <div class="ui checkbox" style="margin: 10px;">
                    <input type="checkbox" name="paypal_term_3">
                    <label> @lang('shop.credit.add.paypal.terms.third')</label>
                </div>

                <div class="ui info message">
                    <p>@lang('shop.credit.add.paypal.terms.infos')</p>
                </div>

                <div class="text-center">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input name="currency_code" type="hidden" value="EUR"/>
                        <input name="shipping" type="hidden" value="0.00"/>
                        <input name="tax" type="hidden" value="0.00"/>
                        <input name="return" type="hidden" value="{{ url('/shop/credit/add') }}"/>
                        <input name="cancel_return" type="hidden" value="{{ url('/shop/credit/add/cancel') }}"/>
                        <input name="notify_url" type="hidden"
                               value="{{ url('/shop/credit/add/paypal/notification') }}"/>
                        <input name="cmd" type="hidden" value="_xclick"/>
                        <input name="business" type="hidden" value=""/>
                        <input name="amount" type="hidden" value=""/>
                        <input name="item_name" type="hidden"
                               value="@lang('shop.credit.add.paypal.details', ['username' => Auth::user()->username])"/>
                        <input name="no_note" type="hidden" value="1"/>
                        <input name="lc" type="hidden" value="FR"/>
                        <input name="custom" type="hidden" value="<?= Auth::user()->id ?>">
                        <input name="bn" type="hidden" value="PP-BuyNowBF"/>
                        <input type="hidden" name="cbt" value="@lang('shop.credit.add.paypal.return')">
                        <input type="hidden" name="charset" value="UTF-8">
                        <button type="submit" class="ui obsifight button disabled pay"
                                data-payment-method="paypal" style="font-size: 25px;">
                            <i class="fa fa-cc-paypal"></i>
                            <span>@lang('shop.credit.add.btn.pay.empty')</span>
                        </button>
                    </form>
                </div>

            </div>

            <div class="step-3-method" data-payment-method="dedipass" style="display:none;">
                <div data-dedipass="<?= env('DEDIPASS_PUBLIC_KEY') ?>">
                    <div class="ui info message">
                        <p>@lang('GLOBAL__LOADING')...</p>
                    </div>
                </div>
                <script src="//api.dedipass.com/v1/pay.js"></script>
            </div>

            <div class="step-3-method text-center" data-payment-method="paysafecard" style="display:none;">
                <div class="ui info message">
                    <p>@lang('shop.credit.add.paysafecard.confirm')</p>
                </div>
                <form method="POST" action="{{ url('/shop/credit/add/paysafecard/init') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="amount" value="10">
                    <input type="hidden" name="currency" value="EUR">
                    <input type="hidden" name="customer_id" class="form-control"
                           value="<?= Auth::user()->id ?>">
                    <button type="submit" class="ui obsifight button pay"
                            data-payment-method="paysafecard" style="font-size: 25px;">
                        <i class="fa fa-eur"></i>
                        <span>@lang('shop.credit.add.btn.pay.empty')</span>
                    </button>
                </form>
            </div>

        </div>

        <div class="ui divider"></div>

        <div class="ui full card">
            <div class="content">
                <div class="header">@lang('shop.credit.add.voucher')</div>
            </div>
            <div class="content">
                <form class="ui form" id="voucher" data-ajax method="post" action="{{ url('/shop/credit/add/voucher') }}">
                    <div class="field">
                        <label>@lang('shop.credit.add.voucher.code')</label>
                        <input type="text" name="code">
                    </div>
                </form>
            </div>
            <div class="extra right aligned content">
                <button onclick="$('#voucher').submit()" class="ui button">@lang('shop.credit.add.voucher.valid')</button>
            </div>
        </div>

    </div>
@endsection
@section('style')
    <style>
        div.full.card {
            width: 100%;
        }

        div.price-table {
            background: rgba(0, 0, 0, 0.03);
            margin: 30px 0;
            text-align: center;
            padding-bottom: 30px;
            border-left: #fff 1px solid;
        }

        div.price-table h3 {
            font-size: 25px;
            line-height: 25px;
            padding: 30px 0;
            border-bottom: rgba(0, 0, 0, 0.1) 2px solid;
            text-transform: uppercase;
            font-weight: 300;
            letter-spacing: normal;
            margin: 0 0 32px 0;
        }

        div.price-table p {
            color: #666;
            font-size: 36px;
            line-height: 36px;
            padding: 30px 0;
            font-weight: 400;
            width: 150px;
            height: 150px;
            padding-top: 53px;
            display: inline-block;
            background-color: rgba(0, 0, 0, 0.05);
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            margin-top: 0;
        }

        .price-table {
            border: 2px solid transparent;
            transition: border .2s, box-shadow .2s;
            border-left-width: 2px !important;
        }

        .price-table:hover {
            cursor: pointer;
            border-color: #ec9422;
            -moz-box-shadow: 5px 5px 5px -5px #656565;
            -webkit-box-shadow: 5px 5px 5px -5px #656565;
            -o-box-shadow: 5px 5px 5px -5px #656565;
            box-shadow: 5px 5px 5px -5px #656565;
            transition: border .2s, box-shadow .2s;
        }

        .btn-pay {
            -webkit-transition: border .4s;
            -moz-transition: border .4s;
            -ms-transition: border .4s;
            -o-transition: border .4s;
            transition: border .4s;
            border: 2px solid #DADADA;
            border-radius: 5px;
            padding: 14px 14px;
            display: inline-block;
            background: #FCFCFC;
            color: #777;
            margin-top: 5px;
            cursor: pointer;
            width: 100%;
            padding-left: 0px;
        }

        .btn-pay:hover,
        .btn-pay.active {
            border: 2px solid #ec9422;
        }

        .btn-pay.active h5,
        .btn-pay.active p {
            color: #ec9422;
        }

        .btn-pay i {
            font-size: 60px;
            color: #ec9422;
        }

        .btn-pay h5 {
            font-size: 20px;
            font-weight: bold;
            font-family: 'Lato';
            color: #777;
            margin-bottom: 8px;
        }

        .btn-pay span {
            font-size: 15px;
            font-weight: normal;
            color: #777;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            // =====
            // INIT : VARS
            // =====
            var paymentMethod = ''
            var amount = 0.0
            var infos = {}

            // =====
            // STEP 1 : CHOOSE PAYMENTS METHOD
            // =====
            $('.price-table').on('click', function (e) {
                var method = $(this)
                paymentMethod = method.attr('data-payment-method')

                // hide step
                $('#step-1').fadeOut(150, function () {
                    $('[data-step="1"]').removeClass('active').addClass('completed')
                    // display step 2
                    if (paymentMethod != 'dedipass') {
                        $('.step-2-method[data-payment-method="' + paymentMethod + '"]').fadeIn(150)
                        $('#step-2').fadeIn(150)
                        $('[data-step="2"]').removeClass('disabled').addClass('active')
                    } else {
                        // set step 2 as completed
                        $('a[href="#step-2"]').parent().removeClass('active').addClass('checked')
                        // display step 3
                        $('.step-3-method[data-payment-method="' + paymentMethod + '"]').fadeIn(150)
                        $('#step-3').fadeIn(150)
                        $('[data-step="3"]').removeClass('disabled').addClass('active')
                    }
                })
            })

            // =====
            // STEP 2 : CHOOSE PAYMENTS AMOUNT
            // =====

            // PAYPAL
            $('.step-2-method[data-payment-method="paypal"] .btn-pay').on('click', function (e) {
                var btn = $(this)
                amount = parseFloat(btn.attr('data-amount'))
                infos.money = parseFloat(btn.attr('data-money'))
                infos.paypalEmail = btn.attr('data-paypal-email')

                // edit classess
                $('.step-2-method[data-payment-method="paypal"] .btn-pay').removeClass('active')
                btn.addClass('active')

                // edit btn
                $('.step3[data-payment-method="paypal"]').removeClass('disabled')
                $('.step3[data-payment-method="paypal"] span').html('@lang('shop.credit.add.btn.pay')'.replace(':amount', amount))
            })
            // hipay-wallet
            $('.step-2-method[data-payment-method="hipayWallet"] .btn-pay').on('click', function (e) {
                var btn = $(this)
                amount = parseFloat(btn.attr('data-amount'))
                infos.offer = JSON.parse(btn.attr('data-offer-data'))

                // edit classess
                $('.step-2-method[data-payment-method="hipayWallet"] .btn-pay').removeClass('active')
                btn.addClass('active')

                // edit btn
                $('.step3[data-payment-method="hipayWallet"]').removeClass('disabled')
                $('.step3[data-payment-method="hipayWallet"] span').html('@lang('shop.credit.add.btn.pay')'.replace(':amount', amount))
            })
            // global
            $('.step3').on('click', function (e) {
                e.preventDefault()
                var btn = $(this)

                // hide step
                $('#step-2').fadeOut(150, function () {
                    $('[data-step="2"]').removeClass('active').addClass('completed')
                    // display step 2
                    if (paymentMethod == 'hipayWallet') {
                        // edit form
                        $('.step-3-method[data-payment-method="hipayWallet"] form input[name="sign"]').val(infos.offer.sign)
                        $('.step-3-method[data-payment-method="hipayWallet"] form input[name="data"]').val(infos.offer.data)
                    }
                    if (paymentMethod == 'paypal') {
                        $('.step-3-method[data-payment-method="paypal"] form input[name="amount"]').val(amount)
                        var itemName = $('.step-3-method[data-payment-method="paypal"] form input[name="item_name"]')
                        itemName.val(itemName.val().replace(':money', infos.money))
                        $('.step-3-method[data-payment-method="paypal"] form input[name="business"]').val(infos.paypalEmail)
                    }
                    if (paymentMethod == 'paysafecard') {
                        amount = parseFloat($('.step-2-method[data-payment-method="paysafecard"] input[name="amount"]').val())
                        infos.currency = $('.step-2-method[data-payment-method="paysafecard"] input[name="currency"]').val()
                        $('.step-3-method[data-payment-method="paysafecard"] form input[name="amount"]').val(amount)
                        $('.step-3-method[data-payment-method="paysafecard"] form input[name="currency"]').val(infos.currency)
                        // info
                        var alertDiv = $('.step-3-method[data-payment-method="paysafecard"] .ui.info.message p')
                        var alertContent = alertDiv.html()
                        amount = $('[data-payment-method="paysafecard"] input[name="amount"]').val()
                        amount = parseFloat(amount)
                        var credits = Math.round(amount * parseFloat(80))
                        alertDiv.html(alertContent.replace(':amount', amount).replace(':money', credits))
                    }
                    $('.step-3-method[data-payment-method="' + paymentMethod + '"]').fadeIn(150)
                    $('#step-3').fadeIn(150)
                    $('[data-step="3"]').removeClass('disabled').addClass('active')
                })
            })

            // =====
            // STEP 3 : PAY
            // =====

            // paypal terms
            $('input[name^="paypal_term_"]').on('change', function () {
                if ($('input[name="paypal_term_1"]:checked').length === 1 && $('input[name="paypal_term_2"]:checked').length === 1 && $('input[name="paypal_term_3"]:checked').length === 1)
                    $('.pay[data-payment-method="paypal"]').removeClass('disabled')
                else
                    $('.pay[data-payment-method="paypal"]').addClass('disabled')
            })

            // hipay wallet terms
            $('input[name^="hipay_wallet_term_"]').on('change', function () {
                if ($('input[name="hipay_wallet_term_1"]:checked').length === 1 && $('input[name="hipay_wallet_term_2"]:checked').length === 1 && $('input[name="hipay_wallet_term_3"]:checked').length === 1)
                    $('.pay[data-payment-method="hipayWallet"]').removeClass('disabled')
                else
                    $('.pay[data-payment-method="hipayWallet"]').addClass('disabled')
            })
        })
    </script>
@endsection