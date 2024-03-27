<?php

use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

//Format Name
function useKey($dr, $key, $times)
{
	for($i=1; $i<= $times; $i++)
	{
		if($key == "TAB")
			$dr->getKeyboard()->pressKey(WebDriverKeys::TAB);
		if($key == "ENTER")
			$dr->getKeyboard()->pressKey(WebDriverKeys::ENTER);
	}
	
		
}
function split_name($string)
{
	$string = strtoupper($string);
	$string = str_replace(",","",$string);
	$string = str_replace(' JR', '',strtoupper($string));
	$string = str_replace(' SR', '',strtoupper($string));	
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
			if ($num2 == 4) 
			{
				$sname = explode(" ",$secPart, 4)[3].",".explode(" ",$secPart,4)[0]." ".explode(" ",$secPart,4)[1]." ".explode(" ",$secPart,4)[2];
			} 
			else if($num2 == 3) 
			{   
				  $sname = explode(" ",$secPart, 3)[2].",".explode(" ",$secPart,3)[0]." ".explode(" ",$secPart,3)[1];
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
			$name = explode(" ",$firPart, 3)[2].",".explode(" ",$firPart,3)[0]." ".explode(" ",$firPart,3)[1];
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
			
		if ($num == 4) 
		{
			$name = explode(" ",$string, 4)[3].", ".explode(" ",$string,4)[0]." ".explode(" ",$string,4)[1]." ".explode(" ",$string,4)[2];
		} 
		else if($num == 3)
		{   
			 $name = explode(" ",$string, 3)[2].",".explode(" ",$string,3)[0]." ".explode(" ",$string,3)[1]; 
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
	// echo "HERE ".$string."\n";
	// EVAN, ARNETT
	$string = strtoupper($string);
	$string = str_replace(",","",$string);
	$string = str_replace(' JR', '',strtoupper($string));
	$string = str_replace(' SR', '',strtoupper($string));	
	$name = "";
	
	$arr = explode(' ', $string);
	$num = count($arr);
		
	if ($num == 3) 
	{
		$name = explode(" ",$string, 3)[1].",".explode(" ",$string,3)[0]." ".explode(" ",$string,3)[2];
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
	
	return $amt; // Return Integer value
}

//Input 10-19-2020

$app_name = $batchDetails->borrowerName;
$loan_amt = $batchDetails->loanAmount;
$loan_dt = $batchDetails->loanDate;

// date conversion

$loan_dt = str_replace("-","/",$loan_dt);
$yr = trim(explode("/",$loan_dt)[2]);
$loan_dt = new DateTime($loan_dt);

//Init
$doc_type = "";
$book_pg = "";
$instrument_num = "";
$record_dt = "";
$mort_found = false;
$primary_name = explode("@",split_name($app_name))[0];
$secondary_name = explode("@",split_name($app_name))[1];
// echo $secondary_name;

try {
	
	
	if (count(glob($path . '/*.*')) > 0)
    {
        array_map('unlink', glob($path . '/*.*'));
    }	
	
	echo $app_name." : ".$batchDetails->county."\n";
	$res_found = false;		
	$url = "https://vaclmweb1.brevardclerk.us/AcclaimWeb/";
	$driver->get($url);
	
	//Home Page
	
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('mnname'))
	);

	
	$driver->findElement(WebDriverBy::xpath('//*[@id="boxHolder2"]/div[1]/h4/button/img'))->click();

	
	if(strpos($driver->getPageSource(), "I accept the conditions above") !== false)
		$driver->findElement(WebDriverBy::xpath('//*[@id="btnButton"]'))->click();
	
		
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('SearchOnName'))
	);
	
	function nameSearch($driver, $search_name, $path, $loan_amt, $loan_dt, $result, $yr, $nreq)
	{
		echo $search_name."\n";
		$writeError = "";
		$driver->findElement(WebDriverBy::id('SearchOnName'))->clear();	
		sleep(1);
		$driver->findElement(WebDriverBy::id('SearchOnName'))->sendKeys($search_name);
		$driver->findElement(WebDriverBy::id('RecordDateFrom'))->click();
		$driver->getKeyboard()->pressKey(WebDriverKeys::END);
		for($i=1; $i <= 4; $i++)
			$driver->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
		
		$driver->findElement(WebDriverBy::id('RecordDateFrom'))->sendKeys($yr);	
			$driver->findElement(WebDriverBy::id('RecordDateTo'))->click();
			$driver->getKeyboard()->pressKey(WebDriverKeys::END);
			for($i=1; $i <= 4; $i++)
			$driver->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
		
			$driver->findElement(WebDriverBy::id('RecordDateTo'))->sendKeys(intval($yr)+1);		
		$driver->findElement(WebDriverBy::id('btnSearch'))->click();
		sleep(2);
		
		// echo $driver->findElement(WebDriverBy::id('frmSchTarget'))->getText();
		
		if(strpos($driver->findElement(WebDriverBy::id('frmSchTarget'))->getText(), "No names found.") !== false)
		{
			$search_name = interchange($search_name);
			$driver->findElement(WebDriverBy::id('SearchOnName'))->clear();
			sleep(1);
			$driver->findElement(WebDriverBy::id('SearchOnName'))->sendKeys($search_name);
			$stDate = $loan_dt->format('m/d/Y');
			$driver->findElement(WebDriverBy::id('RecordDateFrom'))->sendKeys($stDate);	
			
			$driver->findElement(WebDriverBy::id('RecordDateTo'))->click();
			$driver->getKeyboard()->pressKey(WebDriverKeys::END);
			for($i=1; $i <= 4; $i++)
			$driver->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
		
			$driver->findElement(WebDriverBy::id('RecordDateTo'))->sendKeys(intval($yr)+1);		
			$driver->findElement(WebDriverBy::id('btnSearch'))->click();
			sleep(3);	
			if(strpos($driver->getPageSource(), "No Results to Display") !== false)
			{
				
				$writeError = "No Results to Display for the supplied input"; 
				// $result->taxError = "No Results to Display for the supplied input";
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
				
				$driver->findElement(WebDriverBy::id('frmSchTarget'))->click();
				sleep(1);
				
				$driver->executeScript('document.querySelector("#frmSchTarget > form > div:nth-child(5) > input").click();');
				sleep(2);
				
				$driver->wait()->until(
				WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('printResults'))
				);
				if($nreq == false)
				{				
					// $driver->executeScript('document.querySelector("#RsltsGrid > div.t-grid-pager.t-grid-top > div.t-pager.t-reset > div.t-page-size > div > div > span.t-select > span").click();');
					
					sleep(2);
				
				// $driver->executeScript('document.querySelector("body > div:nth-child(27) > div > ul > li:nth-child(6)").click();');
				
				// li[7] = 500, li[6] = 250, li[5] = 200, li[4] = 150, li[3] = 100, li[2] = 50, li[1] = 25
				
									
				}
				
				$driver->wait()->until(
				WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('SearchFooter'))
				);
				sleep(2);
				
				$total_row_count = count($driver->findElements(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr")));
				
				
				if ($total_row_count <= 2)
				{
					sleep(5);
				}
				
				$applicant_flag = "NO";
				$applicant_row = "0";
				$temp_instrument_num = $temp_documentType = $temp_bookPage = $temp_docLink = "";			
				$temp_record_dt = "";			
				$temp_loan_amt = "";
				$mort_prim_bookPage = "";
				
				$lastDocs = ["SATISFACTION","SATISFACTION NO FEE","RELEASE"];
				$allDocs = array();
				// flags
				$tot_page = trim(explode("of", $driver->findElement(WebDriverBy::className("t-page-i-of-n"))->getText())[1]);

				// HIT page
				$search_name_mod = str_replace("-"," ",$search_name);
				$fileName = "$search_name_mod".'_'."HIT";
				$driver->findElement(WebDriverBy::id("printResults"))->click();
				$driver->findElement(WebDriverBy::id("divResultsPrint"))->findElements(WebDriverBy::tagName("button"))[0]->click();
				sleep(2);
				$result->rename($fileName);		
					
				// echo "activePage".$driver->findElement(WebDriverBy::className("t-state-active"))->getText()."\n";
				
				// next page
				
				// $driver->findElement(WebDriverBy::linkText("next"))->click();
				// sleep(2);
				
				// echo "activePage".$driver->findElement(WebDriverBy::className("t-state-active"))->getText()."\n";
				
				$sat_found = false;
				$mort_found = false;
				$mfound = false;
				
				for($p=1; $p <= $tot_page; $p++)
				{
					$pageNavigation = true;
					
					for($i=1; $i <= $total_row_count; $i++)
					{  
						$slno = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[1]"))->getText();
						// echo $slno."\n";
						$temp_record_dt = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[6]"))->getText();
						
						$temp_record_dt = str_replace("-","/",$temp_record_dt);
						$temp_record_dt = new DateTime($temp_record_dt);
						
						
						$temp_instrument_num = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[10]"))->getText();
						
						$temp_loan_amt = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[11]"))->getText();			
						
						$temp_documentType = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[7]"))->getText();
						
						$temp_bookPage = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[9]"))->getText();
						
						
						if($mort_found == false)
						{
							// echo $i." : ".$loan_amt." : ".format_amt($temp_loan_amt)."\n";
							if($loan_amt == format_amt($temp_loan_amt) && $temp_record_dt >= $loan_dt && $temp_documentType == "MORTGAGE")
							{
								// echo "Main doc Found".$slno."\n";
								
								$mort_found = true;
								$mort_prim_bookPage = $temp_bookPage;
							}				
						}

						
						if($mort_found == true)
						{
							
							$savedpdfFiles = glob($path . '/*.pdf');
							
							foreach($savedpdfFiles as $pdf)
							{
								// echo $pdf." AND ".$item."\n";
								if(strpos($pdf, $temp_instrument_num) !== false)
								{
									
									$mfound = true;
									break;
								}
								
							}
							if($mfound == false)
							{
								$driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]"))->click();
								sleep(5);
								
								$numWin = count($driver->getWindowHandles()) - 1;
								
								// echo "After Click - ".count($driver->getWindowHandles())."\n";
								
								$driver->switchTo()->window($driver->getWindowHandles()[$numWin]);
								$page = $driver->getPageSource();
								
								// echo $page; // extraction
								$docLegal = $book = $pg = $st = $recdt = $bktype = $bkpg = $insnum = $num = $consideration = $grantor = $grantee = "";
								
								$doc_Type = trim(explode("<",explode("px;\">",explode("Doc Type:",$page)[1])[1])[0]);
								$doc_Type =  str_replace("&amp;","&",str_replace("/","-",$doc_Type));
								$doc_num = trim(explode("</div",explode("formInput\">",explode("Clerk File Number:&nbsp;",$page)[1])[1])[0]);
								


								$st = $driver->getPageSource();
								
								$recdt = trim(explode("<",explode("\">",explode("Record Date:",$st)[1])[1])[0]);
								$bktype = trim(explode("<",explode("\">",explode("Book Type:",$st)[1])[1])[0]);
								$bktype = preg_replace('/\s+/', ' ', $bktype);
								$bkpg = trim(explode("<",explode("formInput\">",explode("Book / Page:&nbsp;",$st)[1])[1])[0]);
								$book = trim(explode("/",$bkpg)[0]);
								$pg = trim(explode("/",$bkpg)[1]);
															
								$numpages = trim(explode("<",explode("\">",explode("Number Of Pages:",$st)[1])[1])[0]);
								
								$docType = trim(explode("<",explode("\">",explode("Doc Type:",$st)[1])[1])[0]);
								if(strpos($st,"Consideration:") !== false)
								{
									$consideration = trim(explode("<",explode("\">",explode("Consideration:",$st)[1])[1])[0]);	
								}
								
								
								if(strpos($st,"DocLegal:") !== false)
								{
									$docLegal = trim(explode("\"\">",explode("</span>",explode("DocLegal:",$st)[1])[0])[1]);
								}
								
								if(strpos($st,"From Party:") !== false)
								{
									$grantor = "";
									$cnt = 1;
									foreach(explode("</span>",explode("x;\">",explode("From Party:",$st)[1])[1]) as $item)
									{
											if(strpos($item,"\"\">") !== false)
											{
												if($grantor == "")
												{
													$grantor = trim(explode("\"\">",$item)[1]);
												}
												else
												{
													$grantor = $grantor."\\n".trim(explode("\"\">",$item)[1]);
												}										
										
											}	
										
									}
									
								}
								
								if(strpos($st,"To Party:") !== false)
								{
									
									$grantee = "";
									$cnt = 1;
									foreach(explode("</span>",explode("x;\">",explode("To Party:",$st)[1])[1]) as $item)
									{
										if(strpos($item,"\"\">") !== false)
										{
											if($grantee == "")
											{
												$grantee = trim(explode("\"\">",$item)[1]);
											}
											else
											{
												$grantee = $grantee."\\n".trim(explode("\"\">",$item)[1]);
											}										
										
										}
									}								
								}
							
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
									foreach(explode("JumpToTransactionItemId", $page) as $split)
									{
										if($skip != 1)
										{
											$str = explode("<",explode(">", $split)[1])[0];
											// echo $str."\n";
											preg_match("/\d{10}/", $str, $match);
											if(count($match)>0)
												array_push($allDocs,$match[0]);
										}
										
										$skip++;
										
									}
										
									
									$driver->switchTo()->frame('imgFrame1');
									
									$driver->wait()->until(
										WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('ImageInPdf'))
									);	
									sleep(1);
									$driver->switchTo()->frame('ImageInPdf');
									$driver->wait()->until(
									WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('open-button'))
									);
									sleep(1);
									// $targetmort = $path."\\".$doc_Type."_".$doc_num.".pdf";
									$savedFiles = glob($path . '/*.pdf');
									$found = false;
									foreach($savedFiles as $pdf)
									{
										if(strpos($pdf, $item) !== false)
										{
											echo $pdf." AND ".$item."\n";
											$found = true;
											break;
										}
										
									}							
									if ($found == false)
									{								
										$driver->findElement(WebDriverBy::id('open-button'))->click();
										sleep(2);
										// echo "After download - ".count($driver->getWindowHandles())."\n";
										// $driver->close();				
										$driver->switchTo()->window($driver->getWindowHandles()[0]);
										$result->rename("$doc_Type"."_"."$doc_num");
									}
									$driver->switchTo()->window($driver->getWindowHandles()[0]);
									break;
							}
							
						}
									
					}
					if($mort_found == true)
					{
						break;
					}
					else
					{
						$driver->findElement(WebDriverBy::linkText("next"))->click();
						sleep(2);				
					}
					
				}
				
				if($mort_found == false)
				{
					// echo "here ads";
					$writeError = "Not able to find a matching record for the supplied input.";
					$driver->findElement(WebDriverBy::id("printResults"))->click();
					$driver->findElement(WebDriverBy::id("divResultsPrint"))->findElements(WebDriverBy::tagName("button"))[0]->click();
					sleep(1);
					$result->rename("NO_match_found_HIT");					
				}
				
				foreach($allDocs as $item)
				{
					sleep(1);
					$page = $driver->getPageSource();
					
					$rowId = count(explode("rowNumClass",explode($item, $page)[0])) - 1;
					
					$doc_ty	= $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$rowId]/td[7]"))->getText();
					// echo $doc_ty;
					// "$doc_Type"."_"."$item"
					$savedFiles = glob($path . '/*.pdf');
					$found = false;
					foreach($savedFiles as $pdf)
					{
						// echo $pdf." AND ".$item."\n";
						if(strpos($pdf, $item) !== false)
						{
							
							$found = true;
							break;
						}
						
					}
					 
					if ($found == false)
					{
												
						$driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$rowId]"))->click();
						sleep(3);
						$numWin = count($driver->getWindowHandles()) - 1;
						// echo "After Click - ".count($driver->getWindowHandles())."\n";
												
						$driver->switchTo()->window($driver->getWindowHandles()[$numWin]);
						sleep(1);
						
						$page = $driver->getPageSource();
						$docLegal = $book = $pg = $st = $recdt = $bktype = $bkpg = $insnum = $num = $consideration = $grantor = $grantee = "";
							
						$doc_Type = trim(explode("<",explode("px;\">",explode("Doc Type:",$page)[1])[1])[0]);
						$doc_Type =  str_replace("&amp;","&",str_replace("/","-",$doc_Type));
						$doc_num = trim(explode("</div",explode("formInput\">",explode("Clerk File Number:&nbsp;",$page)[1])[1])[0]);
						
						$st = $driver->getPageSource();
						
						$doc_Type = trim(explode("<",explode("px;\">",explode("Doc Type:",$page)[1])[1])[0]);
						$doc_Type = str_replace("&amp;","&",str_replace("/","-",$doc_Type));
												
						$recdt = trim(explode("<",explode("\">",explode("Record Date:",$st)[1])[1])[0]);
						$bktype = trim(explode("<",explode("\">",explode("Book Type:",$st)[1])[1])[0]);
						$bktype = preg_replace('/\s+/', ' ', $bktype);
						$bkpg = trim(explode("<",explode("formInput\">",explode("Book / Page:&nbsp;",$st)[1])[1])[0]);
						$book = trim(explode("/",$bkpg)[0]);
						$pg = trim(explode("/",$bkpg)[1]);
													
						$numpages = trim(explode("<",explode("\">",explode("Number Of Pages:",$st)[1])[1])[0]);
						
						$docType = trim(explode("<",explode("\">",explode("Doc Type:",$st)[1])[1])[0]);
						if(strpos($st,"Consideration:") !== false)
						{
							$consideration = trim(explode("<",explode("\">",explode("Consideration:",$st)[1])[1])[0]);	
						}
						
						
						if(strpos($st,"DocLegal:") !== false)
						{
							$docLegal = trim(explode("\"\">",explode("</span>",explode("DocLegal:",$st)[1])[0])[1]);
						}
						if(strpos($st,"From Party:") !== false)
						{
							$grantor = "";
							$cnt = 1;
							foreach(explode("</span>",explode("x;\">",explode("From Party:",$st)[1])[1]) as $item1)
							{
									if(strpos($item1,"\"\">") !== false)
									{
										if($grantor == "")
										{
											$grantor = trim(explode("\"\">",$item1)[1]);
										}
										else
										{
											$grantor = $grantor."\\n".trim(explode("\"\">",$item1)[1]);
										}										
								
									}	
								
							}
							
						}
						
						if(strpos($st,"To Party:") !== false)
						{
							
							$grantee = "";
							$cnt = 1;
							foreach(explode("</span>",explode("x;\">",explode("To Party:",$st)[1])[1]) as $item1)
							{
								if(strpos($item1,"\"\">") !== false)
								{
									if($grantee == "")
									{
										$grantee = trim(explode("\"\">",$item1)[1]);
									}
									else
									{
										$grantee = $grantee."\\n".trim(explode("\"\">",$item1)[1]);
									}										
								
								}
							}								
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
							
						$driver->switchTo()->frame('imgFrame1');
						
						$driver->wait()->until(
							WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('ImageInPdf'))
						);	
						sleep(1);
						$driver->switchTo()->frame('ImageInPdf');
						$driver->wait()->until(
						WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('open-button'))
						);
						sleep(1);				
						
						$driver->findElement(WebDriverBy::id('open-button'))->click();
						sleep(2);
						// echo "After download - ".count($driver->getWindowHandles())."\n";
						// $driver->close();				
						$driver->switchTo()->window($driver->getWindowHandles()[0]);
						$result->rename("$doc_Type"."_"."$item");
					}
					
				}
			

		}
		return $writeError;
	} // function end
	
	$errorOccurred = "";
	$errorOccurred = nameSearch($driver, $primary_name, $path, $loan_amt, $loan_dt, $result, $yr, false);

	if($secondary_name != "")
	{
		if(count($driver->getWindowHandles()) > 1)
		{
			// echo "close wind \n";
			// echo count($driver->getWindowHandles())."\n";
			sleep(2);
			// $driver->close();
			// $driver->switchTo()->window($driver->getWindowHandles()[0]);			
		}
		// echo "reached \n";
		// echo count($driver->getWindowHandles())."\n";
		sleep(2);
		$errorOccurred = nameSearch($driver, $secondary_name, $path, $loan_amt, $loan_dt, $result, $yr, true);
	}

	if($errorOccurred != "")
	{
		$result->taxError = $errorOccurred;
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