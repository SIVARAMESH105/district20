<?php session_start(); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">  

<?php

include('db.php');

//define('IMG_DIRECTORY', '/images/house');



if(isset($_COOKIE['userID']))

{

    $id = $_COOKIE['userID'];

    

    $qry = "SELECT email FROM user WHERE userID = '$id';";

    $email = mysqli_fetch_assoc(mysqli_query($conn, $qry));

    

    $qry = "SELECT * FROM billingAddress WHERE uID = '$id';";

    $billingInfo = mysqli_fetch_assoc(mysqli_query($conn, $qry));

    

    $qry = "SELECT * FROM shippingAddress WHERE uID = '$id';";

    $shippingInfo = mysqli_fetch_assoc(mysqli_query($conn, $qry));

    //FillPipe Values

    if($shippingInfo['fillPipe'] == 'Right'){

        $shippingfillPipe = 'Right Center';

    }elseif ($shippingInfo['fillPipe'] == 'Left') {

        $shippingfillPipe = 'Left Center';

    }elseif ($shippingInfo['fillPipe'] == 'Blank') {

        $shippingfillPipe = '';

    }else{

        $shippingfillPipe = $shippingInfo['fillPipe'];

    }

    

    $jsRun = 'true';

}

else

{

    $id = "''";

    $email = array('email' => '');

    $billingInfo = array('billID' => "''", 'first' => '', 'last' => '', 'address' => '', 'aptNum' => '', 'city' => '', 'state' => '', 'zip' => '', 'phone' => 'null');

    $shippingInfo = array('shipID' => '', 'first' => '', 'last' => '', 'address' => '', 'aptNum' => '', 'city' => '', 'state' => '', 'zip' => '', 'hColor' => '', 'tankSize' => '', 'fillPipe' => 'null');

    $jsRun = 'false';

}



$qry = 'SELECT price20, price50, price75, price100, serviceprice FROM prices;';

$list = mysqli_fetch_assoc(mysqli_query($conn, $qry));

$perGallon = $list['price100']/100;

$servicePrice = $list['serviceprice'];

$lowPerGallon = $perGallon + .1;



$random = rand();

$_SESSION['random'] = $random;



?>

<html>

<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <title>A&B Oil Co.- Online Ordering of Heating Oil for Boston Area </title>

    <meta name="description" content="You can order home heating oil and heating services for Dorchester,Hyde Park, Roxbury , Jamiaca Plain and Roslindale 24 hours a day 7 days a week - best prices online">

    <link rel="stylesheet" href="style2.css" type="text/css" />

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css"/>

    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>

    <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

    <script src="js/jquery-ui-timepicker-addon.js"></script>

    <script src='js/jquery.watermark.min.js'></script>

    <script src='js/jquery.maskedinput.min.js'></script>

    <?php include('layout/head_meta.php'); ?>
    <style type="text/css">
        tr.billing_hide {visibility: hidden;}
    </style>  

</head>



