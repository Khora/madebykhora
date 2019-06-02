<?php
    // include config files
    include "config/fixerApiKey.php";

    // set max execution time to infinity
    set_time_limit(0);
    
    // init session
    session_start();
    initSessionVariables();

    /*
     * Initialisation method, to be called first.
     */
    function initSessionVariables() {
        // how old (in seconds) data may be at most (1 day)
        $_SESSION["dataExpiryTimeSeconds"] = 1 * 24 * 60 * 60;
        
        // get if the client is a mobile device
        $mobile = "false";
        if (isset($_SESSION["mobile"])) {
            $mobile = $_SESSION["mobile"];
        }
        if (isset($_GET["mobile"])) {
            $mobile = strtolower(htmlspecialchars($_GET["mobile"]));
        }
        $_SESSION["mobile"] = $mobile;
        
        // the chosen currency of the client
        $currency = "GBP";
        if (isset($_SESSION["currency"])) {
            $currency = $_SESSION["currency"];
        }
        if (isset($_GET["currency"])) {
            $currency = htmlspecialchars($_GET["currency"]);
        }
        $_SESSION["currency"] = $currency;
    }
    
    /*
     * Getter for if the client wants to be served the mobile version.
     */
    function isMobile() {
        if (strcmp($_SESSION["mobile"], "true") == 0) {
            return true;
        }
        return false;
    }
    
    /*
     * Getter for the currency of the client.
     */
    function getCurrency() {
        $currentCurrency = "GBP";
        if (isset($_SESSION["currency"]) && strcmp($_SESSION["currency"], "") != 0) {
            $currentCurrency = $_SESSION["currency"];
        }
        return $currentCurrency;
    }
    
    /*
     * Getter for client ip address.
     */
    function getClientIp() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
           $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }
    
    /*
     * Gets the HTML head tag.
     */
    function getHtmlHead($title) {
        if (!isMobile()) {
            return '<head>
                        <meta charset="utf-8"/>
                    <title>' . $title . '</title>
                    <link rel="stylesheet" type="text/css" href="css/style.css">
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fjalla+One|Karla:400,700">
                    <script language="javascript" type="text/javascript" src="lib/lib.js"></script>
                    <link rel="icon" type="image/x-icon" href="img/icon.png">
                    
                    <script>
                        if (isMobileOrTablet()) {
                            document.location = "' . basename($_SERVER['PHP_SELF']) . '?mobile=true";
                        }
                    </script>
                </head>';
        } else {
            return '<head>
                        <meta charset="utf-8"/>
                    <title>' . $title . '</title>
                    <link rel="stylesheet" type="text/css" href="css/style.css">
                    <link rel="stylesheet" type="text/css" href="css/styleMobile.css">
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fjalla+One|Karla:400,700">
                    <script language="javascript" type="text/javascript" src="lib/lib.js"></script>
                    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
                    <link rel="icon" type="image/x-icon" href="img/icon.png">
                </head>';
        }
    }
    
    /*
     * Gets the top area containing the common menu.
     */
    function getPageHeader($title) {
        if (!isMobile()) {
            return '<script>
                        if (window.addEventListener) {
                            window.addEventListener(\'resize\', function() {
                                resizeHeaderElements();
                            }, true);
                        }
                        resizeHeaderElements();
                    </script>
                    
                    <div id="header">
                        <a id="headerLink" href="index.php">
                            <div id="logo"></div>
                        </a>
                        <div id="headerText">
                            <a id="headerLink" href="index.php">ABOUT</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="headerLink" href="store.php">STORE</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="headerLink" href="campaign.php">CAMPAIGN</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>' . getDonationProgress(getCurrentDonationProgress(), getTargetDonationProgress(), "calc(50% + 200px)", "-71px") . getEurosPoundsSwitch("calc(50% + 343px)", "36px") . getCartButton("calc(50% + 390px)", "36px") . getShadow();
        } else {
            return '<div id="mobileMenuBackground">
                </div>
                <div id="mobileLogo">
                    <a href="index.php"><img src="img/logo_small.png" style="width: 9mm; height: 8mm;"></a>
                </div>
                <div id="mobileTitle">
                    ' . $title . '
                </div>
                <div id="mobileBurgerIcon">
                    <img src="img/burgerMenuIcon.png" id="mobileLogoImg" style="width: 9mm; height: 9mm;" onclick="closeOrOpenMobileMenu();">
                </div>
                <div id="mobileShadow">
                </div>
                
                <div id="mobileMenuContent" style="background: red;">
                <table id="mobileMenuTable" class="gridtable" width="100%">
                    ' . getDonationProgress(getCurrentDonationProgress(), getTargetDonationProgress(), "20px", "-35px") . getEurosPoundsSwitch("calc(100% - 65mm)", "71px") . getCartButton("calc(100% - 55mm)", "71px") . '
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="index.php" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;ABOUT</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="store.php" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;STORE</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="campaign.php" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;CAMPAIGN</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="privacy.php" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;PRIVACY and COOKIES</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="terms.php" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;TERMS and CONDITIONS</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="contact.php" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;CONTACT and FAQ</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="http://www.khora-athens.org/" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;KHORA WEBPAGE</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="https://www.facebook.com/KhoraAthens/" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;KHORA FACEBOOK</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="https://www.instagram.com/khoraathens/" style="color: #555555; text-decoration: none;">&nbsp;<span style="font-size: 5mm;">&nbsp;KHORA INSTAGRAM</span></a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <span style="color: #555555; text-decoration: none; font-size: 5mm;">&nbsp;&copy; Khora, ' . date("Y") . '. All rights reserved.</span>
                        </td>
                    </tr>
                    <tr>
                        <td id="switchToDesktop" style="height: 10mm; vertical-align: middle; border-color: #ffffff;">
                            <a href="' . basename($_SERVER['PHP_SELF']) . '?mobile=false" style="color: #555555; text-decoration: none;">&nbsp;DESKTOP VERSION</span></a>
                        </td>
                    </tr>
                </table>
            </div>

            <script>
                document.getElementById("mobileMenuContent").style.display = "none";
                    
                function closeOrOpenMobileMenu() {
                    if (!elementIsVisible(document.getElementById("mobileMenuContent"))) {
                        changeIcon(document.getElementById("mobileLogoImg"), "img/burgerMenuCloseIcon.png");
                        document.getElementById("content").style.display = "block";
                    } else {
                        changeIcon(document.getElementById("mobileLogoImg"), "img/burgerMenuIcon.png");
                    }
                    
                    changeIsOpen(document.getElementById("mobileMenuContent"));
                    changeIsOpen(document.getElementById("content"));
                }
                
                if (isMobileOrTablet()) {
                    document.getElementById("switchToDesktop").innerHTML = "";
                }
            </script>';
        }
    }
    
    /*
     * Gets the shadow to put below the top area.
     */
    function getShadow() {
        if (!isMobile()) {
            return '<div id="shadow"></div>';
        } else {
            return '';
        }
    }
    
    /*
     * Gets the bottom area containing the bottom options.
     */
    function getPageFooter() {
        if (!isMobile()) {
            return '<div id="footerPartnersBackground">
                        <div id="footer" style="color: #222222;">
                                Partners:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum
                        </div>
                    </div>
                    <div id="footerBackground">
                        <div id="footer" style="color: #222222;">
                                <a id="headerLink" href="privacy.php">PRIVACY and COOKIES</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a id="headerLink" href="terms.php">TERMS and CONDITIONS</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a id="headerLink" href="contact.php">CONTACT and FAQ</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &copy; Khora, ' . date("Y") . '. All rights reserved.
                                <a href="http://www.khora-athens.org/"><div id="webpage"></div></a>
                                <a href="https://www.facebook.com/KhoraAthens/"><div id="facebook"></div></a>
                                <a href="https://www.instagram.com/khoraathens/"><div id="instagram"></div></a>
                        </div>
                    </div>
                    <div id="footerAssociationBackground">
                        <div id="footer" style="color: #222222;">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<br><br>
                        </div>
                    </div>';
        } else {
            return '<div id="footerPartnersBackground">
                        <div style="padding-left: 10px; padding-right: 10px;">
                            Partners:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum<br>Lorem ipsum<br>Lorem ipsum<br>Lorem ipsum
                        </div>
                    </div>
                    <div id="footerAssociationBackground">
                        <div style="padding-left: 10px; padding-right: 10px;">
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.<br><br>
                        </div>
                    </div>';
        }
    }
    
    /*
     * Gets a formatted button containing the given text and image.
     */
    function getDonationProgress($alreadyCollectedDonationAmount, $targetDonationAmount, $xPos, $yPos) {
        $targetDonationAmount = max($targetDonationAmount, 0);
        $percentage = 100 * $alreadyCollectedDonationAmount / $targetDonationAmount;
        $percentage = min(max($percentage, 0), 100);
        
        return'<style type="text/css" media="screen">
                    .progressBarContainer {
                        font-size: 14px;
                        color: black;
                        text-decoration: none;
                        -o-transition: 0.2s;
                        -ms-transition: 0.2s;
                        -moz-transition: 0.2s;
                        -webkit-transition: 0.2s;
                        transition: 0.2s;
                        opacity: 1;
                        margin: 100px auto;
                        width: 105px;
                        height: 10px;
                        position: fixed;
                        top: ' . $yPos . ';
                        left: ' . $xPos . ';
                        z-index: 1002;
                    }
                    
                    .progressBarContainer:hover {
                        opacity: 0.4;
                    }

                    .progressBar {
                        padding: 1px;
                        background: rgba(200, 200, 200, 0.1);
                        border-radius: 6px;
                        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.25), 0 1px rgba(255, 255, 255, 0.08);
                    }

                    .progressBarOrange {	
                        height: 10px;
                        border-radius: 4px; 
                        transition: 0.2s linear;  
                        transition-property: width, background-color;
                        width: ' . $percentage . '%; 
                        background-image: linear-gradient(135deg, #ffc65b 20%, #ff9900 20%, #ff9900 40%, #ffc65b 40%, #ffc65b 60%, #ff9900 60%, #ff9900 80%, #ffc65b 80%);
                        animation: progressAnimationStrike 2s;
                    }

                    @keyframes progressAnimationStrike {
                        from { width: 0% }
                        to   { width: ' . $percentage . '% }
                    }
                </style>
        
            <div class="progressBarContainer">
                <a href="campaign.php" style="cursor: pointer;">
                    &nbsp;CAMPAIGN:
                    <div class="progressBar">
                        <div class="progressBarOrange"></div>
                    </div>
                    &nbsp;' . getInCurrentCurrency($alreadyCollectedDonationAmount) . ' of ' . getInCurrentCurrency($targetDonationAmount) . '
                </a>
            </div>
        ';
    }
    
    /*
     * Gets a formatted switch for Euros and Pounds.
     */
    function getEurosPoundsSwitch($xPos, $yPos) {
        $checked = '';
        if (strcmp(getCurrency(), 'EUR') != 0) {
           $checked = 'checked'; 
        }
        return '<style type="text/css" media="screen">
                    .onoffswitch {
                        position: fixed;
                        width: 30px;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        top: ' . $yPos . ';
                        left: ' . $xPos . ';
                        z-index: 1060;
                    }

                    .onoffswitch-checkbox {
                        display: none;
                    }

                    .onoffswitch-label {
                        display: block;
                        overflow: hidden;
                        cursor: pointer;
                        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19);
                        border-radius: 43px;
                    }

                    .onoffswitch-inner {
                        display: block;
                        width: 200%;
                        margin-left: -100%;
                        transition: margin 0.3s ease-in 0s;
                    }

                    .onoffswitch-inner:before, .onoffswitch-inner:after {
                        display: block;
                        float: left;
                        width: 50%; 
                        height: 30px;
                        padding: 0;
                        line-height: 30px;
                        font-size: 14px;
                        font-weight: bold;
                        box-sizing: border-box;
                    }

                    .onoffswitch-inner:before {
                        content: "£";
                        padding-left: 10px;
                        background-color: #FFC65B;
                        color: #000000;
                    }

                    .onoffswitch-inner:after {
                        content: "€";
                        padding-right: 10px;
                        background-color: #FFC65B;
                        color: #000000;
                        text-align: right;
                    }

                    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
                        margin-left: 0;
                    }
                    
                    .tooltip {
                        display:inline-block;
                        position:fixed;
                        top: ' . $yPos . ';
                        left: ' . $xPos . ';
                        text-align:left;
                        z-index: 3005;
                        width: 10px;
                        height: 30px;
                        background-color: white;
                    }

                    .bottom {
                        z-index: 1060;
                    }

                    .onoffswitch .bottom {
                        min-width:200px; 
                        top:40px;
                        left:50%;
                        transform:translate(-50%, 0);
                        padding:10px 20px;
                        color:#444444;
                        background-color:white;
                        font-weight:normal;
                        font-size:13px;
                        border-radius:8px;
                        position:absolute;
                        box-sizing:border-box;
                        box-shadow:0 1px 8px #ccc;
                        display:none;
                        z-index: 1060;
                    }

                    .onoffswitch:hover .bottom {
                        display:block;
                        z-index: 1060;
                    }

                    .onoffswitch .bottom i {
                        position:absolute;
                        bottom:100%;
                        left:50%;
                        margin-left:-12px;
                        margin-top:2px;
                        width:24px;
                        height:12px;
                        overflow:hidden;
                        z-index: 1060;
                    }

                    .onoffswitch .bottom i::after {
                        content:\'\';
                        position:absolute;
                        width:12px;
                        height:12px;
                        left:50%;
                        transform:translate(-50%,50%) rotate(45deg);
                        background-color:white;
                        box-shadow:0 1px 8px #ccc;
                        z-index: 1060;
                    }
                </style>
        
                <div class="onoffswitch">
                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="currencyonoffswitch" onclick="callDelayed(currencySwitchClicked, 500);" ' . $checked . '>
                    <label class="onoffswitch-label" for="currencyonoffswitch">
                        <span class="onoffswitch-inner"></span>
                    </label>
                    <div class="bottom" id="a">
                        <center>Change Currency</center>
                        <i></i>
                    </div>
                </div>
                
                <script>
                    function currencySwitchClicked() {
                        currency = "GBP";
                        if (!currencyonoffswitch.checked) {
                            currency = "EUR";
                        }
                        window.location.href = getCurrentServerAndPath() + getCurrentFileName() + "?currency=" + currency;
                    }
                </script>
        ';
    }
    
    /*
     * Gets a formatted full width banner containing a random text.
     */
    function getTopBanner() {
        $randomNumber = rand(0, 5);
        $textToUse = '';
        if ($randomNumber == 0) {
            $textToUse = '1,000 meals per day for refugees and people in need.';
        }
        if ($randomNumber == 1) {
            $textToUse = 'Big Love, Big Respect.';
        }
        if ($randomNumber == 2) {
            $textToUse = 'In solidarity with refugees, displaced and homeless in Athens and elsewhere.';
        }
        if ($randomNumber == 3) {
            $textToUse = 'Khora Community Kitchen!';
        }
        if ($randomNumber == 4) {
            $textToUse = 'Freedom of movement for all!';
        }
        if ($randomNumber == 5) {
            $textToUse = 'Self-organised collective creative flexible non-dogmatic co-operative non-hierarchical consensus-based decision making!
';
        }
        
        return '<div id="topBanner">
                    <div id="topBannerPadding">
                        ' . $textToUse . '
                    </div>
                </div>';
    }
    
    /*
     * Gets a formatted full width banner containing the given text and image.
     */
    function getFullWidthTextAndImageBanner($text, $image) {
        if (!isMobile()) {
            return '<div style="position: relative; width: 100%; height: 500px;">
                    <div style="position: absolute; width: 50%; height: 500px; background: #ffc65b;">
                        <div style="padding-top: 55px; padding-bottom: 100px; padding-right: 100px; font-size: 15px; line-height: 20px;">
                            <div style="width: 300px; float: right;">
                                ' . $text . '
                            </div>
                        </div>
                    </div>
                    <div class="centerCroppedImage" style="position: absolute; left: 50%; height: 500px; background-image: url(\'' . $image . '\');"></div>
                </div>';
        } else {
            return '<div style="position: relative; width: 100%; height: auto;">
                    <div style="position: relative; width: 100%; height: auto; background: #ffc65b;">
                        <div style="padding-top: 15px; padding-bottom: 55px; padding-left: 10px; padding-right: 10px; font-size: 15px; line-height: 20px;">
                            <div style="float: center;">
                                ' . $text . '
                            </div>
                        </div>
                    </div>
                    <div class="centerCroppedImage" style="position: relative; height: 500px; width: 100%; background-image: url(\'' . $image . '\');"></div>
                </div>';
        }
    }
    
    /*
     * Gets a formatted quote design containing the given text and image.
     */
    function getQuote($text, $image, $rightQuote) {
        if (!isMobile()) {
            if ($rightQuote) {
                return '<div class="horizontalCenteredBase">
                        <div class="horizontalCentered" style="font-size: 15px; line-height: 30px;">
                            <table>
                                <tr>
                                    <td class="blockquoteLeft" style="width: 50%; padding-left: 20px;">
                                        ' . $text . '
                                    </td>
                                    <td style="width: 50%; padding: 0px;">
                                        <div class="centerCroppedImage" style="float: right; position: relative; height: 400px; width: 100%; background-image: url(\'' . $image . '\');"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>';
            } else {
                return '<div class="horizontalCenteredBase">
                            <div class="horizontalCentered" style="font-size: 15px; line-height: 30px;">
                                <table>
                                    <tr>
                                        <td style="width: 50%; padding: 0px;">
                                            <div class="centerCroppedImage" style="float: right; position: relative; height: 400px; width: 100%; background-image: url(\'' . $image . '\');"></div>
                                        </td>
                                        <td class="blockquoteRight" style="width: 50%; padding-left: 20px;">
                                            ' . $text . '
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>';
            }
        } else {
            return '<div class="horizontalCenteredBase">
                    <div class="horizontalCentered" style="font-size: 15px; line-height: 30px;">
                        <table>
                            <tr>
                                <td style="width: 100%; padding: 0px;">
                                    <div class="centerCroppedImage" style="float: right; position: relative; height: 400px; width: 100%; background-image: url(\'' . $image . '\');"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="blockquoteRight" style="width: 100%; padding-left: 10px; padding-top: 60px; padding-bottom: 30px;">
                                    ' . $text . '
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>';
        }
    }
    
    /*
     * Gets a cart button.
     */
    function getCartButton($xPos, $yPos) {
        return '<style type="text/css" media="screen">
                    .cartContainer {
                        position: fixed;
                        width: auto;
                        height: 25px;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        top: ' . $yPos . ';
                        left: ' . $xPos . ';
                        z-index: 1005;
                        display: block;
                        overflow: visible;
                        cursor: pointer;
                        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19);
                        border-radius: 43px;
                        background-color: #FFC65B;
                        padding: 5px 5px 0px 10px;
                        display: inline-block;
                        white-space: nowrap;
                    }
                </style>
        
                 <a class="imageLink" href="cart.php"><div class="cartContainer">
                   <img src="img/cart.png">&nbsp;&nbsp;' . getCartText() . '</a>
                </div>';
    }

	function displayItemOnSearch($id) {
        $widthSubtraction = 100;
        if (isMobile()) {
            $widthSubtraction = 0;
        }
		$item = json_decode(htmlentities(mb_convert_encoding(file_get_contents("./store/items/" . $id . ".json"), 'UTF-8', 'ASCII'), ENT_SUBSTITUTE, "UTF-8"), TRUE);
		return '<table style="width: calc(100% - ' . $widthSubtraction . 'px);">
                    <tr>
                        <td valign="center" style="width: calc(100% - ' . $widthSubtraction . 'px);">
                            <a href="item.php?id=' . $id . '">
                                <img src="store/img/' . $item['image'] . '" width="100%" style="display: inline-block;">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <a href="item.php?id=' . $id . '">
                                <p class="large">' . $item['name'] . '</p>
                                <small>' . $item['title'] . '</small></br></br>
                                Price: ' . getInCurrentCurrency(floatval($item['price'])) . '<span class="right"><small>ID: ' . $id . '</small></span>
                            </a>
                        </td>
                    </tr>
                </table>';
	}
	
	function displayItemOnSinglePresentation($id, $amount) {
		$item = json_decode(htmlentities(mb_convert_encoding(file_get_contents("./store/items/" . $id . ".json"), 'UTF-8', 'ASCII'), ENT_SUBSTITUTE, "UTF-8"), TRUE);
        
        $insertNewRow = '';
        $newLines = '';
        if (isMobile()) {
            $insertNewRow = '</tr><tr>';
            $newLines = '<br><br>';
        }
        
		return '
                <table>
                    <tr>
                        <td style="width: 50%; padding: 0px;">
                            <img style="float: right; padding-right: 50px; position: relative; height: 80%; width: 80%;" src="store/img/' . $item['image'] . '"></div>
                        </td>' . $insertNewRow . '
                        <td style="width: 50%; padding-left: 20px;">
                            <h2>' . $newLines . $item['name'] . '</h2><br>
                            <p class="large">' . $item['title'] . '</p>
                            <small>' . $item['description'] . '</small><br><br>
                            Type: ' . $item['type'] . '<br><br>
                            Category: ' . $item['category'] . '<br><br>
                            Price: ' . getInCurrentCurrency(floatval($item['price'])) . '<br><br>
                            <input type="submit" id="addToCartUp' . $id . '" class="addToCartTextInput" name="quantity" value="&#8593;" size="2"/>&nbsp;
                            <input type="text" id="addToCartTextInput' . $id . '" class="addToCartTextInput" name="quantity" value="' . $amount . '" size="2"/>&nbsp;
                            <input type="submit" id="addToCartDown' . $id . '" class="addToCartTextInput" name="quantity" value="&#8595;" size="2"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="addToCartBtn' . $id . '" href="cart.php?item=' . $id . '-' . $amount . '" class="addToCart">ADD TO CART</a>
                            <script>
                                document.getElementById("addToCartUp' . $id . '").onclick = function() {
                                    document.getElementById("addToCartTextInput' . $id . '").value = parseInt(document.getElementById("addToCartTextInput' . $id . '").value) + 1;
                                    document.getElementById("addToCartBtn' . $id . '").href = "cart.php?item=' . $id . '-" + document.getElementById("addToCartTextInput' . $id . '").value;
                                };
                                document.getElementById("addToCartDown' . $id . '").onclick = function() {
                                    if (document.getElementById("addToCartTextInput' . $id . '").value > 0) {
                                        document.getElementById("addToCartTextInput' . $id . '").value = document.getElementById("addToCartTextInput' . $id . '").value - 1;
                                        document.getElementById("addToCartBtn' . $id . '").href = "cart.php?item=' . $id . '-" + document.getElementById("addToCartTextInput' . $id . '").value;
                                    }
                                };
                            </script>
                        </td>
                    </tr>
                </table>';
	}
	
	function displayItemOnCartPresentation($id, $amount) {
        $retStr = '';
		$item = json_decode(htmlentities(mb_convert_encoding(file_get_contents("./store/items/" . $id . ".json"), 'UTF-8', 'ASCII'), ENT_SUBSTITUTE, "UTF-8"), TRUE);
        
        $insertNewRow = '';
        if (isMobile()) {
            $insertNewRow = '</tr><tr>';
        }
        
		$retStr = $retStr . '<h2>' . $item['name'] . '</h2>
				<table width="100%">
					<tr>
						<td valign="center" width="350px">
                            <img style="display: inline-block; padding-right: 50px; height: 80%; width: 80%;" src="store/img/' . $item['image'] . '"></div>
                        </td>' . $insertNewRow . '
						<td valign="top">
							<a class="large">' . $item['title'] . '</a><br><br>
                            ' . $item['description'] . '</br></br>
                            Category: ' . $item['category'] . '</br></br><br>
                            <input type="submit" id="addToCartUp' . $id . '" class="addToCartTextInput" name="quantity" value="&#8593;" size="2"/>&nbsp;
                            <input type="text" id="addToCartTextInput' . $id . '" class="addToCartTextInput" name="quantity" value="' . $amount . '" size="2"/>&nbsp;
                            <input type="submit" id="addToCartDown' . $id . '" class="addToCartTextInput" name="quantity" value="&#8595;" size="2"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="addToCartBtn' . $id . '" href="cart.php?item=' . $id . '-' . $amount . '" class="addToCart">UPDATE</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="deleteFromCart' . $id . '" href="cart.php?item=' . $id . '-0" class="addToCart">X</a>
                            <script>
                                document.getElementById("addToCartUp' . $id . '").onclick = function() {
                                    document.getElementById("addToCartTextInput' . $id . '").value = parseInt(document.getElementById("addToCartTextInput' . $id . '").value) + 1;
                                    document.getElementById("addToCartBtn' . $id . '").href = "cart.php?item=' . $id . '-" + document.getElementById("addToCartTextInput' . $id . '").value;
                                };
                                document.getElementById("addToCartDown' . $id . '").onclick = function() {
                                    if (document.getElementById("addToCartTextInput' . $id . '").value > 0) {
                                        document.getElementById("addToCartTextInput' . $id . '").value = document.getElementById("addToCartTextInput' . $id . '").value - 1;
                                        document.getElementById("addToCartBtn' . $id . '").href = "cart.php?item=' . $id . '-" + document.getElementById("addToCartTextInput' . $id . '").value;
                                    }
                                };
                            </script>
							</br></br>
                            
							Price: ' . getInCurrentCurrency(floatval($item['price'])) . ' * ' . $amount . ' = ' . getInCurrentCurrency(floatval($item['price']) * floatval($amount)) . '<span class="right"><small>ID: ' . $id . '</small></span>
						</td>
					</tr>
				</table>
				</br></br></br></br>';
        return $retStr;
	}
	
	function parseId($input) {
		$toParse = explode("-", htmlspecialchars($input));
		$idParsed = str_replace("id" , "", $toParse[0]);
		return $idParsed;
	}

	function getCurrentPriceOfCart() {
		$priceTotal = 0;
		foreach ($_SESSION['cart'] as $key => $value) {
			$idParsed = parseId($key);
			$item = json_decode(htmlentities(mb_convert_encoding(file_get_contents("./store/items/" . $idParsed . ".json"), 'UTF-8', 'ASCII'), ENT_SUBSTITUTE, "UTF-8"), TRUE);
			$priceTotal = $priceTotal + floatval($item['price']) * $value;
		}
		return $priceTotal;
	}

	function getCartText() {
		$retValue = 'CART&nbsp;';
        if (isset($_SESSION['cart'])) {
            $i = 0;
            foreach ($_SESSION['cart'] as $key => $value) {
                $i = $i + $value;
            }
            if ($i > 0) {
                if ($i == 1) {
                    $retValue = getInCurrentCurrency(getCurrentPriceOfCart()) . '&nbsp;(' . $i . '&nbsp;item)&nbsp;';
                } else {
                    $retValue = getInCurrentCurrency(getCurrentPriceOfCart()) . '&nbsp;(' . $i . '&nbsp;items)&nbsp;';
                }
            }
        }
		return $retValue;
	}
	
	function simplifyString($input) {
		$chars = array("-", ".", "=", "_", "/", ":", "{", "}", "\"", "'");
		foreach ($chars as $char) {
			$input = str_replace($char, "", $input);
		}
		$input = strtolower($input);
		return htmlspecialchars($input);
	}
    
    /*
     * Gets a formatted button containing the given text and image.
     * The Javascript given to this function will be executed on click.
     */
    function getButton($textToDisplay, $imagePath, $javascriptToCallOnClick) {
        $textToDisplay = strtoupper($textToDisplay);
        $textToDisplay = str_replace("&NBSP;", "&nbsp;", $textToDisplay);
        return '<div id="buttonRoundedEdges" onclick="' . $javascriptToCallOnClick . '" style="cursor: pointer; text-align: left;">
                <img src="' . $imagePath . '">
                <div id="buttonRoundedEdgesText" style="cursor: pointer;">' . $textToDisplay . '&nbsp;&nbsp;</div>
            </div>';
    }
    
    function error($message) {
        echo "<p style='z-index: 50005;font-size: 60px; background-color: red;'>ERROR: " . $message . "</p>";
    }
    
    function debug($message) {
        echo "<p style='z-index: 50000; font-size: 8px'>Debug: " . $message . "</p>";
    }
    
    function getProtocolName() {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }
        $protocol = $isSecure ? 'https' : 'http';
        
        return $protocol;
    }
    
    function getInCurrentCurrency($value) {
        if (strcmp(getCurrency(), "EUR") == 0) {
            return getPoundsToEuros($value) . "&euro;";
        }
        return $value . "&pound;";
    }
    
    function getInCurrentCurrencyValueOnly($value) {
        if (strcmp(getCurrency(), "EUR") == 0) {
            return getPoundsToEuros($value);
        }
        return $value;
    }
    
    function getCurrentCurrencySymbol() {
        if (strcmp(getCurrency(), "EUR") == 0) {
            return "&euro;";
        }
        return "&pound;";
    }
    
    function getPoundsToEuros($value) {
        return round(floatval(getPoundsToEurosExchangeCourse() * $value), 2);
    }
    
    function getPoundsToEurosExchangeCourse() {
        if (!file_exists("data/poundsToEurosCourse.txt") || (time() - filemtime("data/poundsToEurosCourse.txt")) > $_SESSION["dataExpiryTimeSeconds"]) {
            // make all needed variable global
            global $fixerApiKey;
            $url = "http://data.fixer.io/api/latest?access_key=" . $fixerApiKey . "&format=1&symbols=GBP&base=EUR";
            $data = 1 / floatval(getJsonFileContentAsObject(downloadFileViaCurl($url))["rates"]["GBP"]);
            writeToFile($data, "data", "poundsToEurosCourse.txt");
            return floatval($data);
        }
        return floatval(readFromFile("data/poundsToEurosCourse.txt"));
    }
    
    function getCurrentDonationProgress() {
        if (strcmp(readFromFile("data/currentDonationProgress.txt"), "") == 0) {
            writeToFile("0", "data", "currentDonationProgress.txt");
            return "0";
        }
        return readFromFile("data/currentDonationProgress.txt");
    }
    
    function setCurrentDonationProgress($currentDonationProgress) {
        writeToFile(htmlspecialchars($currentDonationProgress), "data", "currentDonationProgress.txt");
    }
    
    function getTargetDonationProgress() {
        if (strcmp(readFromFile("data/targetDonationProgress.txt"), "") == 0) {
            writeToFile("10000", "data", "targetDonationProgress.txt");
            return "10000";
        }
        return readFromFile("data/targetDonationProgress.txt");
    }
    
    function setTargetDonationProgress($targetDonationProgress) {
        writeToFile(htmlspecialchars($targetDonationProgress), "data", "targetDonationProgress.txt");
    }
    
    function readFromFile($filepath) {
        if (!file_exists($filepath)) {
            return "";
        }
		$fileToRead = fopen($filepath, "r+") or die("File not found!");
		$content = fread($fileToRead, filesize($filepath));
		fclose($fileToRead);
        return $content;
    }
    
    function writeToFile($content, $dir, $filename) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
		$fileToWrite = fopen($dir . "/" . $filename, "w+") or die("File not found!");
		fwrite($fileToWrite, $content);
		fclose($fileToWrite);
    }
    
    function getJsonFileContentAsObject($string) {
        return json_decode($string, true);
    }
    
    function downloadFileViaCurl($url) {
        if (!ini_set('default_socket_timeout', 1500)) {
            error("Unable to change socket timeout!");
        }
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_SSLVERSION, 6);
        $data = curl_exec($c);
        $error = curl_error($c); 
        curl_close($c);
        
        if ($error !== "") {
            error($error);
        }

        return $data;
    }
?>