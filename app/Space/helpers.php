<?php

use Crater\Models\CompanySetting;
use Crater\Models\Currency;
use Crater\Models\CustomField;
use Crater\Models\Setting;
use Illuminate\Support\Str;

/**
 * Get company setting
 *
 * @param $company_id
 * @return string
 */
function get_company_setting($key, $company_id)
{
    if (\Storage::disk('local')->has('database_created')) {
        return CompanySetting::getSetting($key, $company_id);
    }
}

/**
 * Get app setting
 *
 * @param $company_id
 * @return string
 */
function get_app_setting($key)
{
    if (\Storage::disk('local')->has('database_created')) {
        return Setting::getSetting($key);
    }
}

/**
 * Get page title
 *
 * @param $company_id
 * @return string
 */
function get_page_title($company_id)
{
    $routeName = Route::currentRouteName();

    $pageTitle = null;
    $defaultPageTitle = 'Crater - Self Hosted Invoicing Platform';

    if (\Storage::disk('local')->has('database_created')) {
        if ($routeName === 'customer.dashboard') {
            $pageTitle = CompanySetting::getSetting('customer_portal_page_title', $company_id);

            return $pageTitle ? $pageTitle : $defaultPageTitle;
        }

        $pageTitle = Setting::getSetting('admin_page_title');

        return $pageTitle ? $pageTitle : $defaultPageTitle;
    }
}

/**
 * Set Active Path
 *
 * @param $path
 * @param string $active
 * @return string
 */
function set_active($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

/**
 * @param $path
 * @return mixed
 */
function is_url($path)
{
    return call_user_func_array('Request::is', (array)$path);
}

/**
 * @param string $type
 * @return string
 */
function getCustomFieldValueKey(string $type)
{
    switch ($type) {
        case 'Input':
            return 'string_answer';

        case 'TextArea':
            return 'string_answer';

        case 'Phone':
            return 'number_answer';

        case 'Url':
            return 'string_answer';

        case 'Number':
            return 'number_answer';

        case 'Dropdown':
            return 'string_answer';

        case 'Switch':
            return 'boolean_answer';

        case 'Date':
            return 'date_answer';

        case 'Time':
            return 'time_answer';

        case 'DateTime':
            return 'date_time_answer';

        default:
            return 'string_answer';
    }
}

/**
 * @param $money
 * @return formated_money
 */
function format_money_pdf($money, $currency = null)
{
    $money = $money / 100;

    if (!$currency) {
        $currency = Currency::findOrFail(CompanySetting::getSetting('currency', 1));
    }

    $format_money = number_format(
        $money,
        $currency->precision,
        $currency->decimal_separator,
        $currency->thousand_separator
    );

    $currency_with_symbol = '';
    if ($currency->swap_currency_symbol) {
        $currency_with_symbol = $format_money . '<span>' . $currency->symbol . '</span>';
    } else {
        $currency_with_symbol = '<span>' . $currency->symbol . '</span>' . $format_money;
    }

    return $currency_with_symbol;
}


/**
 * It takes a number, converts it to a string, splits it into two parts, converts the first part to
 * Thai, converts the second part to Thai, and then concatenates the two parts together.
 *
 * @param price The price you want to convert to Thai string.
 *
 * @return the string representation of the number in Thai.
 */
function convert_price_to_thai_string_pdf($price)
{

    $price = $price / 100;

    $amount_number = number_format($price, 2, '.', '');
    $pt = strpos($amount_number, '.');
    $number = $fraction = '';
    if ($pt === false) {
        $number = $amount_number;
    } else {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }

    $ret = '';
    $baht = read_number_thai($number);
    if ($baht != '') {
        $ret .= $baht . 'บาท';
    }

    $satang = read_number_thai($fraction);
    if ($satang != '') {
        $ret .= $satang . 'สตางค์';
    } else {
        $ret .= 'ถ้วน';
    }
    return $ret;
}

/**
 * It takes a number and returns a string with the Thai equivalent
 *
 * @param number The number to be converted.
 *
 * @return the number in Thai.
 */
function read_number_thai($number)
{
    $position_call = ['แสน', 'หมื่น', 'พัน', 'ร้อย', 'สิบ', ''];
    $number_call = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
    $number = $number + 0;
    $ret = '';
    if ($number == 0) {
        return $ret;
    }
    if ($number > 1000000) {
        $ret .= readNumber(intval($number / 1000000)) . 'ล้าน';
        $number = intval(fmod($number, 1000000));
    }

    $divider = 100000;
    $pos = 0;
    while ($number > 0) {
        $d = intval($number / $divider);
        $ret .= $divider == 10 && $d == 2 ? 'ยี่' : ($divider == 10 && $d == 1 ? '' : ($divider == 1 && $d == 1 && $ret != '' ? 'เอ็ด' : $number_call[$d]));
        $ret .= $d ? $position_call[$pos] : '';
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}

/**
 * @param $string
 * @return string
 */
function clean_slug($model, $title, $id = 0)
{
    // Normalize the title
    $slug = Str::upper('CUSTOM_' . $model . '_' . Str::slug($title, '_'));

    // Get any that could possibly be related.
    // This cuts the queries down by doing it once.
    $allSlugs = getRelatedSlugs($model, $slug, $id);

    // If we haven't used it before then we are all good.
    if (!$allSlugs->contains('slug', $slug)) {
        return $slug;
    }

    // Just append numbers like a savage until we find not used.
    for ($i = 1; $i <= 10; $i++) {
        $newSlug = $slug . '_' . $i;
        if (!$allSlugs->contains('slug', $newSlug)) {
            return $newSlug;
        }
    }

    throw new \Exception('Can not create a unique slug');
}

function getRelatedSlugs($type, $slug, $id = 0)
{
    return CustomField::select('slug')->where('slug', 'like', $slug . '%')
        ->where('model_type', $type)
        ->where('id', '<>', $id)
        ->get();
}

function respondJson($error, $message)
{
    return response()->json([
        'error' => $error,
        'message' => $message
    ], 422);
}
