<?php
/**
 *
 */
function abort404()
{
    echo "Страница не найдена";
    die;
}
function dump($data)
{
     echo "<pre>";
       print_r($data);
     echo "</pre>";
     die;
}

/**
 * Редирект
 * @param $url
 * @param bool $permanent
 */
if(!function_exists('redirect')) {
    function redirect($url, $permanent = false)
    {
        if (headers_sent() === false) {
            header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
        }

        exit();
    }
}
function _hpswd($pswd)
{
    return hash("sha256", $pswd);
}
function dbhelper($type, $db, int $id)
{
    $obj = new \App\Models\Country($db);
    switch($type) {
        case 'region':
             $obj = new \App\Models\Region($db);
            break;
        case  'city':
            $obj = new \App\Models\City($db);
            break;
    }

    return $obj->getItem($id);
}