<body onload='calcSubTotal(); changePay();'>

    <div id='main'>

        <div id='header'>

            <?php include("layout/header.html"); ?>

        </div>

        <div id='hold'>

            <div id='side'>

                <?php include 'sidebartop.php';?>

                <div class='sideBox' id="sidebarmid">

                    <div class='sideTitle'>Contact Us!</div>

                    <div class='picDiv'><img src='images/phone.jpg' /></div>

                    <p><span class='contact'>

                        A&B Oil Co.<br />

                        567 South Street<br />

                        Quincy MA, 02169<br />

                    </span>

                    <h3>617-471-0073</h3></p>

                    <a href="mailto:aboilcompany@gmail.com"><span>aboilcompany@gmail.com</span></a>

                </div>

                <div id='widget'>

                    <script type="application/json" src="http://voap.weather.com/weather/oap/02169?template=GENXV&par=3000000007&unit=0&key=twciweatherwidget"></script>

                </div>

                <div id='widget'>

                    <script language="JavaScript" charset="utf-8" src="http://www.noozilla.com/iframe.php?type=6&bgcolor=FFFFFF&bdcolor=C5DBED&lcolor=000000&tcolor=000000&fontsize=8&box=176&window=1&font=1&bold=2&textalign=1"></script>

                </div>

            </div>

            <div id='right'>

                <div id='menu'>

                    <nav>

                        <ul>

                            <li><a href="index.php">Home</a></li>

                            <li><a href="order.php">Order Oil</a></li>

                            <li><a href="about.php">About Us</a></li>

                            <li style="width:auto"><a href="abcd-acceptance-letter.php">ABCD Acceptance Letter</a></li>

                            <li><a href="contact.php">Contact</a></li>

                        </ul>

                    </nav>

                </div>

                <div id='mainContent'>

                    <?php 

                        if (isset($_SESSION['errorform']) && !empty($_SESSION['errorform'])) { ?>

                            <span style="color:red"><?php echo $_SESSION['errorform']; ?></span>

                        <?php }; 

                        unset($_SESSION['errorform']);

                        ?>

                        <?php

                        if (isset($_GET['error'])) {

                            echo '<h3 style="color: red;" >Sorry! Something went wrong with your order. Please try again or contact the office.</h3>';

                        } else {

                            echo "<h2>All orders are always next day delivery!</h2>";

                        }

                    ?>

                    <form action="avatas1.php" method="post" onSubmit="return checkForm()">  

                        <table name="shipping-table" width="52%" style="float:left">

                            <tr>

                                <th></th>

                                <th>Delivery Address</th>

                            </tr>

                            <tr>

                                <td>First Name</td>

                                <td>

                                    <input type="text" id='ship_first' name="shipping_firstname" size="25" maxlength="20" value="" onKeyPress="return ValidateAlpha(event);" onchange='billToShip()'/>

                                </td>

                            </tr>

                            <tr>

                                <td>Last Name</td>

                                <td>

                                    <input type="text" id='ship_last' name="shipping_lastname" size="25" maxlength="20" value=""onKeyPress="return ValidateAlpha(event);"  onchange='billToShip()'/>

                                </td>

                            </tr>

                            <tr>

                                <td>Address</td>

                                <td>

                                    <input type="text" id='ship_address' name="shipping_address" size="25" maxlength="40" value="" onchange='billToShip()'/>

                                </td>

                            </tr>

                            <tr>

                                <td>Apartment</td>

                                <td>

                                    <input type="text" id='ship_apt' name="shipping_apt" size="25" maxlength="40" value="" onchange='billToShip()'/>

                                </td>

                            </tr>

                            <tr>

                                <td>City</td>

                                <td>

                                    <select id='ship_city' name="shipping_city" onchange='billToShip()'>

                                        <option value='Boston'>Boston</option>

                                        <option value='Dorchester'>Dorchester</option>

                                        <option value='Hyde Park'>Hyde Park</option>

                                        <option value='Jamaica Plain'>Jamaica Plain</option>

                                        <option value='Mattapan'>Mattapan</option>

                                        <option value='Milton'>Milton</option>

                                        <option value='Quincy'>Quincy</option>

                                        <option value='Roslindale'>Roslindale</option>

                                        <option value='Roxbury'>Roxbury</option>

                                        <option value='South Boston'>South Boston</option>

                                        <option value='West Roxbury'>West Roxbury</option>

                                    </select>

                                </td>

                            </tr>

                            <tr>

                                <td>State</td>

                                <td>

                                    <input type="text" id='ship_state' name="shipping_state" size="2" maxlength="2" value="MA" readonly onchange='billToShip()'/>

                                </td>

                            </tr>

                            <tr>

                                <td>Zip Code</td>

                                <td>

                                    <input type="text" id='ship_zip' name="shipping_zip" size="6" maxlength="5" value=""  onchange='billToShip()'/>

                                </td>

                            </tr>

                            <tr>

                                <td style="padding-top: 30px">Phone</td>

                                <td style="padding-top: 30px">

                                    <input type="text" id='phone' class="phone" name="phone" />

                                </td>

                            </tr>

                            <tr>

                                <td>E-mail</td>

                                <td>

                                    <input type="text" id='email' name="email" size="25" maxlength="75" value=''/>

                                </td>

                            </tr>

                            <tr>

                                <td>Color of House</td>

                                <td>

                                    <input type="text" id='hColor' name="hColor" size="18" maxlength="20" onKeyPress="return ValidateAlpha(event);" value=''/>

                                </td>

                            </tr>

                            <tr <?php if($_GET['type'] == 'service'){?> style="display: none;" <?php }?> >
                                    <td style="vertical-align: top;">Number of gallons</td>
                                    <td>
                                        <select id='numGal' name="numGal" onchange="calcSubTotal()">

                                            <option value="0" >0</option>

                                            <option value="1" >1</option>

                                            <option value="20" >20</option>

                                            <option value="50">50</option>

                                            <option value="75">75</option>

                                            <option value="100">100</option>

                                            <option value="125">125</option>

                                            <option value="150" selected='selected'>150</option>

                                            <option value="175">175</option>

                                            <option value="200">200</option>

                                            <option value="225">225</option>

                                            <option value="250">250</option>

                                        </select>
                                        <span class="price-info"> Price per Gallon ( <strong>$ <span id="perGallon"> </span> </strong>)</span><span class="price-info"> Total Cost ( <strong>$ <span id="totalCost"> </span></strong>)</span>
                                    </td>
                                </tr>

                            <tr>

                                <td style="padding-top: 15px">Payment Type</td>

                                <td style="padding-top: 15px">

                                    <select id='payType' name='payType' onchange='calcSubTotal(); changePay();'>

                                        <option selected='selected' value='cc' size='30'>Credit Card</option>

                                        <option  value='cash'>Cash / Check</option>

                                    </select>

                                </td>

                            </tr>

                            <tr class="ccInput">

                                <td>Credit Card Number</td>

                                <td>

                                    <input type='text' id='ccNum' name='ccnumber' size='16' maxlength='19'/>

                                </td>

                            </tr>

                            <tr class="ccInput">

                                <td>Expiration Date</td>

                                <td>

                                    <!--<input type='text' id='ccexp' name='ccexp' size='3' maxlength='5'/>-->

                                    <input type='hidden' id='ccexp' name='ccexp' size='3' maxlength='5' value="" />

                                    <input type='text' id='ccexpmonth' name='ccexpmonth' onchange = 'concatccexp()' size='2' maxlength='2' placeholder='MM' style='margin-right:10px;' /><input type='text' id='ccexpyear' name='ccexpyear' onchange = 'concatccexp()' size='2' maxlength='2' placeholder='YY'/>

                                </td>

                            </tr>

                            <tr class="ccInput">

                                <td>Verification Code</td>

                                <td>

                                    <input type='text' id='cvv' name='cvv' size='3' maxlength='3'/>

                                </td>

                            </tr>

                            <tr class="cashInput">

                                <td>Payment Location</td>

                                <td>

                                    <textarea rows='2' id='payLoc' class="payLoc" name='payLoc'></textarea value=''>

                                    </td>

                                </tr>

                                <tr class="cashInput">

                                    <td></td>

                                    <td>OR <input type='checkbox' class="custom-agree" id='payLocCheck' onclick='someoneHome()'/>Someone will be home</td>

                                </tr>

                                <tr>

                                    <td colspan='4'>



                                    </td>

                                </tr>

                            </table>

                            <table name="billing-table" width="48%" style="float:right">

                                <tr class="billing_hide">

                                    <th>Billing Address</th>



                                </tr>

                                <tr class="billing_hide">

                                    <td>

                                        <table style="border-spacing: 0;">

                                            <tr>

                                                <td style="vertical-align: top;">

                                                    <input type="text" id='first' name="firstname" size="25" maxlength="20" value="" onKeyPress="return ValidateAlpha(event);" onchange='billToShip()'/>

                                                </td>

                                                <td class='smallText term-text'>

                                                    <input type='checkbox' class="custom-agree" id='sameShip' checked="checked" onchange='billToShip()'/>Shipping and Billing address are the same

                                                </td>

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

                                <tr class="billing_hide">

                                    <td>

                                        <input type="text" id='last' name="lastname" size="25" maxlength="20" value="" onKeyPress="return ValidateAlpha(event);" onchange='billToShip()'/>

                                    </td>

                                    <!-- <td class="image-section" rowspan="8" style="display: none">

                                        <img id="loading_image" src="" alt="">

                                        <img id="address_image" src="" alt="">

                                    </td> -->

                                </tr>

                                <tr class="billing_hide">

                                    <td>

                                        <input type="text" id='address' name="address1" size="25" maxlength="40" value="" onchange='billToShip()'/>

                                    </td>



                                </tr>

                                <tr class="billing_hide">

                                    <td>

                                        <input type="text" id='apt' name="apt" size="25" maxlength="40" value="" onchange='billToShip()'/>

                                    </td>

                                </tr>

                                <tr class="billing_hide">

                                    <td>

                                        <input type="text" id='city' name="city" size="20" maxlength="20" value="" onchange='billToShip()'/>

                                    </td>

                                </tr>

                                <tr class="billing_hide">

                                    <td>

                                        <input type="text" id='state' name="state" readonly size="2" maxlength="2" value="MA" onchange='billToShip()'/>

                                    </td>

                                </tr>

                                <tr class="billing_hide">

                                    <td>

                                        <input type="text" id='zip' name="zip" size="6" maxlength="5" value="" onchange='billToShip()'/>

                                    </td>

                                </tr>

                                <tr>

                                    <td style="padding-top: 30px; line-height: 1.5;">

                                        <span><strong>This is your house.</strong></span>

                                        <span class="pipe-text" style="display: none"><br/>Tell us where the pipe is,</span>

                                        <div class="location-selector" style="max-width: 300px;">

                                            <div class="back">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Rear Left">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Rear Center">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Rear Right">

                                            </div>

                                            <div class="left">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Left Back">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Left Center">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Left Front">

                                            </div>

                                            <div class="fill-pipe"><i class="fa fa-home"></i></div>

                                            <div class="right">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Right Back">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Right Center">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Right Front">

                                            </div>

                                            <div class="front">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Front Left">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Front Center">

                                                <input type="radio" class ="pipe-location" name="fillPipe" value="Front Right">

                                            </div>

                                        </div>

                                        <div class="fill-location-text">

                                            <span class="badge badge-medium" style="text-align: center"></span>

                                        </div>





                                    </td>

                                </tr>

                                <tr><td><input id="custom-order-website" name="custom_order_website" type="text" value=""  /></td></tr>

                            </table>

                            <div class="checkbox-section" style="clear:both;line-height: 1.5;display: inline-block;

                            width: 100%;">

                            <em><span class="service-msg" style="color:red"></span></em><br/>



                            <input type='checkbox' class="custom-agree" id="serviceContract" name='serviceContract' value='Y' <?php echo ($_GET['type'] == 'service' ? 'checked' : ''); ?>>Check box to Buy Service Contract. Cost is $FREE( This year 2019 - 2020 season ) for one winter season August 1st through to June 30th. <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please note contract does not include annual cleaning ($135.00). The Office will call you to schedule a cleaning day.<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;There is a $35.00 service call charge each time a tech is called to you home. All oil burner PARTS AND LABOR ARE FREE. <br/>

                            <input type='checkbox' class="custom-agree" name='firstTime' value='checked'/>Check this box to print a sign for your door or mail box. It will read "A&B OIL DELIVERY HERE". <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We ask that you always do this to help the drivers not make any mistakes.<br/>

                            <span id='certBox'>

                                <input type='checkbox' class="custom-agree" name='cert' id='cert'/></span>By checking this box you certify that your oil tank is installed correctly, in proper working order and is not leaking in any way.<br/>



                                <div style="padding-top: 15px" > <strong>Total : &nbsp;&nbsp;$<span id='total'></span></strong></div>



                                <input type='hidden' name='userID' id='userID' value='<?php echo $id; ?>'>

                                <input type='hidden' name='billID' id='billID' value=''>

                                <input type='hidden' name='shipID' id='shipID' value=''>

                                <input type='hidden' name='onlineOrder' id='onlineOrder' value='1'>

                                <input type='hidden' name='servicePeriod' id='servicePeriod' value=''>

                                <input type='hidden' name='rand_hidden' id='rand_hidden' value='<?php echo $random; ?>'>

                                <input type='hidden' name='amount' id='amount' value=''><br/>

                                <?php

                                if($_GET['type'] == 'service'){ ?>

                                    <input type='hidden' name='service_page' id='servicepage' value='servicepage'><br/>

                                <?php

                                }

                                $sysipaddress = $_SERVER['REMOTE_ADDR'];

                                $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";  

                                $visitor = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                                ?>

                                <input type='hidden' name='custom_visitor' id='customvisitor' value='<?php echo $visitor; ?>'>

                                <input type='hidden' name='sysip_address' id='sysip' value='<?php echo $sysipaddress; ?>'>

                                <input class="myButton" type="submit" value="&nbsp;&nbsp;&nbsp;Order&nbsp;&nbsp;&nbsp;"/>

                            </div>

                        </form>

                    </div>

                </div>

                <div style="clear:both;"></div>

            </div>

            <div id='footer' align='center' >

                <?php include 'layout/footer.html';?>

            </div>

        </div>

    </body>

    <style type="text/css">

        input[type="text"]#custom-order-website { display: none; }

    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.60/inputmask/jquery.inputmask.js"></script>



    <script type='text/javascript'>

        $('form').submit(function(){    

                if ($('input#custom-order-website').val().length != 0) {

                    return false;

                } 

        });

        

        $(document).ready(function () {


            calcSubTotal();
            changePay();  
            $('#ship_state').inputmask({ "mask": "aa", 'autoUnmask' : true});

            $('#state').inputmask({ "mask": "aa", 'autoUnmask' : true});

            $('#ccexp').inputmask({ "mask": "99/99", 'autoUnmask' : true});

            $('#cvv').inputmask({ "mask": "999", 'autoUnmask' : true});



            $('#ship_zip').inputmask({ "mask": "99999", 'autoUnmask' : true});

            $('#phone').inputmask({ "mask": "(999)999-9999", 'autoUnmask' : true});

            $('#zip').inputmask({ "mask": "99999", 'autoUnmask' : true});



            $('#phone').on('focus click', function() {

                if ($(this).val() != '(___)___-____'){

                }else{

                    $(this)[0].setSelectionRange(1, 1); 

                }

            });



            $('#ship_zip').on('focus click', function() {

                if ($(this).val() != '_____'){

                }else{

                    $(this)[0].setSelectionRange(0, 0); 

                }

            });



            $('#zip').on('focus click', function() {

                console.log($(this).val());

                if ($(this).val() != '_____'){

                }else{

                    $(this)[0].setSelectionRange(0, 0); 

                }

            });



            $('.pipe-location').on('click', function() {

                    //console.log('tankSize is clicked');

                    var tankSize = $(this).val();

                    //console.log(tankSize);

                    $('.fill-location-text span').text(tankSize);

                    $('.fill-location-text').show();

                });

                //zipcode lookup ajax

                $('#ship_city').on('change', function(){

                    $('#ship_zip').prop('readonly', false);
                    $('#zip').prop('readonly', false);
                    $('#ship_zip').val("");  
                    $('#zip').val("");  

                    var cityEntered = $('#ship_city').val();

                    if( cityEntered !=="" ){

                        $.ajax({

                            url: "ajax_zipcode_lookup.php",

                            type: "post",

                            data : {

                                'city' : cityEntered,

                            },

                            dataType  : 'json',

                            success: function (response) {

                                 if (response.zipcode) {
                                    $('#ship_zip').val(response.zipcode);
                                    $('#zip').val(response.zipcode);
                                    $('#ship_zip').prop('readonly', true);
                                    $('#zip').prop('readonly', true);
                                }  

                            },

                            error: function(jqXHR, textStatus, errorThrown) {



                            }

                        });

                    }

                });



        // find customer information based on email, address and apartment field entered

        $('#ship_city').on('blur', function(){

            var ship_val = $('#ship_zip').val(); 
            if(ship_val){
                $('#phone').focus();  
            }else{
                $('#ship_zip').focus(); 
            }  

            var addressEntered = $.trim($("#ship_address").val());

            var apmtEntered = $.trim($("#ship_apt").val());

            var userID = $("#userID").val();

            var cityEntered = $('#ship_city').val();



            if(addressEntered !== "" || cityEntered !=="" ){

                /*$("#address_image").attr("src", '');

                $('.image-section').show();

                $("#loading_image").attr("src", '/images/ajax-loading.gif');

                $('#loading_image').show().delay(5000).fadeOut();*/

                $.ajax({

                    url: "image_ajaxlookup.php",

                    type: "post",

                    data : {

                        'address' : addressEntered,

                        'apartment': apmtEntered,

                        'cityEntered' : cityEntered,

                        'userID': userID

                    },

                    dataType  : 'json',

                    success: function (response) {

                        if(response.imageURL != null){

                            $('#ship_zip').val(response.pincode);

                            $('#zip').val(response.pincode);

                            $('#fill_image').remove();

                            //$("#address_image").attr("src", response.imageURL);

                            $('.pipe-text').show();

                            $(".location-selector").css('height','auto');

                            $(".location-selector").css('padding','20px 3px');

                            $(".location-selector").css('width','100%');

                            $(".location-selector .left").css('padding-top','0');

                            $(".location-selector .right").css('padding-top','0');

                            $(".fill-pipe").append('<img id="fill_image" src="'+response.imageURL+'" alt="" width="250" height="250">');

                            $(".fill-pipe i").remove();

                        }else{

                            $(".location-selector").css('height','11rem');

                            $(".location-selector").css('padding','0');

                            $(".location-selector").css('width','11rem');

                            $(".location-selector .left").css('padding-top','0px');

                            $(".location-selector .right").css('padding-top','0px');

                            $('.pipe-text').hide();

                            $("#fill_image").remove();

                            $(".fill-pipe i").remove();

                            $(".fill-pipe").append('<i class="fa fa-home"></i>');



                        }

                    },

                    error: function(jqXHR, textStatus, errorThrown) {

                        //$('.image-section').hide();

                        //console.log(textStatus, errorThrown);

                    }

                });

            }

        });



        //service contract checkbox

        $('#serviceContract').click(function() {

            var serviceRequest = ($('#serviceContract').prop('checked'))? 1:0;



            if(serviceRequest){

                serviceContactSection();

            }else{

                $('.price-info').show();

                $('#servicePeriod').val('');

                $(".service-msg").html('');

                $('#numGal').val(150);

                $("#payType").append('<option value="cash">Cash / Check</option>');

                calcSubTotal();

            }

        });

    });



    function serviceContactSection(){

        $('.price-info').hide();

        $('#numGal').val(0);

        calcSubTotal();

        //$("#payType option[value='cash']").remove();

        var currentDate = new Date();

        var serviceSeason;



        currentDay = currentDate.getDate();



        currentMonth = currentDate.getMonth()+1;



        currentYear = currentDate.getFullYear();



        var presentDate  = (currentMonth<10 ? '0' : '') + currentMonth + '-'

        + (currentDay<10 ? '0' : '') + currentDay + "-"

        + currentYear;



        var EndDate    = '06-01-'+ currentYear;



        if(Date.parse(presentDate) <= Date.parse(EndDate)){

            contractEndYear = parseInt(currentYear)+1;

            serviceSeason = 'Sept 1st, '+currentYear +' through June 1st, '+ contractEndYear;

        }else{

            contractStartYear = parseInt(currentYear)+1;

            contractEndYear = parseInt(currentYear)+2;

            serviceSeason = 'Sept 1st, '+ contractStartYear +' through June 1st, '+contractEndYear;

        }

        $('#servicePeriod').val(serviceSeason);



        $(".service-msg").html('Service Contract will be from <b>'+serviceSeason+'</b>.' );

    }

    //*********************************************************************//

    //********************** Fill Forms ***********************************//

    if(<?php echo $jsRun; ?>)

    {

        document.getElementById('first').value = '<?php echo $billingInfo['first']; ?>';

        document.getElementById('last').value = '<?php echo $billingInfo['last']; ?>';

        document.getElementById('address').value = '<?php echo $billingInfo['address']; ?>';

        document.getElementById('apt').value = '<?php echo $billingInfo['aptNum']; ?>';

        document.getElementById('city').value = '<?php echo $billingInfo['city']; ?>';

        document.getElementById('state').value = '<?php echo $billingInfo['state']; ?>';

        document.getElementById('zip').value = '<?php echo $billingInfo['zip']; ?>';

        document.getElementById('email').value = '<?php echo $email['email']; ?>';

        document.getElementById('phone').value = '<?php echo $billingInfo['phone']; ?>';



        document.getElementById('ship_first').value = '<?php echo $shippingInfo['first']; ?>';

        document.getElementById('ship_last').value = '<?php echo $shippingInfo['last']; ?>';

        document.getElementById('ship_address').value = '<?php echo $shippingInfo['address']; ?>';

        document.getElementById('ship_apt').value = '<?php echo $shippingInfo['aptNum']; ?>';

        document.getElementById('ship_city').value = '<?php echo $shippingInfo['city']; ?>';

        document.getElementById('ship_state').value = '<?php echo $shippingInfo['state']; ?>';

        document.getElementById('ship_zip').value = '<?php echo $shippingInfo['zip']; ?>';



        document.getElementById('hColor').value = '<?php echo $shippingInfo['hColor']; ?>';

        //document.getElementById('tankSize').value = '<?php //echo $shippingInfo['tankSize']; ?>';

        //document.getElementById('fillPipe').value = '<?php //echo $shippingInfo['fillPipe']; ?>';

        $('input:radio[name="fillPipe"][value="<?php echo $shippingfillPipe; ?>"]').attr('checked',true);



        document.getElementById('userID').value = <?php echo $id; ?>;

        document.getElementById('billID').value = <?php echo $billingInfo['billID']; ?>;

        document.getElementById('shipID').value = '<?php echo $shippingInfo['shipID']; ?>';

    }



    function billToShip()

    {

        if(document.getElementById('sameShip').checked == true)

        {

            if(document.getElementById('ship_first').value != '')

            {

                document.getElementById('first').value = document.getElementById('ship_first').value;

            }

            if(document.getElementById('first').value != '')

            {

                document.getElementById('ship_first').value = document.getElementById('first').value;

            }



            if(document.getElementById('ship_last').value != '')

            {

                document.getElementById('last').value = document.getElementById('ship_last').value;

            }

            if(document.getElementById('last').value != '')

            {

                document.getElementById('ship_last').value = document.getElementById('last').value;

            }

            if(document.getElementById('ship_address').value != '')

            {

                document.getElementById('address').value = document.getElementById('ship_address').value;

            }

            if(document.getElementById('address').value != '')

            {

                document.getElementById('ship_address').value = document.getElementById('address').value;

            }



            if(document.getElementById('ship_apt').value != '')

            {

                document.getElementById('apt').value = document.getElementById('ship_apt').value;

            }

            if(document.getElementById('apt').value != '')

            {

                document.getElementById('ship_apt').value = document.getElementById('apt').value;

            }



            if(document.getElementById('ship_city').value != '')

            {

                document.getElementById('city').value = document.getElementById('ship_city').value;

            }

            if(document.getElementById('city').value != '')

            {

                var element = document.getElementById('ship_city');

                var city = document.getElementById('city').value;

                $('#ship_city').val(city);

            }



            if(document.getElementById('ship_state').value != '')

            {

                document.getElementById('state').value = document.getElementById('ship_state').value;

            }

            if(document.getElementById('state').value == '')

            {

                document.getElementById('ship_state').value = document.getElementById('state').value;

            }



            if(document.getElementById('ship_zip').value != '')

            {

                document.getElementById('zip').value = document.getElementById('ship_zip').value;

            }

            if(document.getElementById('zip').value != '')

            {

                document.getElementById('ship_zip').value = document.getElementById('zip').value;

            }

        }

    }



    function someoneHome()

    {

        if(document.getElementById('payLocCheck').checked == true)

        {

            document.getElementById('payLoc').value = 'Someone will be home';

        }

        else

        {

            document.getElementById('payLoc').value = '';

        }

    }



    //************************************************************************//

    //*********************** Manage Prices **********************************//



    var list = new Array ( <?php echo $list['price20'] . ", " . $list['price50'] . ", " . $list['price75'] . ", " . $perGallon . ", " . $lowPerGallon . ", " . $servicePrice . ""; ?> );



    var subTotal;

    var total;

    function calcSubTotal()

    {

        var elt = document.getElementById('numGal');

        var numGallons = elt.options[elt.selectedIndex].value;

        if(numGallons != 0){

            if(numGallons == 1)

            {

                total = parseFloat(list[3]);

                currentPrice = total;

            }

            else if(numGallons == 20)

            {

                total = parseFloat(list[0]);

                currentPrice = list[0]/20;

            }

            else if(numGallons == 50)

            {

                total = parseFloat(list[1]);

                currentPrice = list[1]/50;

            }

            else if(numGallons == 75)

            {

                total = parseFloat(list[2]);

                currentPrice = list[2]/75;

            }

            else if(numGallons >= 100 && numGallons < 150)

            {

                total = parseFloat(list[4]) * numGallons;

                currentPrice = list[4];

            }

            else if(numGallons >= 150)

            {

                total = parseFloat(list[3]) * numGallons;

                currentPrice = list[3];

            }

        } else{

            total = parseFloat(list[5]) ;

        }





        document.getElementById('amount').value = total;

        total = total.toFixed(2);



        document.getElementById("perGallon").innerHTML=currentPrice.toFixed(2);

        document.getElementById("totalCost").innerHTML=total;



        document.getElementById('total').innerHTML = total;

    }



    function changePay()

    {

        var elt = $( "#payType" ).val();

        if(elt == 'cc')

        {

            $('.ccInput').css('display', 'table-row');

            //$('.cashInput').css('display', 'none');

            $('.payLoc').val('');

        }

        else if(elt == 'cash')

        {

            $('.ccInput').css('display', 'none');

            $('.cashInput').css('display', 'table-row');

        }

    }



    //************************************************************************************//

    //*********************** Form Verification ******************************************//



    function checkElement(itemID)

    {

        var elementPass;

        if(document.getElementById(itemID).value.trim() == null || document.getElementById(itemID).value.trim() == "")

        {

            elementPass = false;

            console.log('empty form item ' + itemID);

        }

        else

        {

            elementPass = true;

        }

        return elementPass;

    }



    function outlineElement(itemID)

    {

        if(document.getElementById(itemID).value.trim() == null || document.getElementById(itemID).value.trim() == "")

        {

            document.getElementById(itemID).className = 'error';

        }

        else

        {

            document.getElementById(itemID).className = '';

        }



        if(itemID == 'cert')

        {

            if(document.getElementById(itemID).checked == false)

            {

                document.getElementById('certBox').className = 'error';

            }

            else

            {

                document.getElementById('certBox').className = '';

            }

        }

    }





    function checkForm()

    {

        outlineElement('first');

        outlineElement('last');

        outlineElement('address');

        outlineElement('city');

        outlineElement('state');

        outlineElement('zip');

        outlineElement('phone');

        outlineElement('email') ;

        outlineElement('ship_first');

        outlineElement('ship_last');

        outlineElement('ship_address');

        outlineElement('ship_city');

        outlineElement('ship_state');

        outlineElement('ship_zip');

        outlineElement('hColor');

        outlineElement('ccNum');

        outlineElement('ccexpmonth');

        outlineElement('ccexpyear');

        outlineElement('cvv');

        outlineElement('payLoc');

        outlineElement('cert');



        var formPass = true;

        if(

            checkElement('first') == false ||

            checkElement('last') == false ||

            checkElement('address') == false ||

            checkElement('city') == false ||

            checkElement('state') == false ||

            checkElement('zip') == false ||

            checkElement('phone') == false ||

            checkElement('email') == false ||

            checkElement('ship_first') == false ||

            checkElement('ship_last') == false ||

            checkElement('ship_address') == false ||

            checkElement('ship_city') == false ||

            checkElement('ship_state') == false ||

            checkElement('ship_zip') == false ||

            checkElement('hColor') == false ||

            document.getElementById('cert').checked == false)

        {

            formPass = false;

            console.log('failed form 1');

        }

        else

        {

            var elt = document.getElementById('payType');

            if(elt.options[elt.selectedIndex].value == 'cc')

            {

                if( checkElement('ccNum') == false ||

                    checkElement('ccexpmonth') == false ||

                    checkElement('ccexpyear') == false ||

                    checkElement('cvv') == false)

                {

                    formPass = false;

                }

            }

            else if(elt.options[elt.selectedIndex].value == 'cash')

            {

                if(checkElement('payLoc') == false)

                {

                    formPass = false;

                }

            }



        }

        return formPass;

    }



    function concatccexp()

    {

        var month = $('#ccexpmonth').val();

        var year = $('#ccexpyear').val();

        var ccexpconcat = month+'/'+year;

        $('#ccexp').val(ccexpconcat);



    }



    /*Validate Alphapet*/

    function ValidateAlpha(evt) 

    {

        var keyCode = (evt.which) ? evt.which : evt.keyCode

        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)

        return false;

        return true;

    }



    $(window).load(function(){

        var addressEntered = $.trim($("#ship_address").val());

        var apmtEntered = $.trim($("#ship_apt").val());

        var userID = $("#userID").val();

        var cityEntered = $('#ship_city').val();



        //get fillPipe value

        var fillPipeEntered = $("input[name='fillPipe']:checked").val();

        if(fillPipeEntered != ""){

            $('.fill-location-text span').text(fillPipeEntered);

            $('.fill-location-text').show();

        }



        if(addressEntered !== "" || cityEntered !=="" ){

            $.ajax({

                url: "image_ajaxlookup.php",

                type: "post",

                data : {

                    'address' : addressEntered,

                    'apartment': apmtEntered,

                    'cityEntered' : cityEntered,

                    'userID': userID

                },

                dataType  : 'json',

                success: function (response) {

                    if(response.imageURL != null){

                        $('#ship_zip').val(response.pincode);

                        $('#zip').val(response.pincode);

                        $('#fill_image').remove();

                        $('.pipe-text').show();

                        $(".location-selector").css('height','auto');

                        $(".location-selector").css('padding','20px 3px');

                        $(".location-selector").css('width','100%');

                        $(".location-selector .left").css('padding-top','25px');

                        $(".location-selector .right").css('padding-top','25px');

                        $(".fill-pipe").append('<img id="fill_image" src="'+response.imageURL+'" alt="" width="200" height="200">');

                        $(".fill-pipe i").remove();

                    }else{

                        console.log('esle part');

                        $(".location-selector").css('height','11rem');

                        $(".location-selector").css('padding','0');

                        $(".location-selector").css('width','11rem');

                        $(".location-selector .left").css('padding-top','0px');

                        $(".location-selector .right").css('padding-top','0px');

                        $('.pipe-text').hide();

                        $("#fill_image").remove();

                        $(".fill-pipe i").remove();

                        $(".fill-pipe").append('<i class="fa fa-home"></i>');



                    }

                },

                error: function(jqXHR, textStatus, errorThrown) {

                    //$('.image-section').hide();

                        //console.log(textStatus, errorThrown);

                    }

                });

        }



        var serviceRequest = ($('#serviceContract').prop('checked'))? 1:0;



        if(serviceRequest){

            serviceContactSection();

        }



        $('input[placeholder]').on('paste', function(){

    $(this).val(' ');

});



    });

    // For Paste Validation using javascript concept 

    window.onload = function() {

        const ship_first = document.getElementById('ship_first');

            ship_first.onpaste = function(e) {

            e.preventDefault();  

        }

        const billing_first = document.getElementById('first');

            billing_first.onpaste = function(e) {

            e.preventDefault();  

        }

        const ship_last = document.getElementById('ship_last');

            ship_last.onpaste = function(e) {

            e.preventDefault();  

        }

        const billing_last = document.getElementById('last');

            billing_last.onpaste = function(e) {

            e.preventDefault();  

        }  

          

        const ship_address = document.getElementById('ship_address');

            ship_address.onpaste = function(e) {

            e.preventDefault();  

        } 

        const billing_address = document.getElementById('address');

            billing_address.onpaste = function(e) {

            e.preventDefault();  

        }   

        const ship_apt = document.getElementById('ship_apt');

            ship_apt.onpaste = function(e) {

            e.preventDefault();  

        }  

        const billing_apt = document.getElementById('apt');

            billing_apt.onpaste = function(e) {

            e.preventDefault();  

        }       

        const ship_state_field = document.getElementById('ship_state');

            ship_state_field.onpaste = function(e) {

            e.preventDefault();  

        }  

        const billing_state_field = document.getElementById('state');

            billing_state_field.onpaste = function(e) {

            e.preventDefault();  

        }  

        const ship_zip = document.getElementById('ship_zip');

            ship_zip.onpaste = function(e) {

            e.preventDefault();     

        } 

        const billing_zip = document.getElementById('zip');

            billing_zip.onpaste = function(e) {

            e.preventDefault();     

        }

        const billing_city = document.getElementById('city');

            billing_city.onpaste = function(e) {

            e.preventDefault();     

        }    

        

        const phone_field = document.getElementById('phone');

            phone_field.onpaste = function(e) {

            e.preventDefault();  

        }

        const email_field = document.getElementById('email');

            email_field.onpaste = function(e) {

            e.preventDefault();  

        } 

        const hcolor_field = document.getElementById('hColor');

            hcolor_field.onpaste = function(e) {

            e.preventDefault();  

        } 

        const ccNum_field = document.getElementById('ccNum');

            ccNum_field.onpaste = function(e) {

            e.preventDefault();  

        }

        const cvv_field = document.getElementById('cvv');

            cvv_field.onpaste = function(e) {

            e.preventDefault();  

        } 

        const ccexpmonth = document.getElementById('ccexpmonth');

            ccexpmonth.onpaste = function(e) {

            e.preventDefault();  

        }  

        const ccexpyear = document.getElementById('ccexpyear');

            ccexpyear.onpaste = function(e) {

            e.preventDefault();  

        }  

        const payLoc_field = document.getElementById('payLoc');

            payLoc_field.onpaste = function(e) {

            e.preventDefault();  

        }             



    }  

</script>



</html>