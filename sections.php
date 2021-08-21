public function sectionsOut($id=false)
	{
		if(!CModule::IncludeModule("iblock")) return false;
		if(empty($this->iblockId)) return false;

		global $USER;

		$arFilter = array("IBLOCK_ID" =>$this->iblockId, ">TIMESTAMP_X"  => $this->timeback, "MODIFIED_BY" =>  $USER->GetID());

		if($id !== false) {
			$arFilter = '';
			$arFilter = array("ID" => $id, "IBLOCK_ID" => $this->iblockId);
		}
		$arSort = array("ID" => "ASC");
		$arSelect = array(
			"IBLOCK_ID",
			"ID",
			"IBLOCK_SECTION_ID",
			"CODE",
			"SORT",
			"NAME",
			"ACTIVE",
			"DESCRIPTION",
			"DESCRIPTION_TYPE",
			"UF_ORIGINAL_ID",
			"UF_ORIGINAL_SEC_ID"
			);


		$rsSections = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);
		while($arSection = $rsSections->GetNext())
		{

			$arIdSec[$arSection['ID']] = $arSection['IBLOCK_SECTION_ID'];
			$arIdSec[$arSection['ID']] = $arSection;
			$arIdSec[$arSection['ID']]['ID'] = $arSection['UF_ORIGINAL_ID'];
			$arIdSec[$arSection['ID']]['IBLOCK_SECTION_ID'] = $arSection['UF_ORIGINAL_SEC_ID'];
			
		}
	if(!empty($arIdSec)) $sections = $this->send("setSections", "products", $arIdSec);

		if(!empty($sections)){
			foreach ($sections as $key => $value) {
				if(!empty($value["ADDID"]))$UF_ORIGINAL_ID = $value["ADDID"];
				if(!empty($value["UPID"]))$UF_ORIGINAL_ID = $value["UPID"];
				if(!empty($value["ADDSEC_ID"]))$UF_ORIGINAL_SEC_ID = $value["ADDSEC_ID"];
				if(!empty($value["UPSEC_ID"]))$UF_ORIGINAL_SEC_ID = $value["UPSEC_ID"];
				$arFields = array(
					"UF_ORIGINAL_SEC_ID" 	=> $UF_ORIGINAL_SEC_ID,
					"UF_ORIGINAL_ID" 		=> $UF_ORIGINAL_ID,
					"NOTSYNC"	=> true

				);

				$sec = new CIBlockSection;
				$res = $sec->Update($key, $arFields, false);
			}

		}
			$result = 'Выгружен раздел каталога на '.$this->siteURL;
			$errors = 'Что то пошло не так, при выгрузке каталога на '.$this->siteURL;
		if(!empty($sections)) return $sections;/* else return $errors;*/
		
	}
