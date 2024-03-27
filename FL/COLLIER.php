<?php

use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

//Format Name

function split_name($string)
{
    $string = preg_replace('/\s+/', ' ', strtoupper($string));
    $name = $sname = "";
    if (strpos($string, " AND ") !== false)
    {
        if (count(explode(" ", explode(" AND ", $string) [0])) == 3) $name = explode(" ", explode(" AND ", $string) [0]) [2] . " " . explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 3) $sname = explode(" ", explode(" AND ", $string) [1]) [2] . " " . explode(" ", explode(" AND ", $string) [1]) [0];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 2) $name = explode(" ", explode(" AND ", $string) [0]) [1] . " " . explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 2) $sname = explode(" ", explode(" AND ", $string) [1]) [1] . " " . explode(" ", explode(" AND ", $string) [1]) [0];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 1) $name = explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 1) $name = explode(" ", explode(" AND ", $string) [1]) [0];
    }
    else
    {
        if (count(explode(" ", $string)) == 3) $name = explode(" ", $string) [2] . " " . explode(" ", $string) [0];
        if (count(explode(" ", $string)) == 2) $name = explode(" ", $string) [1] . " " . explode(" ", $string) [0];
        if (count(explode(" ", $string)) == 1) $name = explode(" ", $string) [0];
    }
    return $name . "@" . $sname;
}
function split_name_interchange($string)
{
    $string = preg_replace('/\s+/', ' ', strtoupper($string));
    $name = $sname = "";
    if (strpos($string, " AND ") !== false)
    {
        if (count(explode(" ", explode(" AND ", $string) [0])) == 3) $name = explode(" ", explode(" AND ", $string) [0]) [0] . " " . explode(" ", explode(" AND ", $string) [0]) [1];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 3) $sname = explode(" ", explode(" AND ", $string) [1]) [0] . " " . explode(" ", explode(" AND ", $string) [1]) [1];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 2) $name = explode(" ", explode(" AND ", $string) [0]) [0] . " " . explode(" ", explode(" AND ", $string) [0]) [1];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 2) $sname = explode(" ", explode(" AND ", $string) [1]) [0] . " " . explode(" ", explode(" AND ", $string) [1]) [1];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 1) $name = explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 1) $sname = explode(" ", explode(" AND ", $string) [1]) [0];
    }
    else
    {
        if (count(explode(" ", $string)) == 3) $name = explode(" ", $string) [0] . " " . explode(" ", $string) [1];
        if (count(explode(" ", $string)) == 2) $name = explode(" ", $string) [0] . " " . explode(" ", $string) [1];
        if (count(explode(" ", $string)) == 1) $name = explode(" ", $string) [0];
    }
    return $name . "@" . $sname;
}
function middle_name_interchange($string)
{
    $string = preg_replace('/\s+/', ' ', strtoupper($string));
    $name = $sname = "";
    if (strpos($string, " AND ") !== false)
    {
        if (count(explode(" ", explode(" AND ", $string) [0])) == 3) $name = explode(" ", explode(" AND ", $string) [0]) [1] . " " . explode(" ", explode(" AND ", $string) [0]) [2];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 3) $sname = explode(" ", explode(" AND ", $string) [1]) [1] . " " . explode(" ", explode(" AND ", $string) [1]) [2];
    }
    else
    {
        if (count(explode(" ", $string)) == 3) $name = explode(" ", $string) [1] . " " . explode(" ", $string) [2];
    }
    return $name . "@" . $sname;
}
// Format Amount
function format_amt($amt) 
{
	$amt = trim($amt);
	$amt = str_replace(' ', '', $amt); // Removes all spaces
	$amt = str_replace(',', '', $amt); // Removes all ','
	$amt = str_replace('$', '', $amt); // Removes all '-'
	
	return $amt; // Return Integer value
}

$app_name = space(str_replace(","," ", strtoupper($batchDetails->borrowerName)));
$loan_amt = format_amt(strval($batchDetails->loanAmount));
$loan_dt = strval($batchDetails->loanDate);
$loan_dt = str_replace("-","/",$loan_dt);
$tdate = $loan_dt;
$begin_loan_dt = date_create($loan_dt);
date_add($begin_loan_dt, date_interval_create_from_date_string("90 days"));
$end_loan_dt = date_format($begin_loan_dt, "m/d/Y");
$loan_dt = new DateTime($loan_dt);

$found = ""; $first = true;

