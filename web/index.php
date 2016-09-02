<meta charset="utf-8">
<?php

require_once '../autoload.php';

$arrPath = explode('/', strtolower($_REQUEST['path']) );

switch ($arrPath[0]) {
    case 'admin':                    // ссылки вида {имя_сайта}/admin/*
        switch ($arrPath[1])  {
            case 'singin':              // ссылки вида {имя_сайта}/admin/singin/ (*)

                $parameters = explode('?', $arrPath[2]) ? : [];

                $loginController = new adminLoginController($parameters);

                $content = $loginController->getResponce();

            case 'workers':
                $cLink = curl_init('http://allservice.in.ua/test_task/online_store/offices/');
                if ( curl_setopt($cLink, CURLOPT_RETURNTRANSFER, true) &&
                  curl_setopt($cLink, CURLOPT_FRESH_CONNECT, true) ) {

                    $result = curl_exec($cLink);

                    if (preg_match_all( '/((?>\\{)[\S\s]*Stylish\sDesk\sDecors[\S\s]*?(?=\\}))/', $result, $arrayFilter) ) {
                        foreach($arrayFilter as $value) {
                            if (is_array($value)) {
                                foreach($value as $val) {
                                    echo $val . '.  ';
                                }
                                echo '<br>';
                            } else {
                                echo $value . '<br>';
                            }

                        }
                    }
                    exit(0);

                    $result = json_decode($result, true);

                    foreach ($result as $json) {
                        $row = json_decode($json, true);
                        foreach ($row as $key => $value) {
                            echo "$key = $value, ";
                        }
                        echo "<br>";
                    }
                  };
                  curl_close($cLink);
                    break;
            case 'stat':
            case 'customers':
            default:
                $content = implode('.', $arrPath);
        }

    default: // user controllers
        switch ($arrPath[0])  {
            case 'category':// ссылки вида {имя_сайта}/category/{$command}

                $categoryController = new categoryController($arrPath[1]); // $command
                $content = $categoryController->getResponce();

                break;

            case 'product':// ссылки вида {имя_сайта}/product/{$command}

                $controller = new productController($arrPath[1]);
                $content = $controller->getResponce();

                break;

            case 'offices':

                $queryBuilder = new queryBuilder();
                $select = [
                    'worker'       => 'e.lastName',
                    'nameCustomer' => 'customerName',
                    'countOrders'  => 'count(orderNumber)',
                    'orderDate'    => 'orderDate',
                    'avgPrice'     => 'avg(priceEach)',
                    'Product'      => "GROUP_CONCAT(productName SEPARATOR ', ')",
                    'Product_Line' => "GROUP_CONCAT(textDescription SEPARATOR ', ')",
                ];

                $queryBuilder->Fields($select);

                $queryBuilder->Where( [ 'MONTH(orderDate)' => 12 ] );

                $queryBuilder->setFrom('employees e join customers cus on(e.employeeNumber = cus.salesRepEmployeeNumber) join orders OS using(CustomerNumber) 
join orderdetails OD using(orderNumber) join products p using(productCode) join productlines using(productLine)');

                $queryBuilder->setGroupBy('worker, customerName');

                $result = $queryBuilder->runPreparedSQL();

                $content = [];

                $queryBuilder->getSql();

                while ($row = mysqli_fetch_assoc($result)) {
                    $content[] = json_encode($row);
                }
                echo $content = json_encode($content);

                exit(0);

                break;

            default:
                $content = implode('.', $arrPath);

        }


}
include '../views/pages/main/header.html';
include '../views/pages/main/content.html';
include '../views/pages/main/footer.html';