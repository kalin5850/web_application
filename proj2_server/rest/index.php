<?php
$request_uri = $_SERVER['REQUEST_URI'];
$doc_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
$dirs = explode(DIRECTORY_SEPARATOR, __DIR__);
array_pop($dirs); // remove last element
$project_root = implode('/', $dirs) . '/';
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '0'); // would mess up response
ini_set('log_errors', 1);
// the following file needs to exist, be accessible to apache
// and writable (chmod 777 php-server-errors.log)
ini_set('error_log', $project_root . 'php-server-errors.log');
set_include_path($project_root);
// app_path is the part of $project_root past $doc_root
$app_path = substr($project_root, strlen($doc_root));
// project uri is the part of $request_uri past $app_path, not counting its last /
$project_uri = substr($request_uri, strlen($app_path) - 1);
$parts = explode('/', $project_uri);
// like  /rest/product/1 ;
//     0    1     2    3    

require_once('model/database.php');
require_once('model/product_db.php');
require_once('model/order_db.php');
require_once('model/day_db.php');
require_once('model/initial_db.php');
$server = $_SERVER['HTTP_HOST'];
$method = $_SERVER['REQUEST_METHOD'];
$proto = isset($_SERVER['HTTPS']) ? 'https:' : 'http:';
$url = $proto . '//' . $server . $request_uri;
$resource = trim($parts[2]);
$id = $parts[3];
error_log('starting REST server request, method=' . $method . ', uri = ...'. $project_uri);

switch ($resource) {
    // Access the specified product
    case 'products':
        error_log('request at case product');
        switch ($method) {
            case 'GET':
                handle_get_product($id);
                break;
            case 'POST':
                handle_post_product($url);
                break;
            default:
                $error_message = 'bad HTTP method : ' . $method;
                include_once('errors/server_error.php');
                server_error(405, $error_message);
                break;
        }
        break;
    case 'day':
        error_log('request at case day');
        switch ($method) {
            case 'GET':
                // TODO: get current day from DB
                $day = get_system_day(); //6;
                handle_get_day($day);
                break;
            case 'POST':
                $new_day = handle_post_day();
                //TODO: set new day in DB
                break;
            default:
                $error_message = 'bad HTTP method : ' . $method;
                include_once('errors/server_error.php');
                server_error(405, $error_message);
                break;
        }
        break;
    case 'orders':
        error_log('request at case orders');
        switch ($method) {
            case 'GET':
                if($id != ''){
                    handle_get_order($id);
                }else{
                    handle_get_all_order();
                }
                break;
            case 'POST':
                $deliveryDay = rand(1, 2);
                handle_post_order($url, $deliveryDay);
                break;
            default:
                $error_message = 'bad HTTP method : ' . $method;
                include_once('errors/server_error.php');
                server_error(405, $error_message);
                break;
        }
        break;
    case 'initial':
        switch ($method) {
            case 'POST':
                handle_initial_db();
                break;
            default:
                $error_message = 'bad HTTP method : ' . $method;
                include_once('errors/server_error.php');
                server_error(405, $error_message);
                break;
        }
        break;
    default:
        $error_message = 'Unknown REST resource: ' . $resource;
        include_once('errors/server_error.php');
        server_error(400, $error_message);
        break;
}

function handle_initial_db(){
    initial_db();
}

function handle_get_product($product_id) {
    $product = get_product($product_id);
    $data = json_encode($product);
    error_log('hi from handle_get_product');
    echo $data;
}

function handle_post_product($url) {
    $bodyJson = file_get_contents('php://input');
    error_log('Server saw post data' . $bodyJson);
    $body = json_decode($bodyJson, true);
    try {
        $product_id = add_product($body['categoryID'], $body['productCode'], 
                $body['productName'], $body['description'], $body['listPrice'],
                $body['discountPercent']);
        // return new URI in Location header
        $locHeader = 'Location: ' . $url . $product_id;
        //header($locHeader, true, 201);  // needs 3 args to set code 201
        echo $locHeader;
        error_log('hi from handle_post_product, header = ' . $locHeader);
    } catch (PDOException $e) {
        $error_message = 'Insert failed: ' . $e->getMessage();
        include_once('errors/server_error.php');
        server_error(400, $error_message);
    }
}

function handle_get_day($day) {
    error_log('rest server in handle_get_day, day = ' . $day);
    echo $day;
}

function handle_post_day() {
    error_log('rest server in handle_post_day');
    $day = file_get_contents('php://input'); // just a digit string
    error_log('Server saw POSTed day = ' . $day);
    $body = json_decode($day, true);
    try {
        update_system_day($body['day']);
        $order = get_undelivered_order();
        for ($i=0; $i<count($order);$i++) {
            $order_items = get_order_items($order[$i]['orderID']);
            for ($j=0; $j<count($order_items);$j++) {
                if($order_items[$j]['productID']==11){
                    $item1 = array('productID'=>$order_items[$j]['productID'], 'quantity'=>$order_items[$j]['quantity']);
                }else if($order_items[$j]['productID']==12){
                    $item2= array('productID'=>$order_items[$j]['productID'], 'quantity'=>$order_items[$j]['quantity']);
                }
            }
            $order_json[$i] = array('orderID'=> $order[$i]['orderID'],
                                    'items' => array($item1, $item2));
        }
        $data = json_encode($order_json);
        update_delivery_status();
        error_log('hi from handle_get_undelivered_order');
        echo $data;
    } catch (PDOException $e) {
        $error_message = 'Insert failed: ' . $e->getMessage();
        include_once('errors/server_error.php');
        server_error(400, $error_message);
    }
}

