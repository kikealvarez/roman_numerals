<?php
/*
    API interface for generate roman numbers from integer numbers or parse roman numbers to integer numbers.
    It works for numbers between 1 and 3999
 
    Input:
        $_GET['action'] = [ generate | parse ]
        $_GET['input'] = [ string | integer ]
    Output: A formatted JSON response
 
    Examples of use:
        Input: http://localhost/roman/index.php?action=generate&input=400
        Output: {"code":1,"status":200,"message":"Success","result":"CD"}
 
        Input: http://localhost/roman/index.php?action=parse&input=XVI
        Output: {"code":1,"status":200,"message":"Success","result":16}
 
    Author: Enrique Alvarez Macías
    Developed and tested with Apache/2.4.9 and PHP/5.5.12
    History: 27/08/2015 - Created
*/

require_once __DIR__ . '\Converter.php';

/**
 * Send HTTP Response
 * @param string $response The HTTP response data
 * @return void
 **/
function sendResponse($response){
    
    //HTTP response codes
    $httpResponseCodes = array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found'
    );
 
    //Set response headers
    header('HTTP/1.1 ' . $response['status'] . ' ' . $httpResponseCodes[$response['status']]);
    header('Content-Type: application/json; charset=utf-8');
 
    //Send json data
    exit(json_encode($response));
}

//API response codes and their related HTTP response message
$apiResponseCodes = array(
    1 => array('HTTP Response' => 200, 'Message' => 'Success'),
    2 => array('HTTP Response' => 400, 'Message' => 'Invalid Request. The first parameter must be named action and have as value generate or parse'),
    3 => array('HTTP Response' => 400, 'Message' => 'Invalid Request. For a roman number to be generated, the second parameter must be named input and have as value an integer number between 1 and 3999'),
    4 => array('HTTP Response' => 400, 'Message' => 'Invalid Request. For a roman number to be parsed, the second parameter must be named input and have as value a valid roman number with a corresponding integer value between 1 and 3999')
);

//Take and validate action param
if( ! $action = filter_input(INPUT_GET, 'action', FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>'/^generate$|^parse$/i')))) {
    //Set HTTP response
    $response['code'] = 2;
    $response['status'] = $apiResponseCodes[$response['code']]['HTTP Response'];
    $response['message'] = $apiResponseCodes[$response['code']]['Message'];
    $response['result'] = NULL;
    //Send response to browser
    sendResponse($response);
}

$oConverter = new Converter();

if(strcasecmp($action,'generate') == 0){
    //Take and validate input param only accepting integers between 1 and 3999
    if( ! $input = filter_input(INPUT_GET, 'input', FILTER_VALIDATE_INT, array('options'=>array('min_range'=>1, 'max_range'=>3999)))) {
        //Set HTTP response
        $response['code'] = 3;
        $response['status'] = $apiResponseCodes[$response['code']]['HTTP Response'];
        $response['message'] = $apiResponseCodes[$response['code']]['Message'];
        $response['result'] = NULL;
        //Send response to browser
        sendResponse($response);
    }
    
    $response['code'] = 1;
    $response['status'] = $apiResponseCodes[$response['code']]['HTTP Response'];
    $response['message'] = $apiResponseCodes[$response['code']]['Message'];
    $response['result'] = $oConverter->generate($input);
    
} elseif(strcasecmp($action,'parse') == 0){
    //Take and validate input param only accepting valid roman numbers
    if(empty($_GET['input']) || ! $input = filter_input(INPUT_GET, 'input', FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>'/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/')))) {
        $response['code'] = 4;
        $response['status'] = $apiResponseCodes[$response['code']]['HTTP Response'];
        $response['message'] = $apiResponseCodes[$response['code']]['Message'];
        $response['result'] = NULL;
    } else {
        $response['code'] = 1;
        $response['status'] = $apiResponseCodes[$response['code']]['HTTP Response'];
        $response['message'] = $apiResponseCodes[$response['code']]['Message'];
        $response['result'] = $oConverter->parse($input);
    }
}

//Send response to browser
sendResponse($response);