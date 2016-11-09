a:11:{i:0;s:74:"<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
        <title>";s:5:"title";N;i:1;s:52:"</title>
        <meta name="description" content="";s:11:"description";N;i:2;s:202:"">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        ";s:5:"metaB";N;i:3;s:295:"

        <!-- 重置 公用 框架css-->
        <?= $this->tag->stylesheetLink('') ?>

       
    </head>

    <body class="page-loading">
        
        

        <!-- main area -->
        <div class="main-content" id="maincontent">
            <!-- 内容 -->
            ";s:8:"contentA";N;i:4;s:54:"
            <?= $this->getContent() ?>
            ";s:8:"contentB";N;i:5;s:182:"
            
            
        </div>

        <!-- build:js({.tmp,app}) scripts/app.min.js -->
        <?= $this->tag->javascriptInclude('') ?>

    </body>


</html>";}