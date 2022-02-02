<!DOCTYPE html>
<html>

<head>
    <title>@lang('pdf_estimate_label') - {{ $estimate->estimate_number }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        /* 
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        } */

        /* * RTL *
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        } */


        /* -- Header -- */

        .header table {
            border-collapse: collapse;
            width: 100%;
            line-height: 1;
        }

        .header table td {
            padding: 0;
            vertical-align: top;
        }

        .header table tr td:nth-child(2) {
            text-align: right;
        }

        .header-title {
            line-height: 1;
            padding: 0;
            margin: 0 0 16px;
        }

        .data-group {
            line-height: 1;
        }

        .data-group .label {
            font-weight: bold;
            margin-right: 10px;
        }



        /* -- information -- */

        .information table {
            border-collapse: collapse;
            width: 100%;
            line-height: 1;
        }

        .information table td {
            padding: 0;
            vertical-align: top;
        }

        .information table tr td:nth-child(2) {
            text-align: right;
        }

        .company-info {
            line-height: 1;
        }

        .customer-info {
            line-height: 1;
        }

        /* -- Items Table -- */
        .items-table {
            border-collapse: collapse;
            width: 100%;
            line-height: 1;
        }

        .items-table td,
        .items-table th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .items-table .item-table-heading-row {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .item-cell-table-hr {
            display: none;
        }

        /* -- Total table -- */
        .total-display-table {
            border-collapse: collapse;
            width: 200px;
            line-height: 1;
            text-align: right;
            float: right;
            margin-top: 16px;
        }

        .total-label {
            font-weight: bold;
            font-size: 18px;
        }

        .total-value {
            font-weight: bold;
        }

        /* -- Notes -- */
        .notes {
            display: block;
            width: 100%;
            line-height: 1;
        }

        .notes ul,
        .notes ol {
            padding: 6px 0 16px 16px;
            margin: 0;
        }


        /* -- Helpers -- */
        .text-primary {
            color: #5851DB;
        }

        .text-center {
            text-align: center
        }

        table .text-left {
            text-align: left;
        }

        table .text-right {
            text-align: right;
        }

        .border-0 {
            border: none;
        }

    </style>

    @if (App::isLocale('th'))
        @include('app.pdf.locale.th')
    @endif
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <table width="100%" cellspacing="0" border="0">
                <tr>
                    <td width="50%">
                        <div class="company-logo">
                            @if ($logo)
                                <img style="height: 50px;" src="{{ $logo }}" alt="Company Logo">
                            @else
                                <h1> {{ $estimate->customer->company->name }} </h1>
                            @endif
                        </div>
                    </td>

                    <td width="50%">
                        <h1 class="header-title">@lang('pdf_estimate_label')</h1>
                        <div>
                            <div class="data-group">
                                <label class="label">@lang('pdf_estimate_number'):</label>
                                <span class="data">{{ $estimate->estimate_number }}</span>
                            </div>
                            <div class="data-group">
                                <label class="label">@lang('pdf_estimate_date'):</label>
                                <span class="data">{{ $estimate->formattedEstimateDate }}</span>
                            </div>
                            <div class="data-group">
                                <label class="label">@lang('pdf_estimate_expire_date'):</label>
                                <span class="data">{{ $estimate->formattedExpiryDate }}</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="information">
            <table width="100%" cellspacing="0" border="0">
                <tr>
                    <td width="50%">
                        <div class="company-info">
                            <p>
                                <b>{{ $company_name }}</b>
                                <br>
                                {!! $company_address['address_street_1'] ? $company_address['address_street_1'] . '<br>' : '' !!}
                                {!! $company_address['address_street_2'] ? $company_address['address_street_2'] . '<br>' : '' !!}
                                {{ $company_address['state'] }} {{ $company_address['city'] }}
                                {{ $company_address['zip'] }}
                                <br>
                                {{ $company_address['phone'] }}
                            </p>
                        </div>
                    </td>
                    <td width="50%">
                        <div class="customer-info">
                            <p>
                                <b>{{ $customer->contact_name }}</b>
                                <br>
                                {!! $customer_billing['address_street_1'] ? $customer_billing['address_street_1'] . '<br>' : '' !!}
                                {!! $customer_billing['address_street_2'] ? $customer_billing['address_street_2'] . '<br>' : '' !!}
                                {{ $customer_billing['state'] }} {{ $customer_billing['city'] }}
                                {{ $customer_billing['zip'] }} <br>
                                {{ $customer_billing['phone'] }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        @include('app.pdf.estimate.partials.table')
        <div class="notes">
            @if ($notes)
                <p><b>@lang('pdf_notes')</b></p>
                {!! $notes !!}
            @endif
        </div>
    </div>
</body>

</html>
