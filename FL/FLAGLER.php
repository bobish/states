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
        if (count(explode(" ", explode(" AND ", $string) [0])) == 4) $name = explode(" ", explode(" AND ", $string) [0]) [2] . " " . explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 4) $sname = explode(" ", explode(" AND ", $string) [1]) [2] . " " . explode(" ", explode(" AND ", $string) [1]) [0];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 3) $name = explode(" ", explode(" AND ", $string) [0]) [2] . " " . explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 3) $sname = explode(" ", explode(" AND ", $string) [1]) [2] . " " . explode(" ", explode(" AND ", $string) [1]) [0];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 2) $name = explode(" ", explode(" AND ", $string) [0]) [1] . " " . explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 2) $sname = explode(" ", explode(" AND ", $string) [1]) [1] . " " . explode(" ", explode(" AND ", $string) [1]) [0];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 1) $name = explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 1) $name = explode(" ", explode(" AND ", $string) [1]) [0];
    }
    else
    {
        if (count(explode(" ", $string)) == 4) $name = explode(" ", $string) [2] . " " . explode(" ", $string) [0];
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
        if (count(explode(" ", explode(" AND ", $string) [0])) == 4) $name = explode(" ", explode(" AND ", $string) [0]) [0] . " " . explode(" ", explode(" AND ", $string) [0]) [1];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 4) $sname = explode(" ", explode(" AND ", $string) [1]) [0] . " " . explode(" ", explode(" AND ", $string) [1]) [1];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 3) $name = explode(" ", explode(" AND ", $string) [0]) [0] . " " . explode(" ", explode(" AND ", $string) [0]) [1];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 3) $sname = explode(" ", explode(" AND ", $string) [1]) [0] . " " . explode(" ", explode(" AND ", $string) [1]) [1];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 2) $name = explode(" ", explode(" AND ", $string) [0]) [0] . " " . explode(" ", explode(" AND ", $string) [0]) [1];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 2) $sname = explode(" ", explode(" AND ", $string) [1]) [0] . " " . explode(" ", explode(" AND ", $string) [1]) [1];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 1) $name = explode(" ", explode(" AND ", $string) [0]) [0];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 1) $sname = explode(" ", explode(" AND ", $string) [1]) [0];
    }
    else
    {
        if (count(explode(" ", $string)) == 4) $name = explode(" ", $string) [0] . " " . explode(" ", $string) [1];
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
        if (count(explode(" ", explode(" AND ", $string) [0])) == 4) $name = explode(" ", explode(" AND ", $string) [0]) [1] . " " . explode(" ", explode(" AND ", $string) [0]) [2];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 4) $sname = explode(" ", explode(" AND ", $string) [1]) [1] . " " . explode(" ", explode(" AND ", $string) [1]) [2];
        if (count(explode(" ", explode(" AND ", $string) [0])) == 3) $name = explode(" ", explode(" AND ", $string) [0]) [1] . " " . explode(" ", explode(" AND ", $string) [0]) [2];
        if (count(explode(" ", explode(" AND ", $string) [1])) == 3) $sname = explode(" ", explode(" AND ", $string) [1]) [1] . " " . explode(" ", explode(" AND ", $string) [1]) [2];
    }
    else
    {
        if (count(explode(" ", $string)) == 4) $name = explode(" ", $string) [1] . " " . explode(" ", $string) [2];
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
function space($space) {
	return preg_replace('!\s+!', ' ', str_replace("<br>", " ", str_replace("&nbsp;", "", $space)));
}

function remove($remove) {
    return preg_replace('/\b JR\b/', '',  preg_replace('/\b SR\b/', '', str_replace('/\b I\b/', '', str_replace('/\b II\b/', '', str_replace('/\b III\b/', '', str_replace('/\b IV\b/', '', str_replace('/\b V\b/', '', str_replace('/\b VI\b/', '', $remove))))))));
}

$app_name = space(remove(str_replace(","," ", strtoupper($batchDetails->borrowerName))));
$loan_amt = format_amt(strval($batchDetails->loanAmount));
$loan_dt = strval($batchDetails->loanDate);
$loan_dt = str_replace("-","/",$loan_dt);

$dd = strlen(explode("/",$loan_dt)[1]);
$mm = strlen(explode("/",$loan_dt)[0]);
$yyyy = explode("/",$loan_dt)[2];
if ($dd == 1)
{  $dd = "0".explode("/",$loan_dt)[1]; }
else{
    $dd = explode("/",$loan_dt)[1];
}
if ($mm == 1)
{  $mm = "0".explode("/",$loan_dt)[0]; }
else {
    $mm = explode("/",$loan_dt)[0];
}
$temp_Date = $mm."/".$dd."/".$yyyy;

$loan_dt = new DateTime($loan_dt);
$found = ""; $first = true;
$pq = 0;

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
	    $url = "https://apps.flaglerclerk.com/Landmark/";
        $driver->get($url);
        $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath('//*[@data-title="Name Search"]')));
        $driver->findElement(WebDriverBy::xpath('//*[@data-title="Name Search"]'))->click();
        if($first == true)
        {
            $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('idAcceptYes')));
            $driver->findElement(WebDriverBy::id('idAcceptYes'))->click();
            $first = false;
        }
        $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('name-Name')));
		$driver->findElement(WebDriverBy::id('name-Name'))->sendKeys($search_name);	
		$driver->findElement(WebDriverBy::id('beginDate-Name'))->clear();
		$driver->findElement(WebDriverBy::id('beginDate-Name'))->sendKeys($temp_Date);
		$driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('documentCategory-Name')));
		sleep(2);
		$driver->findElement(WebDriverBy::xpath("//option[contains(text(), 'Mortgage')]"))->click();
        sleep(1);
        $driver->findElement(WebDriverBy::xpath("//option[contains(text(), 'Show first 2000 records')]"))->click();
		sleep(1);
        $driver->findElement(WebDriverBy::id("submit-Name"))->click();
        $driver->wait()->until( function($driver)
		{
			$text = $driver->findElement(WebDriverBy::id("resultsTable_info"))->getText();
			return (strpos($text,"Returned") !== false);
		}
		);
		sleep(2);
		if(count($driver->findElement(WebDriverBy::id('resultsTable'))->findElement(WebDriverBy::tagName('tbody'))->findElement(WebDriverBy::tagName('tr'))->findElements(WebDriverBy::tagName('td'))) < 2)
		{
			$result->appraisalError = "No results found!!!";
		}else{
            $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('resultsTable')));
			$driver->findElement(WebDriverBy::xpath("//*[@id='displaySelect']/select/option[5]"))->click();
			
			$driver->executeScript("var css = '@page { size: a3 landscape;}',
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style');
		
			style.type = 'text/css';
			style.media = 'print';
		
			if (style.styleSheet){
			style.styleSheet.cssText = css;
			} else {
			style.appendChild(document.createTextNode(css));
			}
		
			head.appendChild(style);window.print();"); 
			// click that row then break loop.
			while(count(glob($path . "/Landmark*.pdf"))  != 1)
            {
                sleep(1);
            }
            sleep(2);
			$hit_page = $search_name."_HIT";
			$result->rename($hit_page);
            sleep(1);
            // looping through the result table row wise 
            foreach($driver->findElement(WebDriverBy::id('resultsTable'))->findElement(WebDriverBy::tagName('tbody'))->findElements(WebDriverBy::tagName('tr')) as $row)
            {
				// extract recording date from each row
				$temp_record_dt = $row->findElements(WebDriverBy::tagName('td'))[7]->getText();
				$temp_record_dt = new DateTime($temp_record_dt); 
				$temp_doctype = $row->findElements(WebDriverBy::tagName('td'))[8]->getText();
				// checking the recording date is after the loan date ( in the input file ) and also if the doc type is MORTGAGE, if so click that row.
				sleep(2);
				if(strpos($temp_doctype, "MORTGAGE-")!== false)
				{
					$temp_doctype = "MORTGAGE";
				}
				if ($temp_record_dt >= $loan_dt && (strtoupper($temp_doctype) == "MORTAGES" ||strtoupper($temp_doctype) == "MORTGAGES" || strtoupper($temp_doctype) == "MORTGAGE" ))
				{
                    $n = false;
                    while($n != true)
                    {
                        try{
                            $row->click();	
                            sleep(1);
                            $n = true;
                        }catch(Exception $e2)
                        {
                            $driver->executeScript('window.scrollBy(0, 350);');
                            usleep(250000);
                            echo "Page Down\n";
                        }
                    }
                    if($n == true)
                    {
                        $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('idViewGroup'))
                        );
                        $driver->wait()->until(WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loadingModal'))
                        );
                        break;
                    }
				}
            }
            // set a flag to check if we find the mortgage having the loan amount given in the input file.
			$found = false;
			if(strpos($driver->getPageSource(), "Document Information") !== false)
			{
                $driver->findElement(WebDriverBy::id("movementType"))->sendKeys("Result Row #");
                sleep(1);
                $driver->findElement(WebDriverBy::id("movementType"))->sendKeys("Result Row #");
                sleep(1);
                while ($found == false) {	
                    // extract consideration amount each time for comparing
                    $consideration1 = trim(format_amt(space(explode('<td valign="top">',explode('</td>',explode('Consideration</label>',$driver->getpageSource())[1])[1])[1])));
                    if(strpos($consideration1, ".00") !==false)
                    {
                        $consideration1 = str_replace(".00", "",$consideration1);
                    }
                    $loan_amt = trim(format_amt(str_replace(".00", "",$loan_amt)));
                    //echo $consideration."::".$loan_amt;
                    // Checking loan amount in the input file is equal to the consideration amount displayed on recorder website. if so enter to the block.
                    if($consideration1 == $loan_amt)
                    {
                        $original_instr = trim($driver->findElement(WebDriverBy::xpath("//*[@id='documentInformationParent']/table/tbody/tr[1]/td[2]"))->getText());
                        // EXTRACTION 
                        $docType =$docLegal =$grantor= $grantee=$book = $pg = $st = $recdt = $bktype = $bkpg = $insnum = $num = "";
					    $insnum =trim(space(explode('<td valign="top">',explode('</td>',explode('Instrument #</label>',$driver->getpageSource())[1])[1])[1]));
				
						$bkpg =trim(space(explode('<td valign="top">',explode('</td>',explode('Book/Page</label>',$driver->getpageSource())[1])[1])[1]));
                        $book1 =trim(space(explode('<td valign="top">',explode('</td>',explode('Book/Page</label>',$driver->getpageSource())[1])[1])[1]));
                        $book = trim(explode(" ",explode("/",$book1)[0])[1]);
                        $pg = trim(explode("/",$book1)[1]);
                        $bktype =trim(space(explode('<td valign="top">',explode('</td>',explode('Book Type</label>',$driver->getpageSource())[1])[1])[1]));
                        $docType =trim(space(explode('<td valign="top">',explode('</td>',explode('Doc Type</label>',$driver->getpageSource())[1])[1])[1]));
                        $recdt =trim(space(explode(" ",explode('<td valign="top">',explode('</td>',explode('Record Date</label>',$driver->getpageSource())[1])[1])[1])[0]));
                        $consideration =trim(format_amt(space(explode('<td valign="top">',explode('</td>',explode('Consideration</label>',$driver->getpageSource())[1])[1])[1])));
                        $numpages =trim(space(explode('<td valign="top">',explode('</td>',explode('Number of Pages</label>',$driver->getpageSource())[1])[1])[1]));
				
                        $st=$driver->getpageSource();
                        if(strpos($st,"Legal Description") !== false)
						{
							$docLegal =trim(space(explode('<td valign="top">',explode('</td>',explode('Legal Description</label>',$driver->getpageSource())[1])[1])[1]));

						}
						if(strpos($st,"1st Party") !== false)
						{
                            $grantor  =trim(space(explode('<td valign="top">',explode('</td>',explode('1st Party</label>',$driver->getpageSource())[1])[1])[1]));
						}
							
						if(strpos($st,"2nd Party") !== false)
						{
							$grantee =  trim(space(explode('<td valign="top">',explode('</td>',explode('2nd Party</label>',$driver->getpageSource())[1])[1])[1]));
						}
						$avrData = array();
						$avrData["documentFileName"] = $docType."_".$insnum.".pdf";
						$avrData["documentType"] = $docType;
						$avrData["book"] = $book;
						$avrData["page"] = $pg;
						$avrData["bookPage"] = $bkpg;
						$avrData["bookType"] = $bktype;
						$avrData["recordingDate"] = $recdt;
						$avrData["instrumentNumber"] = $insnum;
						$avrData["numberOfPages"] = $numpages;
						$avrData["considerationAmount"] = $consideration;
						$avrData["legalDescription"] = $docLegal;
						$avrData["grantor"] = $grantor;
						$avrData["grantee"] = $grantee;

                        if(in_array($avrData["instrumentNumber"],$chk_dup))
                        {
                            // echo "Enter";
                            break;
                        }
                        else
                        {
                            // echo "Test";
                            array_push($chk_dup, $avrData["instrumentNumber"]);
                            $result->avr[] = $avrData;
                            $driver->wait()->until(WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('imageLoadingImage'))
                            );
                            $driver->findElement(WebDriverBy::id("idViewGroup"))->click();
                            sleep(1);
                            // wait until Preview all pages is displayed.
                            $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('DocumentViewButtonAll')));
                            $driver->findElement(WebDriverBy::id("DocumentViewButtonAll"))->click();
                            sleep(2);
                            // variable for first filename
                            $file = "";
                            // left table object;

                            $leftTable = $driver->findElement(WebDriverBy::xpath('//*[@id="documentInformationParent"]/table'));
                            // iterate through left table rows to find the doctype. it is MORTGAGE only still for safer side extracting and saving.
                            foreach($leftTable->findElements(WebDriverBy::tagName('tr')) as $lrow)
                            {
                                if(count($lrow->findElements(WebDriverBy::tagName('td'))) > 1)
                                {
                                    $label = $lrow->findElements(WebDriverBy::tagName('td'))[0]->getText();
                                    if(strtoupper(trim($label)) == "DOC TYPE")
                                        $file = trim($lrow->findElements(WebDriverBy::tagName('td'))[1]->getText());
                                        if(strpos($file, "MORTGAGE-")!== false)
                                        {
                                            $file = "MORTGAGE";
                                        }
                                }
                            }	
                            while(count(glob($path . "/download*.pdf"))  != 1)
                            {
                                sleep(1);
                            }
                            sleep(1); 
                            $result->rename($original_instr."_".$file);
                            // set flag to "true" since we have got the correct document loan amount matching the consideration amount on website
                            $found = true;
                            sleep(3);		
                        }

                    }else{
                        // click next document
                        if(strpos($driver->getPageSource(),'style="font-size: 2em; color: rgb(34, 34, 34); display: none;"')===false)
                        {
                            $pq++;
                            $driver->findElement(WebDriverBy::id("directNavNext"))->click();
                            sleep(3);	
                            if($pq == 20)
                            {
                                break;
                            }				
                        }
                        else
                        {
                            // break loop if next button is not available
                            break;
                        }	
                    }
                }
            }
            if($found == true)
            {
                $t = 0;
                $docCount = count($driver->findElements(WebDriverBy::className("docLink")));
                for($t=0; $t< $docCount; $t++)
                {
                
                    $driver->executeScript('window.scrollBy(0, 350);');
                    $driver->findElements(WebDriverBy::className("docLink"))[$t]->click();
                    sleep(3);
                    $driver->wait()->until(WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('imageLoadingImage')));
                    // EXTRACTION 
                    $docType =$docLegal =$grantor= $grantee=$book = $pg = $st = $recdt = $bktype = $bkpg = $insnum = $num = "";
                    $insnum =trim(space(explode('<td valign="top">',explode('</td>',explode('Instrument #</label>',$driver->getpageSource())[1])[1])[1]));
				
                    $bkpg =trim(space(explode('<td valign="top">',explode('</td>',explode('Book/Page</label>',$driver->getpageSource())[1])[1])[1]));
                    $book1 =trim(space(explode('<td valign="top">',explode('</td>',explode('Book/Page</label>',$driver->getpageSource())[1])[1])[1]));
                    $book = trim(explode(" ",explode("/",$book1)[0])[1]);
                    $pg = trim(explode("/",$book1)[1]);
                    $bktype =trim(space(explode('<td valign="top">',explode('</td>',explode('Book Type</label>',$driver->getpageSource())[1])[1])[1]));
                    $docType =trim(space(explode('<td valign="top">',explode('</td>',explode('Doc Type</label>',$driver->getpageSource())[1])[1])[1]));
                    $recdt =trim(space(explode(" ",explode('<td valign="top">',explode('</td>',explode('Record Date</label>',$driver->getpageSource())[1])[1])[1])[0]));
                    $numpages =trim(space(explode('<td valign="top">',explode('</td>',explode('Number of Pages</label>',$driver->getpageSource())[1])[1])[1]));
            
                    $st=$driver->getpageSource();
                    if(strpos($st,"Legal Description") !== false)
                    {
                        $docLegal =trim(space(explode('<td valign="top">',explode('</td>',explode('Legal Description</label>',$driver->getpageSource())[1])[1])[1]));

                    }
                    if(strpos($st,"1st Party") !== false)
                    {
                        $grantor  =trim(space(explode('<td valign="top">',explode('</td>',explode('1st Party</label>',$driver->getpageSource())[1])[1])[1]));
                    }
                        
                    if(strpos($st,"2nd Party") !== false)
                    {
                        $grantee =  trim(space(explode('<td valign="top">',explode('</td>',explode('2nd Party</label>',$driver->getpageSource())[1])[1])[1]));
                    }
                    $avrData = array();
                    $avrData["documentFileName"] = $docType."_".$insnum.".pdf";
                    $avrData["documentType"] = $docType;
                    $avrData["book"] = $book;
                    $avrData["page"] = $pg;
                    $avrData["bookPage"] = $bkpg;
                    $avrData["bookType"] = $bktype;
                    $avrData["recordingDate"] = $recdt;
                    $avrData["instrumentNumber"] = $insnum;
                    $avrData["numberOfPages"] = $numpages;
                    $avrData["legalDescription"] = $docLegal;
                    $avrData["grantor"] = $grantor;
                    $avrData["grantee"] = $grantee;
                    $result->avr[] = $avrData;	
                    
                    $driver->executeScript('window.scrollBy(0, -450);');
                    $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('idViewGroup')));
                    $driver->findElement(WebDriverBy::id('idViewGroup'))->click();
                    sleep(1);
                    $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('DocumentViewButtonAll')));
                    $driver->findElement(WebDriverBy::id("DocumentViewButtonAll"))->click();
                    sleep(2);
                    // variables for first filename, instrument number
                    $file = $instr = "";
                    // set left table object to a variable
                    $leftTable = $driver->findElement(WebDriverBy::xpath('//*[@id="documentInformationParent"]/table'));
                    // iterate through left table rows to find the doctype. 
                    foreach($leftTable->findElements(WebDriverBy::tagName('tr')) as $lrow)
                    {
                        if(count($lrow->findElements(WebDriverBy::tagName('td'))) > 1)
                        {
                            // extract the label 
                            $label = $lrow->findElements(WebDriverBy::tagName('td'))[0]->getText();
                            // check if label is DOC TYPE or INSTRUMENT # and set values accordingly.
                            if(strtoupper(trim($label)) == "DOC TYPE")
                            $file = trim($lrow->findElements(WebDriverBy::tagName('td'))[1]->getText());
                            if(strtoupper(trim($label)) == "INSTRUMENT #")
                            $instr = trim($lrow->findElements(WebDriverBy::tagName('td'))[1]->getText());
                        }		
                    }
                    // rename file with the extracted doc type and the instrument number
                    while(count(glob($path . "/download*.pdf"))  != 1)
                    {
                        sleep(1);
                    }
                    sleep(1); 
                    $file1=$instr."_".$file;
                    $result->rename($file1);
                    //  The following dropdown we need to select twice becasue on website it is resetting back to the previous selection, other websites may behave differently change accordingly. 		
                    if($t<$docCount)
                    {			
                        $driver->findElement(WebDriverBy::id("movementType"))->sendKeys("Instrument #");
                        sleep(1);
                        $driver->findElement(WebDriverBy::id("movementType"))->sendKeys("Instrument #");
                        sleep(1);
                        // click inside the textbox
                        $driver->findElement(WebDriverBy::id("directNavigation"))->click();
                        sleep(3);
                        // and delete the unwanted characters, press HOME then press delete multiple times to remove the characters
                        $driver->getKeyboard()->pressKey(WebDriverKeys::HOME);
                        for($j=1;$j<20;$j++)
                        {
                            $driver->getKeyboard()->pressKey(WebDriverKeys::DELETE);
                        }
                        $driver->findElement(WebDriverBy::id("directNavigation"))->sendKeys($original_instr);
                        $driver->findElement(WebDriverBy::id("directNavigationButton"))->click();		
                        sleep(2);				
                    }
                    sleep(2);
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