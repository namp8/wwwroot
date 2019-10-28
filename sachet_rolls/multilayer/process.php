<?php
    include_once "../../base.php";
    $pageTitle = "Multilayer Process";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.multilayer.inc.php";
    $multilayer = new Multilayer($db);
?>
	<ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="process.php">Multilayer</a>
        </li>
        <li class="breadcrumb-item active">Process</li>
    </ol>
    <h2>Multilayer - Process</h2>
	<div class="mxgraph" style="max-width:100%;border:1px solid transparent;" data-mxgraph="{&quot;highlight&quot;:&quot;#0000ff&quot;,&quot;nav&quot;:true,&quot;resize&quot;:true,&quot;toolbar&quot;:&quot;zoom layers lightbox&quot;,&quot;edit&quot;:&quot;_blank&quot;,&quot;xml&quot;:&quot;&lt;mxfile userAgent=\&quot;Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36\&quot; version=\&quot;8.1.2\&quot; editor=\&quot;www.draw.io\&quot; type=\&quot;google\&quot;&gt;&lt;diagram id=\&quot;2f404044-711c-603c-8f00-f6bb4c023d3c\&quot; name=\&quot;Page-1\&quot;&gt;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&lt;/diagram&gt;&lt;/mxfile&gt;&quot;}"></div>
	<script type="text/javascript" src="/assets/js/viewer.min.js"></script>


    <?php
    include_once '../../footer.php';
?>