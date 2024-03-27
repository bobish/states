<?php

use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

$app_name = $batchDetails->borrowerName;
$loan_amt = $batchDetails->loanAmount;
$loan_dt = $batchDetails->loanDate;

$loan_dt = str_replace("-","/",$loan_dt);
$loan_dt = new DateTime($loan_dt);
$found = "";

function split_name($string)
{
	$string = str_replace(" & "," AND ",trim($string));
	
	$string = explode(" AND ",$string)[0];
	$string = explode(" OR ",$string)[0];
    $arr = explode(' ', $string);
    $num = count($arr);
    
    if ($num == 3) 
	{
        $name = explode(" ",$string, 3)[2]." ".explode(" ",$string,3)[0]." ".explode(" ",$string,3)[1];
    } 
	else 
	{   $name = explode(" ",$string, 2)[1]." ".explode(" ",$string,2)[0];    }

    return $name;
}

function format_amt($amt) 
{
	$amt = trim($amt);
	$amt = str_replace(' ', '', $amt); 
	$amt = str_replace(',', '', $amt);
	$amt = str_replace('$', '', $amt);
	
	return $amt;
}

$applicant_name = split_name($app_name);

try {
	
	echo $app_name." : ".$batchDetails->county."\n";

	$url = "http://208.75.175.18/landmarkweb";
	$driver->get($url);
		
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath('//*[@data-title="Name Search"]'))
	);

	$driver->findElement(WebDriverBy::xpath('//*[@data-title="Name Search"]'))->click();
	
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('idAcceptYes'))
	);
	
	$driver->findElement(WebDriverBy::id('idAcceptYes'))->click();
	
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('name-Name'))
	);
	$driver->findElement(WebDriverBy::id('name-Name'))->sendKeys($applicant_name);
	
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('documentCategory-Name'))
	);
	$driver->findElement(WebDriverBy::xpath("//*[@id='documentCategory-Name']/option[19]"))->click();
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
	
	if(strpos($driver->getPageSource(), "No results.") !== false)
	{
		$tax= "No Results returned for this search";
		$result->appraisalError = $tax;
	}
	else
	{	
		$original_instr = "";

		$driver->wait()->until(
		WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('resultsTable'))
		);
		
		$driver->findElement(WebDriverBy::xpath("//*[@id='displaySelect']/select/option[5]"))->click();
		sleep(3);
		$count = count($driver->findElements(WebDriverBy::xpath("//*[@id='resultsTable']/tbody/tr")));
		for($i=1; $i <= $count; $i++){
			$temp_record_dt = $driver->findElement(WebDriverBy::xpath('//*[@id="resultsTable"]/tbody/tr['.$i.']/td[8]'))->getText();
			$temp_record_dt = new DateTime($temp_record_dt); 
			$temp_doctype = $driver->findElement(WebDriverBy::xpath('//*[@id="resultsTable"]/tbody/tr['.$i.']/td[9]'))->getText(); 
			if($temp_record_dt >= $loan_dt && strtoupper($temp_doctype) == "MORTGAGE")
			{
				$driver->executeScript("var css = '@page { size: a4 portrait;}',
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
				$driver->findElement(WebDriverBy::xpath('//*[@id="resultsTable"]/tbody/tr['.$i.']/td[8]'))->click();
				$found = false;
				sleep(10);
				$consideration = format_amt($driver->findElement(WebDriverBy::xpath('//*[@id="documentInformationParent"]/table/tbody/tr[14]/td[2]'))->getText());
		
				if($consideration == $loan_amt)
				{
					$found = true;
					break;
				}else
				{
					$driver->findElement(WebDriverBy::id("returnToSearchButton"))->click();
					unlink("$path\\Landmark Web Official Records Search.pdf");
				}
			}			
		}				
		if($found == true)
		{	
			sleep(10);
			$original_instr = trim($driver->findElement(WebDriverBy::xpath('//*[@id="documentInformationParent"]/table/tbody/tr[1]/td[2]'))->getText());
			$inst_details = $original_instr."_HIT";
			sleep(3);
			$result->rename($inst_details);
			$driver->wait()->until(
			WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('imageLoadingImage'))
			);
			
			$driver->findElement(WebDriverBy::id("idViewGroup"))->click();
			
			$driver->wait()->until(
			WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('DocumentViewButtonAll'))
			);
			
			$driver->findElement(WebDriverBy::id("DocumentViewButtonAll"))->click();
			
			sleep(2);
			
			$file = "";
			$leftTable = $driver->findElement(WebDriverBy::xpath('//*[@id="documentInformationParent"]/table'));
			
			foreach($leftTable->findElements(WebDriverBy::tagName('tr')) as $lrow)
			{
				if(count($lrow->findElements(WebDriverBy::tagName('td'))) > 1)
				{
					$label = $lrow->findElements(WebDriverBy::tagName('td'))[0]->getText();
					if(strtoupper(trim($label)) == "DOC TYPE")
						$file = trim($lrow->findElements(WebDriverBy::tagName('td'))[1]->getText());
							
				}
				
			}					
			$result->rename($file."_".$original_instr);
			sleep(2);
			$docCount = count($driver->findElements(WebDriverBy::className("docLink")));
			for($t=0;$t<=$docCount-1;$t++)
			{
				sleep(5);
				$driver->findElements(WebDriverBy::className("docLink"))[$t]->click();
				sleep(3);
				$driver->wait()->until(
				WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('imageLoadingImage'))
				);		
				$driver->executeScript('window.scrollBy(0, -350);');			
				$driver->wait()->until(
				WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('idViewGroup'))
				);
				$driver->findElement(WebDriverBy::id("idViewGroup"))->click();
				$driver->wait()->until(
				WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('DocumentViewButtonAll'))
				);
			
				$driver->findElement(WebDriverBy::id("DocumentViewButtonAll"))->click();
				sleep(3);
				$file = $instr = "";
				$leftTable = $driver->findElement(WebDriverBy::xpath('//*[@id="documentInformationParent"]/table'));
				
				foreach($leftTable->findElements(WebDriverBy::tagName('tr')) as $lrow)
				{
					if(count($lrow->findElements(WebDriverBy::tagName('td'))) > 1)
					{
						$label = $lrow->findElements(WebDriverBy::tagName('td'))[0]->getText();
						if(strtoupper(trim($label)) == "DOC TYPE")
							$file = trim($lrow->findElements(WebDriverBy::tagName('td'))[1]->getText());
						if(strtoupper(trim($label)) == "INSTRUMENT #")
							$instr = trim($lrow->findElements(WebDriverBy::tagName('td'))[1]->getText());
								
					}
					
				}
				sleep(5);
				$result->rename($file."_".$instr);
			
				if($t<$docCount-1)
				{					
					$driver->findElement(WebDriverBy::id("movementType"))->sendKeys("Clerk File #");
					sleep(1);
					$driver->findElement(WebDriverBy::id("movementType"))->sendKeys("Clerk File #");
					sleep(1);
					$driver->findElement(WebDriverBy::id("directNavigation"))->click();
					$driver->getKeyboard()->pressKey(WebDriverKeys::HOME);
					sleep(3);
					for($j=1;$j<20;$j++)
					{
						$driver->getKeyboard()->pressKey(WebDriverKeys::DELETE);
					}
				

					$driver->findElement(WebDriverBy::id("directNavigation"))->sendKeys($original_instr);
					$driver->findElement(WebDriverBy::id("directNavigationButton"))->click();						
				}
				sleep(10);
				$driver->executeScript('window.scrollBy(0, 350);');
				
				
			}
			
		}else if($found == false){
			$tax= "No Results returned for this search";
			$result->appraisalError = $tax;
		}
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