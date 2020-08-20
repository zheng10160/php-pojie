<?php

global $_W, $_GPC;
if ($_W["container"] == "wechat") {
$openid = $_W["openid"];
$member = pdo_fetch("select * from " . tablename(GARCIA_PREFIX . "members") . " where weid = :weid and openid = :openid", array(":weid" => $_W["uniacid"], ":openid" => $openid));
$memberid = $member["id"];
$basic = pdo_fetch("select logo,company,hreftype,hrefstr,bdapi,webshow,copyright,xieyi,sharetitle as title,sharedesc as `desc`,sharepic as imgUrl from " . tablename(GARCIA_PREFIX . "basicsetting") . " where weid = :weid", array(":weid" => $_W["uniacid"]));
if (empty($basic)) {
$item = pdo_fetch("select * from " . tablename(GARCIA_PREFIX . "houses") . " where weid = :weid and id = :id", array(":weid" => $_W["uniacid"], ":id" => $_GPC["id"]));
if (!empty($item)) {
$telephone = $item["telephone"];
$commiss = pdo_fetch("select commission from " . tablename(GARCIA_PREFIX . "commissionhouse") . " where weid = :weid and houseid = :houseid and status = 0 and from_unixtime(endtime,'%Y%m%d') >= from_unixtime(" . TIMESTAMP . ",'%Y%m%d') ", array(":weid" => $_W["uniacid"], ":houseid" => $item["id"]));
if (!empty($commiss)) {
$item["commission"] = $commiss["commission"];
if (empty($item)) {
if (!($_W["config"]["setting"]["cache"] == "redis")) {
$promotelist = pdo_fetch("select posters from " . tablename(GARCIA_PREFIX . "promotelist") . " where weid = :weid and memberid = :memberid and houseid = :houseid ", array(":weid" => $_W["uniacid"], ":memberid" => $memberid, ":houseid" => $item["id"]));
if ($member["identity"] != 0 && empty($promotelist["posters"]) && $item["commission"] != 0) {
$promote = pdo_fetch("select * from " . tablename(GARCIA_PREFIX . "promote") . " where weid = :weid ", array(":weid" => $_W["uniacid"]));
$_poster = TIMESTAMP;
$_qrFileDir = IA_ROOT . "/addons/" . $this->modulename . "/template/upload/posters/posters_" . $_poster . ".png";
$_savefile = "/addons/" . $this->modulename . "/template/upload/posters/posters_" . $_poster . ".png";
$urlstr = $_W["siteroot"] . "/app/index.php?i=" . $_W["uniacid"] . "&fromid={$memberid}&id={$_GPC["id"]}&c=entry&do=houses_detail&m=" . $this->module["name"];
$value = $item["id"] . "_" . $item["mobile"];
$errorCorrectionLevel = "L";
$matrixPointSize = 10;
$url = IA_ROOT . "/addons/" . $this->modulename . "/template/upload/posters/" . $value . ".png";
$qrcode = QRcode::png($urlstr, $url, $errorCorrectionLevel, $matrixPointSize, 2);
$_qrImg = $this->openImg($this->changeImgSize($url, $value, 300, 300, true));
$_posterImg = $this->openImg($this->changeImgSize(tomedia($promote["posters"]), "tp", 640, 800));
imagecopyresampled($_posterImg, $_qrImg, 330, 490, 0, 0, 300, 300, 300, 300);
imagejpeg($_posterImg, $_qrFileDir);
imagedestroy($_posterImg);
imagedestroy($_qrImg);
pdo_insert(GARCIA_PREFIX . "promotelist", array("weid" => $_W["uniacid"], "memberid" => $memberid, "houseid" => $_GPC["id"], "posters" => $_savefile, "addtime" => TIMESTAMP));
$promote = $_savefile;
$policylist = pdo_getall(GARCIA_PREFIX . "policy", array("weid" => $this->weid), array("id", "title"), "sort desc");
if (!(!empty($_GPC["fromid"]) && $memberid != 0)) {
$sql = "select id,title,orientation,typepic from " . tablename(GARCIA_PREFIX . "housetype") . " where weid = :weid and cateid = 0 and housesid = :housesid order by addtime desc limit 5";
$huxing = pdo_fetchall($sql, array(":weid" => $_W["uniacid"], ":housesid" => $_GPC["id"]));
$sql = "select title,content,addtime from " . tablename(GARCIA_PREFIX . "notice") . " where weid = :weid and cateid = 0 and housesid = :housesid order by addtime desc limit 1";
$notice = pdo_fetch($sql, array(":weid" => $_W["uniacid"], ":housesid" => $_GPC["id"]));
$noticecount = pdo_fetchcolumn("select count(*) from " . tablename(GARCIA_PREFIX . "notice") . " where weid = :weid and cateid = 0 and housesid = :housesid", array(":weid" => $_W["uniacid"], ":housesid" => $_GPC["id"]));
$sql = "select * from " . tablename(GARCIA_PREFIX . "vodio") . " where weid = :weid and cate_id = 0 and houseid = :houseid order by addtime desc limit 5";
$vodio = pdo_fetchall($sql, array(":weid" => $_W["uniacid"], ":houseid" => $_GPC["id"]));
if (!(!empty($item["lat"]) && !empty($item["lng"]))) {
$browselog = pdo_fetch("select id from " . tablename(GARCIA_PREFIX . "browselog") . " where weid = :weid and memberid = :memberid and house_id = :id and cateid = 1", array(":weid" => $_W["uniacid"], ":memberid" => $memberid, ":id" => $_GPC["id"]));
if (empty($browselog)) {
$data = array("weid" => $_W["uniacid"], "cateid" => 1, "house_id" => $_GPC["id"], "openid" => $member["openid"], "memberid" => $memberid, "addtime" => TIMESTAMP);
pdo_insert(GARCIA_PREFIX . "browselog", $data);
$collec = pdo_fetch("select id from " . tablename(GARCIA_PREFIX . "collection") . " where weid = :weid and cateid = 1 and house_id = :id and uid = :uid ", array(":weid" => $_W["uniacid"], ":id" => $_GPC["id"], ":uid" => $memberid));
if (!empty($collec)) {
$house_collec = 1;
if (!($_GPC["opp"] == "collect")) {
$_notice = pdo_fetch("select * from " . tablename(GARCIA_PREFIX . "noticelist") . " where weid = :weid and houseid = :houseid and openid = :openid and type = 0", array(":weid" => $_W["uniacid"], ":houseid" => $_GPC["id"], ":openid" => $openid));
$_notice1 = pdo_fetch("select * from " . tablename(GARCIA_PREFIX . "noticelist") . " where weid = :weid and houseid = :houseid and openid = :openid and type = 1", array(":weid" => $_W["uniacid"], ":houseid" => $_GPC["id"], ":openid" => $openid));
if (!($_GPC["dopost"] == "savenotice")) {
include $this->template("houses_detail");
