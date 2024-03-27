<?php

use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

//Format Name

// echo $rootPath."\n";

function split_name($string)
{
	$string = strtoupper($string);
	$string = str_replace(",","",$string);
	$string = preg_replace('/\s+/', ' ',strtoupper($string));
	$string = str_replace(' JR', '',strtoupper($string));
	$string = str_replace(' SR', '',strtoupper($string));
	// $string = str_replace(' LLC', '',strtoupper($string));
	$secPart = $firPart = $name = $sname = "";
	
	if(strpos($string,' AND ') !== false)
	{
		$string = trim($string);
		$firPart = explode(" AND ",$string)[0];
		if(count(explode(" AND ",$string)) >= 1)
		{
			$secPart = explode(" AND ",$string)[1]; 
			$arr2 = explode(' ', $secPart);
			$num2 = count($arr2);
			if ($num2 == 3) 
			{
				$sname = explode(" ",$secPart, 3)[2].", ".explode(" ",$secPart,3)[0]." ".explode(" ",$secPart,3)[1];
			} 
			else 
			{   
				$sname = explode(" ",$secPart, 2)[1].", ".explode(" ",$secPart,2)[0];  
			}
		}	
		
		$arr = explode(' ', $firPart);
		$num = count($arr);
			
		if ($num == 3) 
		{
			$name = explode(" ",$firPart, 3)[2].", ".explode(" ",$firPart,3)[0]." ".explode(" ",$firPart,3)[1];
		} 
		else 
		{   
			$name = explode(" ",$firPart, 2)[1].", ".explode(" ",$firPart,2)[0];  
		}
	}
	else
	{
		$arr = explode(' ', $string);
		$num = count($arr);
			
		if ($num == 3) 
		{
			$name = explode(" ",$string, 3)[2].", ".explode(" ",$string,3)[0]." ".explode(" ",$string,3)[1];
		} 
		else 
		{   
			$name = explode(" ",$string, 2)[1].", ".explode(" ",$string,2)[0];  
		}		
	}
	
    return $name."@".$sname;
}


function interchange($string)
{

	$string = strtoupper($string);
	$string = str_replace(","," ",$string);
	$string = preg_replace('/\s+/', ' ',strtoupper($string));
	$string = str_replace(' JR', '',strtoupper($string));
	// $string = str_replace(' LLC', '',strtoupper($string));
	$string = str_replace(' SR', '',strtoupper($string));	
	$name = "";
	
	$arr = explode(' ', $string);
	$num = count($arr);
		
	if ($num == 3) 
	{
		$name = explode(" ",$string, 3)[1].", ".explode(" ",$string,3)[0]." ".explode(" ",$string,3)[2];
	} 
	else 
	{   
		$name = explode(" ",$string, 2)[1].", ".explode(" ",$string,2)[0];  
	}		

    return $name;
	
}

// Format Amount
function format_amt($amt) 
{
	$amt = trim($amt);
	$amt = str_replace(' ', '', $amt); // Removes all spaces
	$amt = str_replace(',', '', $amt); // Removes all ','
	$amt = str_replace('$', '', $amt); // Removes all '-'
	$amt = str_replace('.00', '', $amt);
	return $amt; // Return Integer value
}

//Input 10-19-2020

$app_name = $batchDetails->borrowerName;
$loan_amt = $batchDetails->loanAmount;
$loan_amt = floatval(str_replace(",", "", $loan_amt));
$loan_dt = $batchDetails->loanDate;

$loan_dt = str_replace("-","/",$loan_dt);
$yr = trim(explode("/",$loan_dt)[2]);
$loan_dt = new DateTime($loan_dt);

//Init
$doc_type = "";
$book_pg = "";
$instrument_num = "";
$record_dt = "";

$primary_name = explode("@",split_name($app_name))[0];
$secondary_name = explode("@",split_name($app_name))[1];
// echo $primary_name."\n";
// echo $secondary_name."\n";