function handle_get_all_order() {
    $order = get_all_pizza_order();
    for ($i=0; $i<count($order);$i++) {
        $order_items = get_order_items($order[$i]['orderID']);
        for ($j=0; $j<count($order_items);$j++) {
            if($order_items[$j]['productID']==11){
                $item1 = array('productID'=>$order_items[$j]['productID'], 'quantity'=>$order_items[$j]['quantity']);
            }else if($order_items[$j]['productID']==12){
                $item2= array('productID'=>$order_items[$j]['productID'], 'quantity'=>$order_items[$j]['quantity']);
            }
        }
        $order_json[$i] = array('customerID'=> $order[$i]['customerID'],
                                'orderID'=> $order[$i]['orderID'],
                                'delivered'=> transStatus($order[$i]['status']),
                                'deliveryDay'=> $order[$i]['deliveryDay'],
                                'items' => array($item1, $item2));
    }
    $data = json_encode($order_json);
    //update_delivery_status();
    error_log('hi from handle_get_all_pizza_order');
    echo $data;
}

function handle_get_order($order_id) {
    //update_delivery_status();
    $order = get_order($order_id);
    $order_items = get_order_items($order_id);
    for ($i=0; $i<count($order_items);$i++) {
        if($order_items[$i]['productID']==11){
            $item1 = array('productID'=>$order_items[$i]['productID'], 'quantity'=>$order_items[$i]['quantity']);
        }else if($order_items[$i]['productID']==12){
            $item2= array('productID'=>$order_items[$i]['productID'], 'quantity'=>$order_items[$i]['quantity']);
        }
    }

    $order_json = array('customerID'=> $order['customerID'], 'orderID'=> $order['orderID'],
                        'delivered'=> transStatus($order['status']), 'items' => array($item1, $item2));
    $data = json_encode($order_json);
    error_log('hi from handle_get_order');
    echo $data;
}

function handle_post_order($url, $deliveryDay) {
    $bodyJson = file_get_contents('php://input');
    error_log('Server saw post data' . $bodyJson);
    $body = json_decode($bodyJson, true);
    try {
        $current_day = 'now()'; //get_system_day();
        $order_id = add_order($body['customerID'], $current_day, $deliveryDay);
        for ($i=0; $i<count($body['items']);$i++) {
            $product_detail = get_product($body['items'][$i]['productID']);
            $item_price = $product_detail['listPrice'];
            $discount = $product_detail['discountPercent'];
            add_order_item($order_id, $body['items'][$i]['productID'], $item_price, $discount, $body['items'][$i]['quantity']);
        }

        // return new URI in Location header
        $location_url = $url . '/' . $order_id;
        echo $location_url;

    } catch (PDOException $e) {
        $error_message = 'Insert failed: ' . $e->getMessage();
        include_once('errors/server_error.php');
        server_error(400, $error_message);
    }
}

function handle_undelivered_order(){
    $order = get_undelivered_order();
    for ($i=0; $i<count($order);$i++) {
        $order_items = get_order_items($order[$i]['orderID']);
        for ($j=0; $j<count($order_items);$j++) {
            if($order_items[$j]['productID']==11){
                $item1 = array('productID'=>$order_items[$j]['productID'], 'quantity'=>$order_items[$j]['quantity']);
            }else if($order_items[$j]['productID']==12){
                $item2= array('productID'=>$order_items[$j]['productID'], 'quantity'=>$order_items[$j]['quantity']);
            }
        }
        $order_json[$i] = array('customerID'=> $order[$i]['customerID'],
            'orderID'=> $order[$i]['orderID'],
            'delivered'=> transStatus($order[$i]['status']),
            'deliveryDay'=> $order[$i]['deliveryDay'],
            'items' => array($item1, $item2));
    }
    $data = json_encode($order_json);
    update_delivery_status();
    error_log('hi from handle_get_all_pizza_order');
    echo $data;
}

// as in main.php
/*function display_db_error($error_message) {
    include_once('errors/server_error.php');
    server_error(400, $error_message);
    exit;
}*/

// define this error function for server use (used in product_db.php, etc.)
// The error message is put in the server log
function display_db_error($error_message) {
    include_once('errors/server_error.php');
    server_error(500, $error_message);
    exit;
}

function transStatus($val) {
    if($val==1){
        $st = "false";
    }else{
        $st = "true";
    }
    return $st;
}

?>