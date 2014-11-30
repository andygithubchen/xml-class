<?php
/**
 * xml文件处理类
 * @authors Andy Chen (bootoo@sina.cn)
 * @date    2014-03-15 10:52:37
 * @version $Id$
 */

class xmlClass {
    
    function __construct(){
        
    }


    /**
     * 将php数组转换为XML文件的方法
     * @param str     $name    要生成的XML文件名
     * @param arr     $array   要转换的数组
     * @param bool    $return  函数是否要返回生成后的文件路径
     * @return str    $dir     返回XML文件路径
     */
    public function ArrToXml($name, $array, $return = false){

        //检查app下是否有xml文件
        if (!file_exists(APP_XML_ROOT)) {
            if (!mkdir(APP_XML_ROOT,0777)) {  //0777表示最大的读写权限
            	die('不能创建xml文件');
            }
        }
        
        $file = APP_XML_ROOT.$name.'.xml';

        //打开文件并取得文件头指针
 		$handle = @fopen($file, "w");  

        //组装数据
        $xml = $this->package($array,'books','book');

        //写入内容
        if (!$wr = fwrite($handle, $xml)) {
            die('内容写入错误！');
        } 

        //关闭文件
        if(!fclose($handle)){
        	die('内容写入错误！');
        }

        if ($return) {
            return $file;
        } else {
            return true;
        }
        
    }

    /**
     * 将php数组组装为XML的方法
     * @param arr     $arr     要转换的数组
     * @param str     $box     XML数据容器
     * @param str     $name    数据名称
     * @return str    $xml     返回组装好的XML字符串
     */
    public function package($arr,$box,$name){
        $xml = '';
        $xml .= '<'.$box.'>';
        foreach ($arr as $value) {
            $xml .= '  <'.$name.'>';
     		$xml .= $this->foreachArr($value);
            $xml .= '  </'.$name.'>';
        }
        $xml .= '</'.$box.'>';
       return $xml;
    }

    /**
     * 将php数组循环组装为XML字符串的方法
     * @param arr     $value   要循环转换的数组
     * @return str    $xml     返回组装好的XML字符串
     */
    public function foreachArr($value){
    	 $xml = '';    
         foreach ($value as $k => $v) {
         	if (is_array($v)) {
         		$k = is_numeric($k) ? 'action' : $k;			
         		$xml .= '<'.$k.'>';
         		$xml .= $this->foreachArr($v);
         		$xml .= '</'.$k.'>';
         	} else {
                $xml .= '    <'.$k.'>'.$v.'</'.$k.'>';
         	}
         }
        return $xml;
    }

    /**
     * 将XML文件转换为php数组的方法
     * @param str     $name    要生成的php数组文件名
     * @param object  $path    要转换的Xml文件路径
     * @param bool    $return  函数是否要返回生成后的文件路径
     * @return str    $dir     返回XML文件路径
     */
    public function XmlToArr($name,$path,$return = false){
        // 打开文件
        $file = APP_XML_ROOT.$name.'.php';
        $handle = @fopen($file, "w");  //打开或生成文件并取得文件头指针
        $xml = simplexml_load_file($path);  
        if(!$xml) error('xml文件载入错误！请重试。'); 

        //生成php数组
        $arr = $this->objectToArray($xml);

        //将数组写入文件
        // NO.1
        $string_start = "<?php return \n";
        $string_process = var_export($arr, TRUE);
        $string_end = "\n?>";
        $string = $string_start.$string_process.$string_end; //开始写入文件
        $put = file_put_contents($file, $string);   
        if(!$put) error('数据生成出错！请重试。'); 

        // // NO.2
        // //数组序列化
        // $string = serialize($arr);
        // //写入文件
        // if (!fwrite($handle,$string)) {
        //     error('内容写入错误！');
        // }
        // // 关闭文件
        // if (!fclose($handle)) {
        //     error('内容写入错误！');
        // }

        if ($return) {
            return $file;
        } else {
            return true;
        }
    }

    /**
     * 将XML文件转换为php数组的方法
     * @param object  $object    要转换的XML对象
     * @return arr    $array     返回XML文件路径
     */
    public function objectToArray($object) {
        // 判断对象是否为空  
        if( count($object)==0 ){  
            return trim((string)$object);
        }

        $array = array();

        // 去除XML的“SimpleXMLElement Object”预定义字符
        $object = is_object($object) ? get_object_vars($object) : $object;

        foreach ($object as $key => $val) {
            $newVal = (is_object($val) || is_array($val)) ? $this->objectToArray($val) : $val;
            $array[$key] = $newVal;
        }
        return $array;
    }




}
?>