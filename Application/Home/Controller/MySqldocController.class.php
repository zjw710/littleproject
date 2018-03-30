<?php
/*
实现将数据库表结构导出成表格或者showdoc的格式
201608
jewey
 */
namespace Home\Controller;
use Think\Controller;
class MySqldocController extends Controller {
    public function index(){
    	echo "MySqldoc/index";
    }
    //以表格形式打印数据库某张表
    public function showtable($value='')
    {
    	// $user = M("tp_users");
    	// $data = $user->select();
    	// dump($data);
    	
    	header("Content-Type:text/html;charset=utf-8");
    	$tbname = I('tbname','tp_users');
    	$db = M();
    	$sql = "select 
			    	column_name AS `列名`,    
					data_type   AS `数据类型`,  
					character_maximum_length  AS `字符长度`,  
					numeric_precision AS `数字长度`,  
					numeric_scale AS `小数位数`,  
					is_nullable AS `是否允许非空`,  
					column_default  AS  `默认值`,  
					column_comment  AS  `备注`   
    			from Information_schema.columns 
	    			where TABLE_SCHEMA='".C("DB_NAME")."' and   
					table_Name='".$tbname."' 
					order by ORDINAL_POSITION asc";
		$data = $db->query($sql);
		echo "<table border=1>";  
		echo "<tr style='background-color:#ccc'>";  
		echo "<td>列名</td>";  
		echo "<td>字段描述</td>";  
		echo "<td>数据类型</td>";  
		echo "<td>字符长度</td>";  
		echo "<td>数字长度</td>";  
		echo "<td>小数位数</td>";  
		echo "<td>是否允许非空</td>";  
		echo "<td>默认值</td>";  
		echo "</tr>";  
		foreach ($data as $key => $value) {
			// dump($value);
		    echo "<td>".$value['列名']."</td>";  
		    echo "<td>".$value['备注']."</td>";  
		    echo "<td>".$value['数据类型']."</td>";  
		    echo "<td>".$value['字符长度']."</td>";  
		    echo "<td>".$value['数字长度']."</td>";  
		    echo "<td>".$value['小数位数']."</td>";  
		    echo "<td>".$value['是否允许非空']."</td>";  
		    echo "<td>".$value['默认值']."</td>";  
		    echo "</tr>";  			
		}		
    }
    //查询数据库中有哪些表，并返回表名列表
    public function querytablename($value='')
    {
    	$db = M();
    	$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".C("DB_NAME")."' ";//TABLE_COMMENT 表备注名，未添加
    	$data = $db->query($sql);
    	
    	foreach ($data as $key => $value) {
    		$tablename[$key] = $value["table_name"] ;
    	}    	
    	return $tablename;
    }
    //打印数据库表名
    public function showtbname($value='')
    {
    	dump($this->querytablename());
    }
    //查询某张表所有字段及类型
	public function showOnetbcolumn($tbname='')	
    {
    	if (empty($tbname)) {
    		$tbname = I('tbname','tp_users');
    	}    	
    	header("Content-Type:text/html;charset=utf-8");
    	$db = M();
    	$tb_info_sql = "SELECT table_comment as 'desc' FROM
    				information_schema. TABLES
    				WHERE table_schema = '".C("DB_NAME")."' and
					table_name = '".$tbname."'";
		$tb_info = $db->query($tb_info_sql);
		// dump($tb_info);
		$tb_desc = $tb_info[0]['desc'];
    	$sql = "select 
			    	column_name AS `列名`,    
					data_type   AS `数据类型`,  
					character_maximum_length  AS `字符长度`,  
					numeric_precision AS `数字长度`,  
					numeric_scale AS `小数位数`,  
					is_nullable AS `是否允许非空`,  
					column_default  AS  `默认值`,  
					column_comment  AS  `备注`   
    			from Information_schema.columns 
	    			where TABLE_SCHEMA='".C("DB_NAME")."' and   
					table_Name='".$tbname."' 
					order by ORDINAL_POSITION asc";
		$data = $db->query($sql);
		echo "## 表名：".$tbname." ".$tb_desc."<br><br>";
		echo "|编号|列名|备注|类型|长度|空|默认值|";  
		echo "<br>";
		echo "|:----    |:----    |:-------    |:--- |-- -|------      |";  
		echo "<br>";  
		foreach ($data as $key => $value) {
			// dump($value);
			echo "|".($key+1);  
		    echo " |".$value['列名'];  
		    echo " |".$value['备注'];  
		    echo " |".$value['数据类型'];  
		    echo " |".$value['字符长度'];  
		    echo " |".$value['是否允许非空'];  
		    echo " |".$value['默认值']." |";  
		    echo "<br>";  			
		}	
		echo "<br><br>";	
    }    
    //查询显示所有的表，按showdoc格式
    public function showAlltbcolumn($value='')
    {
    	$tablename = $this->querytablename();    	
    	// dump($tablename);exit;
    	foreach ($tablename as $key => $value) {
    		//dump($value);
    		$this->showOnetbcolumn($value);
    	}
    }
}