<?php

// date_default_timezone_set("America/Los_Angeles");

use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

//Format Name
function split_name($string)
{
	
	$string = preg_replace('/\s+/', ' ',strtoupper($string));
	$string = str_replace(' JR', '',strtoupper($string));
	$string = str_replace(' SR', '',strtoupper($string));
	$name = $sname = "";
	// echo count(explode(" AND ", $string));
	
	if(strpos($string, " AND ") !== false)
	{
	
		if(count(explode(" ", explode(" AND ", $string)[0])) == 3)
		{
			$name = explode(" ", explode(" AND ", $string)[0])[2].",".explode(" ", explode(" AND ", $string)[0])[0]." ".explode(" ", explode(" AND ", $string)[0])[1];
		}
		if(count(explode(" ", explode(" AND ", $string)[1])) == 3)
		{		
			$sname = explode(" ", explode(" AND ", $string)[1])[2].",".explode(" ", explode(" AND ", $string)[1])[0]." ".explode(" ", explode(" AND ", $string)[1])[1];
		}
		if(count(explode(" ", explode(" AND ", $string)[0])) == 2)
		{
			$name = explode(" ", explode(" AND ", $string)[0])[1]." ".explode(" ", explode(" AND ", $string)[0])[0];
		}
		if(count(explode(" ", explode(" AND ", $string)[1])) == 2)
		{
			$sname = explode(" ", explode(" AND ", $string)[1])[1]." ".explode(" ", explode(" AND ", $string)[1])[0];
		}
		
	}
	else
	{
		if(count(explode(" ", $string)) == 3)
			$name = explode(" ",$string)[2].",". explode(" ",$string)[0]." ". explode(" ",$string)[1];
		if(count(explode(" ", $string)) == 2)
			$name = explode(" ",$string)[1].",".explode(" ",$string)[0];
		if(count(explode(" ", $string)) == 1)
			$name = explode(" ",$string)[0];
	}
	
	return $name."@".$sname;
}

