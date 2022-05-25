<!DOCTYPE html>
<html>

<head>
    <title>@lang('pdf_estimate_label') - {{ $estimate->estimate_number }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 30px;
            min-height: 100vh;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px 30px 80px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        /* -- Header -- */

        .header table {
            border-collapse: collapse;
            width: 100%;
            line-height: 0.8;
        }

        .header table td {
            padding: 0;
            vertical-align: top;
        }

        .header table tr td:nth-child(2) {
            text-align: right;
        }

        .header-title {
            line-height: 0.8;
            padding: 0;
            margin: 0 0 16px;
        }

        .data-group {
            line-height: 0.8;
        }

        .data-group .label {
            font-weight: bold;
            margin-right: 10px;
        }



        /* -- information -- */

        .information {
            border-top: 1px solid #eee;
            padding-top: 16px;
            padding-bottom: 20px;
            margin-top: 16px
        }

        .information table td {
            padding: 0;
            vertical-align: top;
        }

        .information table tr td:nth-child(3) {
            text-align: left;
        }

        .company-info {
            line-height: 0.8;
        }

        .customer-info {
            line-height: 0.8;
        }

        /* -- Items Table -- */
        .items-table {
            border-collapse: collapse;
            width: 100%;
            line-height: 0.8;
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

        .item-description {
            color: #595959;
            font-size: 13px;
        }

        /* -- Total table -- */
        .total-display-table {
            border-collapse: collapse;
            width: 200px;
            line-height: 0.8;
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
            line-height: 0.8;
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

        .py-2 {
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .py-8 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .py-3 {
            padding: 3px 0;
        }

        .pr-20 {
            padding-right: 20px;
        }

        .pr-10 {
            padding-right: 10px;
        }

        .pl-20 {
            padding-left: 20px;
        }

        .pl-10 {
            padding-left: 10px;
        }

        .pl-0 {
            padding-left: 0;
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
                    <td width="33.33%">
                        <div class="company-info">
                            {!! $company_address !!}
                        </div>
                    </td>
                    <td width="33.33%"></td>
                    <td width="33.33%">
                        <div class="customer-info">
                            @if ($billing_address)
                                {!! $billing_address !!}
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="items">
            @include('app.pdf.estimate.partials.table')
        </div>
        <div class="notes">
            <table width="100%" cellspacing="0" border="0">
                <tr>
                    <td width="33.33%">
                        <p style="margin: 16px 0;"><b>@lang('pdf_notes')</b></p>
                        @if ($notes)
                            {!! $notes !!}
                        @else
                            <p>-</p>
                        @endif
                    </td>
                    <td width="33.33%"></td>
                    <td width="33.33%"></td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
