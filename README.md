php xml class
=========

## 将php数组转换为XML文件的方法
```php
require_once './xmlClass.php';
$obj = new xmlClass;

$name    = 'myxml';  //要生成的XML文件名
$array   = $arr;     //要转换的数组
$return  = true;     //函数是否要返回生成后的文件路径
$path = $obj->ArrToXml($name, $array, $return);
```

## 将XML文件转换为php数组的方法
```php
require_once './xmlClass.php';
$obj = new xmlClass;

$name   = 'myarr';      //要生成的php数组文件名
$path   = './data.xml'; //要转换的Xml文件路径
$return = true;         //函数是否要返回生成后的文件路径
$path = $obj->XmlToArr($name, $path, $return);
```