function space($space) {
	return preg_replace('!\s+!', ' ', str_replace("</span", "", str_replace("<br>", " ", str_replace("&nbsp;", "", $space))));
}

$primary_name = trim(explode("@", split_name($app_name))[0]);
$secondary_name = trim(explode("@", split_name($app_name))[1]);
$interprimary_name = trim(explode("@", split_name_interchange($app_name)) [0]);
$intersecondary_name = trim(explode("@", split_name_interchange($app_name)) [1]);
$middleprimary_name = trim(explode("@", middle_name_interchange($app_name)) [0]);
$middlesecondary_name = trim(explode("@", middle_name_interchange($app_name)) [1]);
if(strpos($app_name, " AND ") !== false)
{
    if (count(explode(" ", explode(" AND ", $app_name) [0])) == 3 && count(explode(" ", explode(" AND ", $app_name) [1])) == 3)
    {
        $names = [$primary_name, $secondary_name, $interprimary_name, $intersecondary_name, $middleprimary_name, $middlesecondary_name]; 
    }else if (count(explode(" ", explode(" AND ", $app_name) [0])) == 3 && count(explode(" ", explode(" AND ", $app_name) [1])) == 2)
    {
        $names = [$primary_name, $secondary_name, $interprimary_name, $intersecondary_name, $middleprimary_name]; 
    }else if (count(explode(" ", explode(" AND ", $app_name) [0])) == 2 && count(explode(" ", explode(" AND ", $app_name) [1])) == 3)
    {
        $names = [$primary_name, $secondary_name, $interprimary_name, $intersecondary_name, $middlesecondary_name]; 
    }else{
        $names = [$primary_name, $secondary_name, $interprimary_name, $intersecondary_name];
    }
}else{
    if (count(explode(" ", $app_name)) == 3)
    {
        $names = [$primary_name, $interprimary_name, $middleprimary_name]; 
    }else{
        $names = [$primary_name, $interprimary_name];
    }
}
$n = ""; $chk_dup = array();
try {
	
    echo $app_name." : ".$batchDetails->county."\n";
    foreach($names as $search_name)		
    {
	    $url = "https://app.collierclerk.com/records-search-2/official-land-records-search";
        $driver->get($url);

        $driver->wait()->until(
        WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::tagName('iframe'))
        );
        $iframe = $driver->findElement(WebDriverBy::tagName('iframe'));
        $driver->switchTo()->frame($iframe);
        $driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[1]/div[1]/ul/li[2]/a'))->click();
        $driver->wait()->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('search_businessname'))
        );
        $driver->findElement(WebDriverBy::id('search_businessname'))->sendKeys($search_name);
        $driver->findElement(WebDriverBy::xpath('//*[@id="search_doctypes"]/li[24]/label/input'))->click();
        $driver->findElement(WebDriverBy::id('search_startdate'))->sendKeys($tdate);
        $driver->findElement(WebDriverBy::id('search_enddate'))->sendKeys($end_loan_dt);
        sleep(1);

        $driver->findElement(WebDriverBy::id('searchbutton'))->click();
        sleep(3);

        if (strpos($driver->getPageSource(), "No results found based on search criteria.") !== false) {
            $result->appraisalError = "No Results returned for this search";
        }else
        {
            $result->appraisalError = "";
            $st1 = $driver->getPageSource();
            $Instrument_Num = explode('<td align="center">', explode('</td>', explode('MTGE</td>', $st1)[1])[0])[1];
    
            $respage = $driver->getpageSource();
            $rowcount = substr_count($respage, "rowspan") / 3;
            $ViewAD = substr_count($respage, '"View Affected Documents" href="');
            $found = 'False';
            for ($i = 3; $i <= ($rowcount * 2) + 2; $i++) 
            {
                $str = $driver->getpageSource();
                if (($i % 2) == 0) {
                    $i++;
                }
                if ($found == 'False') {
                    $doc1 = $driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[4]'))->getText();
                    if ($doc1 == 'MTGE') {
                        $driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[9]/a'))->click();
                        sleep(4);
                        $st = $driver->getpageSource();
                        $y = format_amt(count(explode(" ",$driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/table[1]/tbody/tr[2]/td/table/tbody/tr[9]/td[2]/span'))->getText())));
                        $consideration_amt = trim(space(explode(" ",$driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/table[1]/tbody/tr[2]/td/table/tbody/tr[9]/td[2]/span'))->getText())[$y-1]));
                        $Doc_Type = trim(space(explode('<span>', explode('</span></td>', explode('Doc&nbsp;Type:</td>', $st)[1])[0])[1]));
                        $Instrument_Num = trim(space(explode('<td><span>', explode('</span></td>', explode('Instrument&nbsp;#:</td>', $st)[1])[0])[1]));
                        $rec_dt = trim(space(explode('<span>', explode('</span></td>', explode('Recorded&nbsp;Date:</td>', $st)[1])[0])[1]));
                        $bktype = trim(space(explode('<td><span>', explode('</span></td>', explode('Book&nbsp;Type:</td>', $st)[1])[0])[1]));
                        $book = trim(space(explode('<td><span>', explode('</span></td>', explode('Book:</td>', $st)[1])[0])[1]));
                        $page = trim(space(explode('<td><span>', explode('</span></td>', explode('Page:</td>', $st)[1])[0])[1]));
                        $numPages = trim(space(explode('<td><span>', explode('</span></td>', explode('Page&nbsp;Count:</td>', $st)[1])[0])[1]));
                        $grantor = trim(space(explode('<td><span>', explode('</span></td>', explode("From:</span>", explode('Party&nbsp;Names</td>', $st)[1])[1])[0])[1]));
                        $grantee = trim(space(explode('<td><span>', explode('</span></td>', explode("To:</span>", explode('Party&nbsp;Names</td>', $st)[1])[1])[0])[1]));
                        $docLegal = trim(space(explode('</span>', explode('<span>', explode('Legal&nbsp;Description</td>', $st)[1])[1])[0]));
                        if (format_amt($consideration_amt) == format_amt($loan_amt) ) {
                            $avrData = array();
                            $avrData["documentFileName"] = $Instrument_Num."_".$Doc_Type.".pdf";
                            $avrData["documentType"] = $Doc_Type;
                            $avrData["book"] = $book;
                            $avrData["bookType"] = $bktype;
                            $avrData["page"] = $page;
                            $avrData["recordingDate"] = $rec_dt;
                            $avrData["instrumentNumber"] = $Instrument_Num;
                            $avrData["numberOfPages"] = $numPages;
                            $avrData["considerationAmount"] = $consideration_amt;
                            $avrData["legalDescription"] = $docLegal;
                            $avrData["grantor"] = $grantor;
                            $avrData["grantee"] = $grantee;
                            if(in_array($avrData["instrumentNumber"],$chk_dup))
                            {
                                // skip
                            }
                            else
                            {
                                array_push($chk_dup, $avrData["instrumentNumber"]);
                                $result->avr[] = $avrData;
                                $driver->findElement(WebDriverBy::linkText('View Document'))->click();
                                sleep(8);
                                $result->rename($Instrument_Num . "_" . $Doc_Type);
                                $MTG_Instrument = $Instrument_Num;
                                $i = 3;
                                $found = 'True';
                                $driver->navigate()->back();
                                sleep(3);
                                $driver->switchTo()->window($driver->getWindowHandles()[0]);
                                sleep(1);
                                $iframe = $driver->findElement(WebDriverBy::tagName('iframe'));
                                $driver->switchTo()->frame($iframe);
                                sleep(1);
                                if($MTG_Instrument != ""){
                                    $driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[1]/div[1]/ul/li[2]/a'))->click();
                                    $driver->wait()->until(
                                        WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('search_businessname'))
                                    );
                                    $driver->findElement(WebDriverBy::id('search_businessname'))->sendKeys($search_name);
                                    $driver->findElement(WebDriverBy::id('search_startdate'))->sendKeys($tdate);
                                    sleep(1);
                                    $driver->findElement(WebDriverBy::id('searchbutton'))->click();
                                    sleep(3);
                                    if (strpos($driver->getPageSource(), "No results found based on search criteria.") !== false) {
                                        $tax = "No Results returned for this search";
                                        $result->appraisalError = $tax;
                                    }else 
                                    {
                                        $st1 = $driver->getPageSource();

                                        $driver->executeScript('window.print();');
                                        sleep(5);
                                        $result->rename($search_name. " " . 'HIT');
                                        $respage = $driver->getpageSource();
                                        $rowcount = substr_count($respage, "rowspan") / 3;
                                        $ViewAD = substr_count($respage, '"View Affected Documents" href="');
                                        $found = 'True';
                                        $j = 0;
                                        for ($i = 3; $i <= ($rowcount * 2) + 2; $i++) {
                                            $str = $driver->getpageSource();

                                            if (($i % 2) == 0) {
                                                $i++;
                                            }
                                            if($ViewAD > $j)
                                            {
                                                if((stripos(explode('<tr', $str)[$i], "View Affected Documents") !== false) && $found == 'True') 
                                                {
                                                    $j++;
                                                    $driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[9]/a[2]'))->click();
                                                    sleep(3);
                                                    $st2 = $driver->getpageSource();
                                                    if (strpos($driver->getPageSource(), "MTGE</td>") !== false) {
                                                        $Inst_chk = explode('<td align="center">', explode('</td>', explode('MTGE</td>', $st2)[1])[0])[1];
                                                        if ($Inst_chk == $MTG_Instrument) {
                                                            $driver->navigate()->back();
                                                            sleep(3);
                                                            $driver->switchTo()->window($driver->getWindowHandles()[0]);
                                                            sleep(1);
                                                            $iframe = $driver->findElement(WebDriverBy::tagName('iframe'));
                                                            $driver->switchTo()->frame($iframe);
                                                            $t=$i+1;
                                                            $name = trim(space($driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[2]'))->getText()));
                                                            $f = substr_count($name, "F:");
                                                            $t = substr_count($name, "T:");
                                                            for($go=1; $go<=$f; $go++)
                                                            {
                                                                $grantor .= trim(explode("F:", $name)[$go]).' ';
                                                            }
                                                            $grantor = trim($grantor);
                                                            for($ge=1; $ge<=$t; $ge++)
                                                            {
                                                                $grantee .= trim(explode("T:", $name)[$ge]).' ';
                                                            }
                                                            $grantee = trim($grantee);
                                                            $rec_dt = trim(space($driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[3]'))->getText()));
                                                            $docType = trim(space($driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[4]'))->getText()));
                                                            $instr = trim(space($driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[5]'))->getText()));
                                                            $bk = trim(space($driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[6]'))->getText()));
                                                            $bktype = explode(" ", $bk)[0];
                                                            $book = explode(" ", $bk)[1];
                                                            $page = trim(space($driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[7]'))->getText()));
                                                            $numPages = trim(space($driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[8]'))->getText()));
                                                            $docLegal = trim(space($driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $t. ']/td[1]'))->getText()));
                                                            $avrData = array();
                                                            $avrData["documentFileName"] = $instr."_".$docType.".pdf";
                                                            $avrData["documentType"] = $docType;
                                                            $avrData["book"] = $book;
                                                            $avrData["bookType"] = $bktype;
                                                            $avrData["page"] = $page;
                                                            $avrData["recordingDate"] = $rec_dt;
                                                            $avrData["instrumentNumber"] = $instr;
                                                            $avrData["numberOfPages"] = $numPages;
                                                            $avrData["legalDescription"] = $docLegal;
                                                            $avrData["grantor"] = $grantor;
                                                            $avrData["grantee"] = $grantee;
                                                            $result->avr[] = $avrData;
                                                            $driver->findElement(WebDriverBy::xpath('//*[@id="contents"]/div[3]/div[1]/table/tbody/tr[' . $i . ']/td[1]/a'))->click();
                                                            sleep(7);
                                                            $result->rename($instr . "_" . $docType);
                                                            
                                                        }
                                                        else{
                                                            $driver->navigate()->back();
                                                            sleep(3);
                                                            $driver->switchTo()->window($driver->getWindowHandles()[0]);
                                                            sleep(1);
                                                            $iframe = $driver->findElement(WebDriverBy::tagName('iframe'));
                                                            $driver->switchTo()->frame($iframe);
                                                        }
                                                    }else
                                                    {
                                                        $driver->navigate()->back();
                                                        sleep(3);
                                                        $driver->switchTo()->window($driver->getWindowHandles()[0]);
                                                        sleep(1);
                                                        $iframe = $driver->findElement(WebDriverBy::tagName('iframe'));
                                                        $driver->switchTo()->frame($iframe);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                           
                        }
                        break;
                    }
                }
            }
        }

    }
    if ((count(glob($path . '/*.pdf')) - count(glob($path . '/*HIT.pdf')))  >= 1)
    {
        $result->appraisalError = "";
    }
    else
    {
        $result->appraisalError = "No Results returned for this search";
    }
	$driver->quit();	
}

catch (Exception $e)
{
	// Log error
	error_log($e->getMessage()." ".$e->getTraceAsString());
	
	//WriteResult($parcelData,"","","","",$e->getMessage());
	$result->otherError = $e->getMessage();
	
	if ($driver != null)
		$driver->quit();
}
?>
