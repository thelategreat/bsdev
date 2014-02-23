<? $this->load->view('common/header'); ?>
<body>
    <ul class="ym-skiplinks">
        <li><a class="ym-skip" href="#nav">Skip to navigation (Press Enter)</a></li>
        <li><a class="ym-skip" href="#main">Skip to main content (Press Enter)</a></li>
    </ul>

    <div class="ym-wrapper">
        <div class="ym-wbox">
            <header>
                <h1>Bookshelf</h1>
            </header>

            <? $this->load->view('common/nav', $nav); ?>

            <div id="main" class='ym-clearfix'>
                <div class="ym-wrapper">
                    <div class="ym-wbox">                        
                        <?=$html?>
                    </div>
                </div>
            </div>
        </div>
    </div>