try {
	
	if (count(glob($path . '/*.*')) > 0)
    {
        array_map('unlink', glob($path . '/*.*'));
    }	
	
	echo $app_name." : ".$batchDetails->county."\n";
	
	$url = "https://officialrecords.broward.org/AcclaimWeb/";
	$driver->get($url);
	
	//Home Page
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('mnname'))
	);
	
		
	$driver->findElement(WebDriverBy::xpath('//*[@id="boxHolder2"]/div[1]/h4/button/img'))->click();
	sleep(3);
	
	if(strpos($driver->getPageSource(), "I accept the conditions above") !== false)
	{	$driver->findElement(WebDriverBy::xpath('//*[@id="btnButton"]'))->click();
		sleep(3);
	}
		
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('SearchOnName'))
	);
	
	
	$driver->findElement(WebDriverBy::id('RecordDateFrom'))->click();
	$driver->getKeyboard()->pressKey(WebDriverKeys::END);
	for($i=1; $i <= 4; $i++)
		$driver->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
	
	$driver->findElement(WebDriverBy::id('RecordDateFrom'))->sendKeys($yr);
	
	function nameSearch($driver, $search_name, $path, $loan_amt, $loan_dt, $result)
	{
	
			$res_found = false;	
			$writeError = "";
			// echo $search_name."\n";
			$driver->findElement(WebDriverBy::id('SearchOnName'))->clear();
			$driver->findElement(WebDriverBy::id('SearchOnName'))->sendKeys($search_name);
			$driver->findElement(WebDriverBy::id('btnSearch'))->click();
			
			sleep(5);
		
		if(strpos($driver->getPageSource(), "No Results to Display") !== false)
		{
			$avrPath = explode("AVRAutomation\\",$path)[0]."AVRAutomation\\";
			$imgn = $path."//".$search_name."_No_Results.png";
			$pdfName = $path."//".$search_name."_No_Results.pdf";
			$driver->findElement(WebDriverBy::id("mainBack"))->takeElementScreenshot($imgn);
			$converterPath = $avrPath."//TiiffConv//PDFConversion//ImageMagick-7.0.10-Q16-HDRI//";
					
			exec("\"".$converterPath."convert\" \"$imgn\" \"".$pdfName."\"");
			
			unlink($imgn);
				
			$search_name = interchange($search_name);
			$driver->findElement(WebDriverBy::id('SearchOnName'))->clear();
			sleep(1);
			$driver->findElement(WebDriverBy::id('SearchOnName'))->sendKeys($search_name);
			$driver->findElement(WebDriverBy::id('btnSearch'))->click();
			sleep(5);	
			if(strpos($driver->getPageSource(), "No Results to Display") !== false)
			{
				
				$result->taxError = "No Results to Display for the supplied input";
				$imgn = $path."//".$search_name."_No_Results.png";
				$pdfName = $path."//".$search_name."_No_Results.pdf";
				$driver->findElement(WebDriverBy::id("mainBack"))->takeElementScreenshot($imgn);
							
				exec("\"".$converterPath."convert\" \"$imgn\" \"".$pdfName."\"");			
				
				unlink($imgn);
				
				$writeError = "No Results to Display for the supplied input"; 
			}
			else
			{
				$res_found = true;
			}
		
		}
		else
		{
			$res_found = true;
		}
			if($res_found == true)
			{	

				sleep(5);
				$driver->wait()->until(
				WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('printResults'))
				);
				
				$driver->wait()->until(
				WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('SearchFooter'))
				);
				sleep(3);
				
				$total_row_count = count($driver->findElements(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr")));
				
				if ($total_row_count <= 2)
				{
					sleep(5);
				}
				//echo "Row: $total_row_count"."\n";
				
				$applicant_flag = "NO";
				$applicant_row = false;
				$temp_instrument_num = "";			
				$temp_record_dt = "";			
				$temp_loan_amt = "";	
				for($i=1; $i < $total_row_count; $i++)
				{  
					$temp_record_dt = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[5]"))->getText();
					
					// echo $temp_record_dt."\n";
					
					$temp_record_dt = str_replace("-","/",$temp_record_dt);
					$temp_record_dt = new DateTime($temp_record_dt);
						
					$temp_instrument_num = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[8]"))->getText();
					
					$temp_loan_amt= $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[11]"))->getText();			
					
					$temp_doctype = strtoupper($driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[13]"))->getText());
					// echo $temp_doctype."\n".format_amt($temp_loan_amt)." and $loan_amt\n";
					
					if ($loan_amt == floatval(format_amt($temp_loan_amt)) && $temp_record_dt >= $loan_dt && (strpos($temp_doctype, "MORTGAGE") !== false || strpos($temp_doctype, "MORTGAGE/ MODIFICATIONS & ASSUMPTIONS") !== false))
 					{
						$applicant_row = true;				
						$instrument_num = $temp_instrument_num;
						$temp_doctype =  str_replace("&amp;","&",str_replace("/","-",$temp_doctype));
						$search_name_mod = str_replace("-"," ",$search_name);
						$fileName = "$search_name_mod".'_'."HIT";
						$driver->findElement(WebDriverBy::id("printResults"))->click();
						$driver->findElement(WebDriverBy::id("divResultsPrint"))->findElements(WebDriverBy::tagName("button"))[0]->click();
						sleep(2);
						$result->rename($fileName);
						$driver->findElement(WebDriverBy::xpath('//*[@id="RsltsGrid"]/div[4]/table/tbody/tr['.$i.']'))->click();
						break;
						
						
					}
								
				}
				
				if($applicant_row == true)
				{			
					$driver->switchTo()->window($driver->getWindowHandles()[1]);
					sleep(2);
				
				// Record Date:
				$driver->wait()->until( function($driver)
				{
					return (strpos($driver->getPageSource(),"Record Date:") !== false);	
				}
				);
					
				$page = $driver->getPageSource();
				$doc_Type = trim(explode("<",explode("px;\">",explode("Doc Type:",$page)[1])[1])[0]);
				$doc_Type =  str_replace("&amp;","&",str_replace("/","-",$doc_Type));
				$doc_num = trim(explode("</div",explode("formInput\">",explode("Instrument Number:&nbsp;",$page)[1])[1])[0]);
				
				$docLegal = $book = $pg = $st = $recdt = $bktype = $bkpg = $insnum = $num = $consideration = $grantor = $grantee = "";

				$st = $driver->getPageSource();
				
				$doc_Type = trim(explode("<",explode("px;\">",explode("Doc Type:",$page)[1])[1])[0]);
				$doc_Type = str_replace("&amp;","&",str_replace("/","-",$doc_Type));
				$doc_num = trim(explode("</div",explode("formInput\">",explode("Instrument Number:&nbsp;",$page)[1])[1])[0]);
				
				$recdt = trim(explode("<",explode("\">",explode("Record Date:",$st)[1])[1])[0]);
				$bktype = trim(explode("<",explode("\">",explode("Book Type:",$st)[2])[1])[0]);
				$bktype = preg_replace('/\s+/', ' ', $bktype);
				$bkpg = trim(explode("<",explode("formInput\">",explode("Book / Page:&nbsp;",$st)[1])[1])[0]);
				$book = trim(explode("/",$bkpg)[0]);
				$pg = trim(explode("/",$bkpg)[1]);
				
				$insnum = trim(explode("<",explode("formInput\">",explode("Instrument Number:&nbsp;",$st)[1])[1])[0]);
				
				$numpages = trim(explode("<",explode("\">",explode("Number Of Pages:",$st)[1])[1])[0]);
				
				$docType = trim(explode("<",explode("\">",explode("Doc Type:",$st)[1])[1])[0]);
				if(strpos($st,"Consideration:") !== false)
				{
					$consideration = trim(explode("<",explode("\">",explode("Consideration:",$st)[1])[1])[0]);	
				}
				
				
				if(strpos($st,"DocLegals / Parcel#:") !== false)
				{
					$docLegal = trim(explode("</span>",explode("<span>",explode("DocLegals / Parcel#:",$st)[1])[1])[0]);
				}
				if(strpos($st,"Mortgagor / Borrower:") !== false)
				{
					$grantor = trim(explode("</span>",explode("<span>",explode("\">",explode("Mortgagor / Borrower:",$st)[1])[1])[1])[0]);
				}
				
				if(strpos($st,"Mortgagee / Lender:") !== false)
				{
					$grantee = trim(explode("</span>",explode("<span>",explode("\">",explode("Mortgagee / Lender:",$st)[1])[1])[1])[0]);	
				}
				
				// till here
				
				$avrData = array();
				$avrData["documentFileName"] = $doc_Type."_".$doc_num.".pdf";
				$avrData["documentType"] = $doc_Type;
				$avrData["book"] = $book;
				$avrData["page"] = $pg;
				$avrData["bookPage"] = $bkpg;
				$avrData["bookType"] = $bktype;
				$avrData["recordingDate"] = $recdt;
				$avrData["instrumentNumber"] = $doc_num;
				$avrData["numberOfPages"] = $numpages;
				$avrData["considerationAmount"] = $consideration;
				$avrData["legalDescription"] = $docLegal;
				$avrData["grantor"] = $grantor;
				$avrData["grantee"] = $grantee;
				
				$result->avr[] = $avrData;			
				
				$skip = 1;
				$allDocs = array();
				foreach(explode("JumpToTransactionItemId", $page) as $split)
				{
					if($skip != 1)
					{
						$str = explode("'",explode("'", $split)[1])[0];
						preg_match("/\d{8}/", $str, $match);
						if(count($match)>0)
						{
							array_push($allDocs,$match[0]);
						}
							
					}
					
					$skip++;
					
				}
					
					$counter = count($allDocs);
					// echo "Docs count $num \n";
					// foreach($allDocs as $r)
					// {
						// echo $r."\n";
					// }
					for($i=0;$i< $counter+1;$i++)
					{
					
						$driver->wait()->until(
						WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath('//*[@alt="Loading image..Please Wait.."]'))
						);
						
						$page = $driver->getPageSource();
						if($i == 0)
						{
							// do nothing
						}
						else
						{
							
							$docLegal = $book = $pg = $st = $recdt = $bktype = $bkpg = $insnum = $num = $consideration = $grantor = $grantee = "";

							$st = $driver->getPageSource();
							
							$doc_Type = trim(explode("<",explode("px;\">",explode("Doc Type:",$page)[1])[1])[0]);
							$doc_Type = str_replace("&amp;","&",str_replace("/","-",$doc_Type));
							$doc_num = trim(explode("</div",explode("formInput\">",explode("Instrument Number:&nbsp;",$page)[1])[1])[0]);
							
							$recdt = trim(explode("<",explode("\">",explode("Record Date:",$st)[1])[1])[0]);
							$bktype = trim(explode("<",explode("\">",explode("Book Type:",$st)[2])[1])[0]);
							$bktype = preg_replace('/\s+/', ' ', $bktype);
							$bkpg = trim(explode("<",explode("formInput\">",explode("Book / Page:&nbsp;",$st)[1])[1])[0]);
							$book = trim(explode("/",$bkpg)[0]);
							$pg = trim(explode("/",$bkpg)[1]);
							
							$insnum = trim(explode("<",explode("formInput\">",explode("Instrument Number:&nbsp;",$st)[1])[1])[0]);
							
							$numpages = trim(explode("<",explode("\">",explode("Number Of Pages:",$st)[1])[1])[0]);
							
							$docType = trim(explode("<",explode("\">",explode("Doc Type:",$st)[1])[1])[0]);
							if(strpos($st,"Consideration:") !== false)
							{
								$consideration = trim(explode("<",explode("\">",explode("Consideration:",$st)[1])[1])[0]);	
							}
							
							
							if(strpos($st,"DocLegals / Parcel#:") !== false)
							{
								$docLegal = trim(explode("</span>",explode("<span>",explode("DocLegals / Parcel#:",$st)[1])[1])[0]);
							}
							if(strpos($st,"Mortgagor / Borrower:") !== false)
							{
								$grantor = trim(explode("</span>",explode("<span>",explode("\">",explode("Mortgagor / Borrower:",$st)[1])[1])[1])[0]);
							}
							
							if(strpos($st,"Mortgagee / Lender:") !== false)
							{
								$grantee = trim(explode("</span>",explode("<span>",explode("\">",explode("Mortgagee / Lender:",$st)[1])[1])[1])[0]);	
							}
							
							// till here
							
							$avrData = array();
							$avrData["documentFileName"] = $doc_Type."_".$doc_num.".pdf";
							$avrData["documentType"] = $doc_Type;
							$avrData["book"] = $book;
							$avrData["page"] = $pg;
							$avrData["bookPage"] = $bkpg;
							$avrData["bookType"] = $bktype;
							$avrData["recordingDate"] = $recdt;
							$avrData["instrumentNumber"] = $doc_num;
							$avrData["numberOfPages"] = $numpages;
							$avrData["considerationAmount"] = $consideration;
							$avrData["legalDescription"] = $docLegal;
							$avrData["grantor"] = $grantor;
							$avrData["grantee"] = $grantee;
							
							$result->avr[] = $avrData;
							
					
						}
						$savedFile = $path."\\".$doc_Type."_".$doc_num.".pdf";
						// echo $savedFile."\n";
						if (!file_exists($savedFile))
						{
							$driver->wait()->until(
							WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::xpath('//*[@alt="Loading image..Please Wait.."]'))
							);
							
							$driver->switchTo()->frame('imgFrame1');
							$driver->wait()->until(
								WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('ImageInPdf'))
							);				
							$driver->switchTo()->frame('ImageInPdf');
							$driver->wait()->until(
							WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('open-button'))
							);
							sleep(1);
							$driver->findElement(WebDriverBy::id('open-button'))->click();
							sleep(2);
						
							// echo $doc_Type;
							$result->rename("$doc_Type"."_".$doc_num);	
							// $scount++;				
							sleep(3);
						}
						
						$driver->switchTo()->window($driver->getWindowHandles()[1]);
						
						if($i < $counter)
							$driver->executeScript("JumpToTransactionItemId('".$allDocs[$i]."')");
						else
							break;

						sleep(5);
					}
				}
				else
				{
					// $result->taxError = "Not able to find a matching record for the supplied input.";
					$writeError = "Not able to find a matching record for the supplied input.";
					$driver->findElement(WebDriverBy::id("printResults"))->click();
					$driver->findElement(WebDriverBy::id("divResultsPrint"))->findElements(WebDriverBy::tagName("button"))[0]->click();
					$result->rename("$search_name-NO_MATCH_FOUND");
				}	
			}
			return $writeError;
	}
	
	
	$errorOccurred = "";
	$errorOccurred = nameSearch($driver, $primary_name, $path, $loan_amt, $loan_dt, $result);
	if($secondary_name != "")
	{
		if(count($driver->getWindowHandles()) > 1)
		{
			$driver->close();
			$driver->switchTo()->window($driver->getWindowHandles()[0]);			
		}

		$errorOccurred = nameSearch($driver, $secondary_name, $path, $loan_amt, $loan_dt, $result);
	}
	
	if(strpos($errorOccurred,"Not able to find a matching record") !== false)
	{
		if((count(glob($path . "/$primary_name-NO_MATCH_FOUND.pdf"))) == 1)
		{
			$interPri_name = interchange($primary_name);
			$errorOccurred = nameSearch($driver, $interPri_name, $path, $loan_amt, $loan_dt, $result);
		}
		if((count(glob($path . "/$secondary_name-NO_MATCH_FOUND.pdf"))) == 1)
		{
			$interSec_name = interchange($secondary_name);
			$errorOccurred = nameSearch($driver, $interSec_name, $path, $loan_amt, $loan_dt, $result);			
		}
	}

	
	if($errorOccurred != "")
	{
		if((count(glob($path . '/*.pdf')) - count(glob($path . '/*HIT.pdf')))  >= 1)
		{
			if((count(glob($path . '/*.pdf')) == count(glob($path . '/*No_Results.pdf'))))
			{
				$result->taxError = "No Results to Display for the supplied input";
			}
			else if (count(glob($path . '/*.pdf')) == (count(glob($path . '/*No_Results.pdf')) + count(glob($path . '/*NO_MATCH_FOUND.pdf'))) )
			{
				$result->taxError = "Not able to find a matching record for the supplied input";
			}
			else
			{
				$result->taxError = $errorOccurred;
			}
			
		}
		else
		{
			$result->taxError = $errorOccurred;
		}		
	}

	
	
	$driver->quit();	
}

catch (Exception $e)
{
	// Log error
	error_log($app_name.":".$e->getMessage()." ".$e->getTraceAsString());
	
	//WriteResult($parcelData,"","","","",$e->getMessage());
	$result->otherError = $e->getMessage();
	
	if ($driver != null)
		$driver->quit();
}
?>