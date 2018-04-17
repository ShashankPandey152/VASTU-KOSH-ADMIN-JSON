<?php

    $link = mysqli_connect("shareddb-g.hosting.stackcp.net","vastukosh-32353f7f","password98@","vastukosh-32353f7f");

    if($_GET['item'] == 1) {
        $cnames = Array();
        $inames = Array();
        $iimages = Array();
        $iids = Array();
        $times = Array();
        
        $query = "SELECT * FROM `items` ORDER BY `iid` DESC";
        if($result = mysqli_query($link, $query)) {
            while($row = mysqli_fetch_array($result)) {
                array_push($cnames, $row['cname']);
                array_push($inames, $row['iname']);
                $image = "http://vastukosh-com.stackstaging.com/img/items/" . $row['iimage'];
                array_push($iimages, $image);
                array_push($iids, $row['iid']);
                array_push($times, $row['time']);
            }
        }
        echo json_encode(Array("cnames" => $cnames, "inames" => $inames, "iimages" => $iimages, "iids" => $iids, "times" => $times));
    } else if($_GET['item'] == 2) {
        $cnames = Array();
        $mobiles = Array();
        $idimages = Array();
        $ids = Array();
        $times = Array();
        
        $query = "SELECT * FROM `users` ORDER BY `id` DESC";
        if($result = mysqli_query($link, $query)) {
            while($row = mysqli_fetch_array($result)) {
                array_push($cnames, $row['name']);
                array_push($mobiles, $row['mobile']);
                $image = "http://vastukosh-com.stackstaging.com/img/id/" . $row['idpic'];
                array_push($idimages, $image);
                array_push($ids, $row['id']);
                array_push($times, $row['time']);
            }
        }
        echo json_encode(Array("cnames" => $mobiles, "inames" => $cnames, "iimages" => $idimages, "iids" => $ids, "times" => $times));
    } else if($_GET['item'] == 3) {
        $cnames = Array();
        $inames = Array();
        $iimages = Array();
        $iids = Array();
        $times = Array();
        
        $query = "SELECT * FROM `give` ORDER BY `sno` DESC";
        if($result = mysqli_query($link, $query)) {
            while($row = mysqli_fetch_array($result)) {
                array_push($cnames, $row['oname']);
                array_push($inames, $row['iname']);
                $image = "http://vastukosh-com.stackstaging.com/img/items/" . $row['iimage'];
                array_push($iimages, $image);
                array_push($iids, $row['sno']);
                array_push($times, $row['time']);
            }
        }
        echo json_encode(Array("cnames" => $cnames, "inames" => $inames, "iimages" => $iimages, "iids" => $iids, "times" => $times));
    }

    if($_GET['details'] == 1) {
        $query = "SELECT * FROM `items` WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        echo json_encode(Array("itype" => $row['itype'], "isubtype" => $row['isubtype'], "count" => $row['count'], "descr" => $row['descr'], "duration" => $row['duration'], "rent" => $row['rent'], "sell" => $row['sell'], "price" => $row['price'], "ad_count" => $row['ad_count'], "ad_descr" => $row['ad_descr']));
    } else if($_GET['details'] == 2) {
        $query = "SELECT * FROM `users` WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        echo json_encode(Array("location" => $row['location'], "address" => $row['address'], "email" => $row['email'], "idno" => $row['idno'], "status" => $row['status'], "block" => $row['block']));
    } else if($_GET['details'] == 3) {
        $query = "SELECT * FROM `give` WHERE `sno` = '".mysqli_real_escape_string($link, $_GET['sno'])."'";
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        echo json_encode(Array("iid" => $row['iid'], "itype" => $row['itype'], "isubtype" => $row['isubtype'], "count" => $row['count'], "charity" => $row['charity']));
    }

    if($_GET['getOtp'] == 1) {
        $otp = rand(111111,999999);
        $sent = 0;
        
        $to = "sdharchou@gmail.com";
        $subject = "OTP for edit access";
        $message = '
OTP generated for edit access is:-
<html>
<body>
<h1 style="color: green;">'.$otp.'</h1>
<br>
<p style="color:red;">This otp will expire in a minute. Input it fast</p>
</body>
</html>
        ';
        $headers = 'From:no-reply@vastukosh.com' . "\r\n"; 
        $headers .= "Content-type: text/html; charset=iso-8859-1". "\r\n";
        if(mail($to, $subject, $message, $headers)) {
            $query = "INSERT INTO `otp`(`otp`) VALUES('".$otp."')";
            if(mysqli_query($link, $query)) {
                $sent = 1;
                echo json_encode(Array("sent" => $sent));
            }
        }
    }

    if($_GET['putOtp'] == 1) {
        $verify = 0;
        $curTime = time();
        $query = "SELECT * FROM `otp` LIMIT 1";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $date = $row['time'];
        $date = strtotime($date);
        if($curTime - $date > 60) {
            $query = "DELETE FROM `otp` WHERE `otp` = '".$row['otp']."'";
            mysqli_query($link, $query);
        }
        
        if(isset($row['otp'])) {
            if($_GET['otp'] == $row['otp']) {
                $verify = 1;
            } else {
                $verify = 2;
            }
        } else {
            $verify = 3;
        }
        
        echo json_encode(Array("verify" => $verify));
    }

    if($_GET['edit'] == 1) {
        $status = 0;
        
        if($_GET['iname'] != "") {
            $query = "UPDATE `items` SET `iname` = '".mysqli_real_escape_string($link, $_GET['iname'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['itype'] != "") {
            $query = "UPDATE `items` SET `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['isubtype'] != "") {
            $query = "UPDATE `items` SET `isubtype` = '".mysqli_real_escape_string($link, $_GET['isubtype'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['count'] != "") {
            $query = "UPDATE `items` SET `count` = '".mysqli_real_escape_string($link, $_GET['count'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['descr'] != "") {
            $query = "UPDATE `items` SET `descr` = '".mysqli_real_escape_string($link, $_GET['descr'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['duration'] != "") {
            $query = "UPDATE `items` SET `duration` = '".mysqli_real_escape_string($link, $_GET['duration'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['status'] != "") {
            $query = "UPDATE `items` SET `status` = '".mysqli_real_escape_string($link, $_GET['status'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['rent'] != "") {
            $query = "UPDATE `items` SET `rent` = '".mysqli_real_escape_string($link, $_GET['rent'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['sell'] != "") {
            $query = "UPDATE `items` SET `sell` = '".mysqli_real_escape_string($link, $_GET['sell'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['price'] != "") {
            $query = "UPDATE `items` SET `price` = '".mysqli_real_escape_string($link, $_GET['price'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['ad_count'] != "") {
            $query = "UPDATE `items` SET `ad_count` = '".mysqli_real_escape_string($link, $_GET['ad_count'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['ad_descr'] != "") {
            $query = "UPDATE `items` SET `ad_descr` = '".mysqli_real_escape_string($link, $_GET['ad_descr'])."' WHERE `iid` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        echo json_encode(Array("status" => $status));
    } else if($_GET['edit'] == 2) {
        $status = 0;
        
        if($_GET['pass'] != "") {
            $query = "UPDATE `users` SET `password` = '".mysqli_real_escape_string($link, hash('sha512', $_GET['pass']))."' WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['block'] != "") {
            $query = "UPDATE `users` SET `block` = '".mysqli_real_escape_string($link, $_GET['block'])."' WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
            if(mysqli_query($link, $query)) {
                $query = "SELECT `email` FROM `users` WHERE `id` = '".mysqli_real_escape_string($link, $_GET['id'])."'";
                $row = mysqli_fetch_array(mysqli_query($link, $query));
                
                if($_GET['block'] == 1) {
                    $to = $row['email'];
                    $subject = "Account blocked";
                    $message = '
<p>Dear customer,</p>

<p>Your account has been <span style="color: red; font-weight: bold;">blocked</span> due to violation of company\'s terms of service.</p>

<p>For further details drop us a mail at: koshvastu@gmail.com</p>
                    ';
                    $headers = 'From:noreply@vastukosh.com' . "\r\n"; 
                    $headers .= "Content-type: text/html; charset=iso-8859-1". "\r\n";
                    if(mail($to, $subject, $message, $headers)) {
                        $status += 1;
                    } else {
                        $status = -1;
                    }
                } else {
                    $status += 1;
                }
                
            } else {
                $status = -1;
            }
        }
        
        echo json_encode(Array("status" => $status));
    } else if($_GET['edit'] == 3) {
        $status = 0;
        
        if($_GET['iname'] != "") {
            $query = "UPDATE `give` SET `iname` = '".mysqli_real_escape_string($link, $_GET['iname'])."' WHERE `sno` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['count'] != "") {
            $query = "UPDATE `give` SET `count` = '".mysqli_real_escape_string($link, $_GET['count'])."' WHERE `sno` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['itype'] != "") {
            $query = "UPDATE `give` SET `itype` = '".mysqli_real_escape_string($link, $_GET['itype'])."' WHERE `sno` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['isubtype'] != "") {
            $query = "UPDATE `give` SET `isubtype` = '".mysqli_real_escape_string($link, $_GET['isubtype'])."' WHERE `sno` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        if($_GET['charity'] != "") {
            $query = "UPDATE `give` SET `charity` = '".mysqli_real_escape_string($link, $_GET['charity'])."' WHERE `sno` = '".mysqli_real_escape_string($link, $_GET['iid'])."'";
            if(mysqli_query($link, $query)) {
                $status += 1;
            } else {
                $status = -1;
            }
        }
        
        echo json_encode(Array("status" => $status));
    }

    if($_GET['mail'] == 1) {
        $status = 0;
        
        $query = "SELECT `id` FROM `users` WHERE `email` = '".mysqli_real_escape_string($link, $_GET['to'])."'";
        if(mysqli_num_rows(mysqli_query($link, $query)) == 0) {
            $status = -1;
        } else {
            $to = $_GET['to'];
            $subject = $_GET['subject'];
            $message = $_GET['content'];
            $headers = 'From:noreply@vastukosh.com' . "\r\n"; 
            $headers .= "Content-type: text/html; charset=iso-8859-1". "\r\n";
            if(mail($to, $subject, $message, $headers)) {
                $status = 1;
            } else {
                $status = 0;
            }
        }
        
        echo json_encode(Array("status" => $status));
    }

?>