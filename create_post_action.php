<?php
include_once(dirname(__FILE__) . "/cdyn_includes/database/db.php");
include_once(dirname(__FILE__) ."/cdyn_includes/cdyn_phputil.php");
include_once(dirname(__FILE__) ."/cdyn_includes/cdyn_printform.php");
include_once(dirname(__FILE__) . "/cdyn_includes/cdyn_phpvalidation_class.php");
$pageName="create_post.php";
$datastorage="insert";$error="";$ajaxmethod=0;
if(strtolower($_SERVER["REQUEST_METHOD"])!=="post") {
header("location:".$pageName);exit;
}
if(isset($_POST["ajaxmethod"]) && $_POST["ajaxmethod"]==1) {
$ajaxmethod=1;
header("Content-type:application/json");
}
$validate=new CDYN_FormValidation();
$cdyn_utilobj=new cdyn_phputility();
$arr=$postarr=array();
$arr["post_title"]=$postarr["post_title"]=$cdyn_utilobj->chkSavePostInput("post_title");
$arr["post_content"]=$postarr["post_content"]=$cdyn_utilobj->chkSavePostInput("post_content");
$valid_elem=array();
$valid_elem["post_title"]=array(
"value"=>$postarr["post_title"],
"label"=>"Title",
"validation"=>array(
"required" => true
)
);
$valid_elem["post_content"]=array(
"value"=>$postarr["post_content"],
"label"=>"Content",
"validation"=>array(
"required" => true
)
);
$validate->checkErrors($valid_elem);
$error.= $validate->getError();
if($error=="") {
$print_emailobj=new CDYNprintEmailFormData();
$print_emailobj->textbox("post_title","Title");
$print_emailobj->textarea("post_content","Content");
$cdyn_emaildata=$print_emailobj->printFormData();
/* //Print Form data
echo $cdyn_emaildata;*/


$arr['posts_owner']=$_SESSION[USR_SESSION]['members']['member_id'];
$arr['group_id']=0;

$message="Post form data successully";
$insert_status=0;
//$db->debug_mode=true;
$insert_status=$db->insert($arr,"posts");
header("location:members_dashboard.php?posted=success");exit;

if($insert_status) {
$message="Form data saved successfully";
}
if($ajaxmethod==1){
echo "{ \"error\":0,\"success\":1,\"type\":\"insert\",\"msg\":\"".$message."\"}";
exit;}else {
echo $message;
//header("location:$pageName"."?success=1");
exit;}
}else {
if($ajaxmethod==1) {
echo "{ \"error\":1,\"success\":0,\"type\":\"insert\",\"msg\":\"$error\"}";
}
else {
include_once($pageName);
}
}
?>