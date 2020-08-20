<?php
//建议用途
//1. 安全测试：测试你用网络工具加密的代码是否可以被快速解密。
//2. 学习参考：对自己感兴趣的源码解密用于编程学习,或了解加密解密。
//3. 安全评估：解密自己用的第三方源码是否有危险行为,比如后门、间谍行为.
//不得用于
//1. 解密出售：请尊重他人劳动成果，不得公开、盗卖他人知识产权等侵权行为。
//2. 黑产：不得用于网络攻击、非法获取网络数据等违反法律法规的用途。
//版本说明
//1. 适合php单文件完整无错goto加密源码，仅供解密思路参考
 $mails = "xxxxxx@126.com";
//去行首goto+多字母;
function Trimgoto($str){
    $str = Trim($str);
    preg_match_all('/goto.+?;/', $str, $goto);
    foreach($goto[0] as $tiqu6){
        $str = Trim(str_replace("@#@".$tiqu6,"","@#@".$str));
    }
    return $str;
}

//作用：按长度降序 替换 \开头+数字字母的转义符
function Trimziti($str){
    $str = Trim($str);
    preg_match_all('/(\\\[a-zA-Z0-9_]{1,6})+/', $str, $ziti);
    foreach($ziti[0] as $tiqu6){
        $lans = strlen($tiqu6);
        $liti[$tiqu6] = $lans;
    }
    arsort($liti);
    $tiecho="";
    $numberOfFiles = sizeOf($liti);
    for($t=0;$t<$numberOfFiles;$t++){
        $thisFile = each($liti);
        $tiqu6 = $thisFile[0];
        $tiqua = $thisFile[1];
        eval("\$tsts = \"{$tiqu6}\";");
        $tiqux = addslashes($tiqu6);
        $str = str_replace($tiqux,$tsts,$str);
        $str = str_replace($tiqu6,$tsts,$str);
    }
    return $str;
}

//去行首 多字母: ;
function Trimgete($str){
    global $mails;
    $str = Trim($str);
    preg_match_all('/\w+:/', $str, $gete);
    $jj=0;
    foreach($gete[0] as $tiqu6){
        if(stristr("@#@".$str,"@#@".$tiqu6)){
            $jj++;
            $str = Trim(str_replace("@#@".$tiqu6,"","@#@".$str));
        }
    }
    if($jj>0){return $str;}else{return $mails;}
}

$efile = "./pp.php"; //要解密的文件 纯goto完整PHP源码
$code = file_get_contents($efile);

if (strlen($code)>10){
    $code1 = $code;
    $code1 =str_replace(array('<?php','?>'),"\r\n // < ?php 或 ? > \r\n",$code1);
    $code1 =str_replace(array('goto '),"\r\ngoto ",$code1);
    $codes = "";
    $tat0=explode("\r\n",$code1);
    foreach($tat0 as $tat1){
        $tat1 = Trim($tat1);
        if(strlen($tat1)>0 && stristr("@".$tat1,"@goto ")){
            $codo1 = Trimgoto($tat1);
            $codo2 = Trimgete($codo1);
            if($codo2==$mails){
                $codo3 = "\$chalida .= '" . addslashes($codo1) ."<brbr>';\r\n";
                $codel = str_replace($codo1,$codo3,$tat1);
                $codes .= $codel."\r\n";
            }else{
                $codo3 = "\$chalida .= '" . addslashes($codo2) ."<brbr>';\r\n";
                $codel = str_replace($codo2,$codo3,$tat1);
                $codes .= $codel."\r\n";
            }
        }else{
            $codes .= "\$chalida .= '" . addslashes($tat1) ."<brbr>';\r\n";
        }
    }
    file_put_contents($efile.".01.txt",$codes); //步骤一写入*.01.txt文件
    eval($codes);
    $chalide = $chalida;
    $chalide = Trimziti($chalide,"");
    $chalide = stripslashes($chalide);
    $chalide = str_replace("<brbr>","\r\n",$chalide);
    $chalide = str_replace("\r\n} ","\r\n}\r\n",$chalide);
    file_put_contents($efile.".02.txt",$chalide); //结果写入*.02.txt文件
}

