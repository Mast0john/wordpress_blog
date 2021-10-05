<!DOCTYPE html>
<html>
<head>
<style>
html
{
height:100%;
}
body{
	
background-image: url('<?php echo Template_theme_option('ttr_mm_image'); ?>');
background-repeat: no-repeat;
background-size:100% 100%;
height:100%;
}

#container
{
margin:0 auto;
width: 700px;
margin-top:40px;
background:white;
border-radius:3px;
box-shadow:1px 1px 1px 1px;
opacity:1;

}
.title
{
text-align:center;
font-size:38px;
font-color:black;
}
.content
{
	
margin:40px;
text-align:center;
}
</style>
</head>
<body>
<div id="container">
<div class="title">
<?php if(Template_theme_option('ttr_mm_title'))
{
	$title=Template_theme_option('ttr_mm_title');
	echo $title;	
}
else{
	$title='Down for Maintenance - ' . get_bloginfo( 'name');
	echo $title;
}?></div>
<div class="content">
<?php if(Template_theme_option('ttr_mm_content')){
	$content=Template_theme_option('ttr_mm_content');
	echo $content;
}
else
{
	$content ='Sorry for in convenience';
	echo $content;
}?>
</div>
</div>
</body>
</html>
