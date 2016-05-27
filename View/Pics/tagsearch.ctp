<?php
	//連接資料庫
	$db = mysqli_connect();
	if (!$db) die("錯誤: 無法連接MySQL伺服器!" . mysqli_connect_error());
	mysqli_select_db($db, "photosearch") or  // 選擇資料庫
	  	die("錯誤: 無法選擇資料庫!" . mysqli_error($db));
	
	//接取表單
	$search = $_GET['q'];
	$sql = "";
	$size = $_GET['size'];
	$sql_size = "";
	if($size == 2 || $size == 3)
	{
		$width = 1920;
		$height = 1080; 
		$sql_size = ($size == 2) ? " and width >= ".$width." and height >= ".$height." "  : 
					" and width between 800 and ".$width." and height between 600 and ".$height." ";
	}
	elseif($size == 4)
	{
		$width = 800;
		$height = 600;
		$sql_size = " and width < ".$width." and height < ".$height." ";
	}
	$authType = $_GET['authType'];
	
	//搜尋
	if(!empty($search))
	{
		//標籤關鍵字
		$sql = "select * from pics , Pic_tag
				where pics.ID = Pic_tag.Pic_ID and 
				Pic_tag.tag_name = '".$search."' and AuthType = ".$authType.$sql_size."; ";
	}
	else
	{
		//標籤列表
		$sql = "select distinct Name from tag";
	}
	$result = mysqli_query($db,$sql);
?>

<div style="overflow: visible">
	<ul class="polaroids">
    <!-- 圖片顯示 -->
    <?php 
        if(empty($result))	echo "Error!";
        elseif(!empty($search))
        {
        	$show = mysqli_fetch_all($result,MYSQLI_NUM);
        	if(count($show)==0)
        	{
        		echo "No Search!";	
        	}
        	else
        	{
        		echo "<p>您所搜尋標籤為：".$search."</p>";
        		for($i=0; $i < count($show); $i++)
				{
					echo '<li>
					<a href="profile?varname='.$show[$i][0].'" title="'.$show[$i][1].'">
                        <img alt="Roeland!" src="../webroot/'.$show[$i][2].'">
                        </img>
                        <iframe width="0" height="0" name="actionframe" style="visibility:hidden;display:none"></iframe> <!--提交表單而不跳轉-->
                        <form action="myFunction()" target="actionframe">
	                        <button class="btn btn-success" onclick="myFunction()">
					            收藏
					        </button>
					    </form> 
                    </a>
                	</li>';
				}
        	}
        }
        else
        {
        	echo "所有標籤列表";
        	$show = mysqli_fetch_all($result,MYSQLI_NUM);
        	for($i=0;$i<count($show);$i++)
        	{
				echo "<p><a href='tagsearch?q=".$show[$i][0]."&size=1&authType=1'>".$show[$i][0]."</a></p>";
			}
        }
		mysqli_close($db);
	?>
	</ul>
	</div>

	<script type="text/javascript">
		function myFunction() {
		    alert("我將執行收藏功能");
		}
	</script>
        

        