function split_name_interchange($string)
{

	$string = preg_replace('/\s+/', ' ',strtoupper($string));
	$string = str_replace('JR', '',strtoupper($string));
	$string = str_replace('SR', '',strtoupper($string));		
	$name = $sname = "";
	// echo count(explode(" AND ", $string));
	
	if(strpos($string, " AND ") !== false)
	{
	
		if(count(explode(" ", explode(" AND ", $string)[0])) == 3)
		{
			$name = explode(" ", explode(" AND ", $string)[0])[0].",".explode(" ", explode(" AND ", $string)[0])[2]." ".explode(" ", explode(" AND ", $string)[0])[1];	
		}
		if(count(explode(" ", explode(" AND ", $string)[1])) == 3)
		{
			$sname = explode(" ", explode(" AND ", $string)[1])[0].",".explode(" ", explode(" AND ", $string)[1])[2]." ".explode(" ", explode(" AND ", $string)[1])[1];
		}
		
		if(count(explode(" ", explode(" AND ", $string)[0])) == 2)
		{
			$name = explode(" ", explode(" AND ", $string)[0])[0]." ".explode(" ", explode(" AND ", $string)[0])[1];
		}
		if(count(explode(" ", explode(" AND ", $string)[1])) == 2)
		{
			$sname = explode(" ", explode(" AND ", $string)[1])[0]." ".explode(" ", explode(" AND ", $string)[1])[1];
		}
		
	}
	else
	{
		if(count(explode(" ", $string)) == 3)
			$name = explode(" ",$string)[0].",". explode(" ",$string)[2]." ". explode(" ",$string)[1];
		if(count(explode(" ", $string)) == 2)
			$name = explode(" ",$string)[0].",".explode(" ",$string)[1];
		if(count(explode(" ", $string)) == 1)
			$name = explode(" ",$string)[0];
	}
	
	return $name."@".$sname;
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

$app_name = str_replace("ETAL","", strtoupper($batchDetails->borrowerName));
$loan_amt = format_amt(strval($batchDetails->loanAmount));
echo $batchDetails->loanDate."\n";
$loan_dt = strval(str_replace("-","/",$batchDetails->loanDate));
// echo $loan_amt."\n";

// echo $loan_dt."\n";

$loan_dt = str_replace("-","/",$loan_dt);
// $loan_dt = date_create_from_format();

$loan_dt = new DateTime($loan_dt);
$stringDate = $loan_dt->format('m/d/Y');
// echo $stringDate."\n";
//Init
$doc_type = "";
$book_pg = "";
$instrument_num = "";
$record_dt = "";

$primary_name = trim(explode("@",split_name($app_name))[0]);
$secondary_name = trim(explode("@",split_name($app_name))[1]);
$interprimary_name = trim(explode("@",split_name_interchange($app_name))[0]);
$intersecondary_name = trim(explode("@",split_name_interchange($app_name))[1]);
try {
	
	echo $app_name." : ".$batchDetails->county."\n";
	
	$url = "https://oncore.duvalclerk.com/";
	$driver->get($url);
	
	//Home Page
	$driver->wait()->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('mnname'))
	);
	
	
	$driver->findElement(WebDriverBy::xpath('//*[@id="boxHolder2"]/div[1]/h4/button/img'))->click();

	
	if(strpos($driver->getPageSource(), "I accept the conditions above") !== false)
	{	
		$driver->findElement(WebDriverBy::xpath('//*[@id="btnButton"]'))->click();
	}
	
	$driver->wait()->until(
		WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('SearchOnName'))
		);
	
	
	function avrDocCollect($search_name, $driver, $loan_amt, $loan_dt, $result, $skip, $path)
	{
		echo $search_name."\n";
		$taxerror = "";
		$driver->switchTo()->window($driver->getWindowHandles()[0]);
		if($skip == true)
			$driver->findElement(WebDriverBy::xpath('//*[@value="Reset"]'))->click();sleep(1);
		
		$driver->findElement(WebDriverBy::id('SearchOnName'))->sendKeys($search_name);
		$driver->findElement(WebDriverBy::id('RecordDateFrom'))->clear();
		$stDate = $loan_dt->format('m/d/Y');
		$driver->findElement(WebDriverBy::id('RecordDateFrom'))->sendKeys($stDate);
		
		$driver->findElement(WebDriverBy::id('btnSearch'))->click();
		sleep(2);
		
		// echo $driver->findElement(WebDriverBy::id('frmSchTarget'))->getText();
		
			if(strpos($driver->findElement(WebDriverBy::id('frmSchTarget'))->getText(), "No results found for the search.") !== false || strpos($driver->findElement(WebDriverBy::id('frmSchTarget'))->getText(), "No names found. Please try your search again.") !== false)
			{
				// echo "her";
				$taxerror = "Error";
				// $result->appraisalError = $tax;
			}
			else
			{	
				
			$driver->findElement(WebDriverBy::id('frmSchTarget'))->click();
			sleep(1);
			$driver->findElement(WebDriverBy::name('NameListTreeView_checkedNodes[0].Checked'))->click();
			sleep(1);
			$driver->executeScript('document.querySelector("#frmSchTarget > form > div:nth-child(5) > input").click();');
			sleep(1);
					
			$driver->wait()->until(
			WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('printResults'))
			);
			
			// click on dropdown
			try
			{
			$driver->wait()->until(
			WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::linkText('Refresh'))
			);
				// $driver->findElement(WebDriverBy::className('t-select'))->click();
				// echo "Clicked\n";
				// sleep(10);
				$driver->executeScript('document.querySelector("#RsltsGrid > div.t-grid-pager.t-grid-top > div.t-pager.t-reset > div.t-page-size > div > div > span.t-select > span").click();');
				// sleep(100);
				// li[7] = 500, li[6] = 250, li[5] = 200, li[4] = 150, li[3] = 100, li[2] = 50, li[1] = 25
				
				// clicking on the number of items to be shown on the resulting pane
			
				$driver->findElement(WebDriverBy::xpath('/html/body/div[11]/div/ul/li[7]'))->click();
		
				sleep(1);	
			}
			catch(Exception $ex)
			{
				// ignore
			}
			
			
			
			$driver->wait()->until(
			WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('SearchFooter'))
			);
			
			$total_row_count = count($driver->findElements(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr")));
			if ($total_row_count <= 2)
			{
				sleep(5);
				
			}			
			$total_row_count = count($driver->findElements(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr")));
			
			// ECHO $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table"))->getAttribute('innerHTML');
			ECHO $total_row_count."\n";

			
			$applicant_flag = "NO";
			$applicant_row = "0";
			$temp_instrument_num = $temp_documentType = $temp_bookPage = $temp_docLink = "";			
			$temp_record_dt = "";			
			$temp_loan_amt = "";
			$mort_prim_bookPage = "";
			
			$lastDocs = ["SATISFACTION","SATISFACTION NO FEE","RELEASE"];
			
			// flags
			$tot_page = trim(explode("of", $driver->findElement(WebDriverBy::className("t-page-i-of-n"))->getText())[1]);
			
			// echo "activePage".$driver->findElement(WebDriverBy::className("t-state-active"))->getText()."\n";
			
			// next page
			
			// $driver->findElement(WebDriverBy::linkText("next"))->click();
			// sleep(2);
			
			// echo "activePage".$driver->findElement(WebDriverBy::className("t-state-active"))->getText()."\n";
			$allDocs = array();
			$sat_found = false;
			$mort_found = false;
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
					
					
					$temp_instrument_num = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[5]"))->getText();
					
					$temp_loan_amt = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[11]"))->getText();			
					
					$temp_documentType = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[7]"))->getText();
					
					$temp_bookPage = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[9]"))->getText();
					
					$temp_docLink = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[10]"))->getText();
					
					// echo $temp_docLink."\n";
					
					if($mort_found == false)
					{
						// echo $i." : ".$loan_amt." : ".format_amt($temp_loan_amt)."\n";
						if(format_amt($loan_amt) == format_amt($temp_loan_amt) && $temp_record_dt >= $loan_dt && (strtoupper($temp_documentType) == "MORTGAGE" || strtoupper($temp_documentType) == "CREDIT UNION MORTGAGE" ))
						{
							// echo "Main doc Found".$slno."\n";
							
							$mort_found = true;
							$mort_prim_bookPage = $temp_bookPage;
							array_push($allDocs, "$p#$i#$mort_prim_bookPage#$temp_instrument_num#$temp_documentType");
							$fileName = "$search_name".'_'."HIT";
							$driver->findElement(WebDriverBy::id("printResults"))->click();
							$driver->findElement(WebDriverBy::id("divResultsPrint"))->findElements(WebDriverBy::tagName("button"))[0]->click();
							sleep(3);
							$result->rename($fileName);
							$pageNavigation = false;
							
							break;
						}				
					}


								
				}
				
				if($pageNavigation == true)
				{
					$driver->findElement(WebDriverBy::linkText("next"))->click();
					sleep(2);				
				}
				else
				{
					break;
				}
				

			}
			
			// getting all related documents
			
			if($mort_found == true)
			{
				for($p=1; $p <= $tot_page; $p++)
				{
					for($i=1; $i <= $total_row_count; $i++)
					{
						$temp_docLink = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[10]"))->getText();
						$temp_instrument_num = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[5]"))->getText();
						$temp_documentType = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$i]/td[7]"))->getText();
							if(strpos($temp_docLink, $mort_prim_bookPage) !== false)
							{
								echo $temp_docLink." HEREEEEE \n";
								array_push($allDocs, "$p#$i#$mort_prim_bookPage#$temp_instrument_num#$temp_documentType");
							}
					}
				}
			}
					
			if($mort_found == false)
			{
				$result->taxError = "There was no matching Mortgage document found for this search!";
				$fileName = "$search_name No_Mortgage_HIT";
				$driver->findElement(WebDriverBy::id("printResults"))->click();
				$driver->findElement(WebDriverBy::id("divResultsPrint"))->findElements(WebDriverBy::tagName("button"))[0]->click();
				$result->rename($fileName);			
			}
			else
			{
				
				foreach($allDocs as $item)
				{
					
					$targetPage = explode("#", $item)[0];
					$targetRow = explode("#", $item)[1];
					$instr_num = explode("#", $item)[3];
					$doc_ty = explode("#", $item)[4];
					$savedFile = $path."\\".$instr_num."_".$doc_ty.".pdf";
					if (!file_exists($savedFile))
					{
						$taregtInstNumber = $driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$targetRow]/td[5]"))->getText();
						
						if($taregtInstNumber != $instr_num)
						{
							$driver->findElement(WebDriverBy::linkText("$targetPage"))->click();
							sleep(2);	
						}
						
						$driver->findElement(WebDriverBy::xpath("//*[@id='RsltsGrid']/div[4]/table/tbody/tr[$targetRow]"))->click();
						sleep(2);
							
						$driver->switchTo()->window($driver->getWindowHandles()[1]);
						sleep(3);
						// EXTRACTION 
						$docLegal = $book = $pg = $st = $recdt = $bktype = $bkpg = $insnum = $num = "";
							
						$driver->wait()->until(
							WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('jmpBook'))
							);
						$book = $driver->findElement(WebDriverBy::id("jmpBook"))->getAttribute('value');
						$pg = $driver->findElement(WebDriverBy::id("jmpPg"))->getAttribute('value');
						$st = $driver->getPageSource();
						$recdt = trim(explode("<",explode("\">",explode("Record Date:",$st)[1])[1])[0]);
						$bktype = trim(explode("<",explode("\">",explode("Book Type:",$st)[1])[1])[0]);
						$bktype = preg_replace('/\s+/', ' ', $bktype);
						$bkpg = trim(explode("<",explode("\">",explode("Book / Page:",$st)[1])[1])[0]);
						$insnum = trim(explode("<",explode("\">",explode("Instrument Number:&nbsp;",$st)[1])[1])[0]);
						$numpages = trim(explode("<",explode("\">",explode("Number Of Pages:",$st)[1])[1])[0]);
						
						$docType = trim(explode("<",explode("\">",explode("Doc Type:",$st)[1])[1])[0]);
						$consideration = trim(explode("<",explode("\">",explode("Consideration:",$st)[1])[1])[0]);
						
						if(strpos($st,"DocLegals / Parcel#:") !== false)
						{
							$docLegal = trim(explode("</span>",explode("<span>",explode("DocLegals / Parcel#:",$st)[1])[1])[0]);
						}
						
						$grantor = trim(explode("</span>",explode("<span>",explode("Grantor:",$st)[1])[1])[0]);
						$grantee = trim(explode("</span>",explode("<span>",explode("Grantee:",$st)[1])[1])[0]);
						// till here
						
						$avrData = array();
						$avrData["documentFileName"] = $instr_num."_".$doc_ty.".pdf";
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
						
						$result->avr[] = $avrData;
						
						// echo $recdt."\n".$bktype."\n".$bkpg."\n".$insnum."\n".$numpages."\n".$docType."\n".$consideration."\n".$docLegal."\n".$grantor."\n".$grantee."\n";						
						
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
						$driver->close();				
						// $driver->switchTo()->window($driver->getWindowHandles()[0]);
						$driver->switchTo()->window($driver->getWindowHandles()[0]);
						$result->rename("$instr_num"."_"."$doc_ty"); 
					}
				}
			}

		}
		return $taxerror;
	}
	
	// call functions
	
	$error_flag = "";
	$error_flag = avrDocCollect($primary_name, $driver, $loan_amt, $loan_dt, $result, false, $path);
	
	if($secondary_name != "")
	{
		$error_flag = avrDocCollect($secondary_name, $driver, $loan_amt, $loan_dt, $result, true, $path);
	}
	
	if($error_flag != "")
	{
		// inter change first and Last names and try again
		$error_flag = "";
		$error_flag = avrDocCollect($interprimary_name, $driver, $loan_amt, $loan_dt, $result, true, $path);
		if($intersecondary_name != "")
		{
			$error_flag = avrDocCollect($intersecondary_name, $driver, $loan_amt, $loan_dt, $result, true, $path);
		}		
		
	}
	
	if($error_flag !="")
	{
		$result->taxError = "No Search result found for the supplied names";
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