<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
        <title>我们都力求完美</title>
        <meta name="description" content="我要买车网为您提供全面而细致的汽车生活服务，并量身打造超低首付的购车方案，让您购车再无资金压力，也不必辛苦比价。">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        

        <!-- 重置 公用 框架css-->
        <?= $this->tag->stylesheetLink('') ?>

       
    </head>

    <body class="page-loading">
        
        

        <!-- main area -->
        <div class="main-content" id="maincontent">
            <!-- 内容 -->
            
            <?= $this->getContent() ?>
            
            
            
        </div>

        <!-- build:js({.tmp,app}) scripts/app.min.js -->
        <?= $this->tag->javascriptInclude('') ?>

    </body>


</html>