<?
class AudioMaxSystem extends IPSModule {

    public function Create(){
        //Never delete this line!
        parent::Create();
       
        //These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
		//CreateVariableProfile($ProfileName, $ProfileType, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Icon,$Wert,$Name,$Color)
		$this->CreateVariableProfile("ESERA.AMVolume",1,"%",0,40,1,0,"Intensity");
		$this->CreateVariableProfile("ESERA.AMGain",1,"%",0,15,1,0,"Intensity");
	    $this->CreateVariableProfile("ESERA.AMTone",1,"%",0,15,1,0,"Intensity");
		$this->CreateVariableProfile("ESERA.AMBalance",1,"%",0,15,1,0,"Intensity");
		$this->CreateVariableProfile("ESERA.AMInput",1,"",1,4,1,0,"");
		//$this->CreateVariableProfile("ESERA.AMMute",0,"",0,1,1,0,"Power");

		
		for($i = 1; $i <= 2; $i++){
    			$this->RegisterVariableinteger("volume".$i, "Volume ".$i, "ESERA.AMVolume");			
    			$this->EnableAction("volume".$i);
				
				$this->RegisterVariableinteger("gain".$i, "Gain ".$i, "ESERA.AMGain");
    			$this->EnableAction("gain".$i);
				
				$this->RegisterVariableinteger("bass".$i, "Bass ".$i, "ESERA.AMTone");
    			$this->EnableAction("bass".$i);
				
				$this->RegisterVariableinteger("mid".$i, "Middle ".$i, "ESERA.AMTone");
    			$this->EnableAction("mid".$i);
				
				$this->RegisterVariableinteger("treble".$i, "Treble ".$i, "ESERA.AMTone");
    			$this->EnableAction("treble".$i);

				$this->RegisterVariableinteger("balance".$i, "Balance ".$i, "ESERA.AMBalance");
    			$this->EnableAction("balance".$i);		
							
    			$this->RegisterVariableBoolean("amp".$i, "Amplifier on/off ".$i, "~switch");
    			$this->EnableAction("amp".$i);

    			$this->RegisterVariableBoolean("mute".$i, "Mute Output ".$i, "~switch");			
    			$this->EnableAction("mute".$i);	

    			$this->RegisterVariableInteger("input".$i, "Input ".$i, "ESERA.AMInput");
    			$this->EnableAction("input".$i);				
    		}

        $this->ConnectParent("{C73DD44F-BF0D-4180-A0F1-D296F68024B2}"); 			//AudioMax Interface
		
    }
    public function Destroy(){
        //Never delete this line!
        parent::Destroy();

    }

	
	public function ApplyChanges(){
     //Never delete this line!
     parent::ApplyChanges();

     $this->SetReceiveDataFilter(".*\"DeviceType\":\"AUDIO\".*");
	}
	
	
    public function ReceiveData($JSONString) {

        $data = json_decode($JSONString);
        $this->SendDebug("AudioMaxSystem", "| Room Number:" . $data->RoomNumber . "| Audio Type:" . $data->AudioType . "| Audio Value:" . $data->AudioValue, 0);
        
		$Number = intval($data->RoomNumber, 10); 		//Daten der übergebenen Variable
		//$Type = strval($data->AudioType,10); 		//Daten der übergebenen Variable
		$Type = $data->AudioType;
		$Value = intval($data->AudioValue,10);
				
	    $this->SendDebug("Room Number", $Number, 0);
		$this->SendDebug("Audio Type", $Type, 0);
		$this->SendDebug("Audio Value", $Value, 0);
		
  		
		if ($Type == "VOL"){
			SetValue($this->GetIDForIdent("volume".$Number), $Value);
			$this->SendDebug(("volume".$Number), $Value,0);
			}
		if ($Type == "INP"){
			SetValue($this->GetIDForIdent("input".$Number), $Value);
			$this->SendDebug(("input".$Number), $Value,0);
			}
		if ($Type == "GAI"){
			SetValue($this->GetIDForIdent("gain".$Number), $Value);
			$this->SendDebug(("gain".$Number), $Value,0);
			}			
		if ($Type == "BAS"){
			SetValue($this->GetIDForIdent("bass".$Number), $Value);
			$this->SendDebug(("bass".$Number), $Value,0);
			}
		if ($Type == "MID"){
			SetValue($this->GetIDForIdent("mid".$Number), $Value);
			$this->SendDebug(("mid".$Number), $Value,0);
			}
		if ($Type == "TRE"){
			SetValue($this->GetIDForIdent("treble".$Number), $Value);
			$this->SendDebug(("treble".$Number), $Value,0);
			}
		if ($Type == "BAL"){
			SetValue($this->GetIDForIdent("balance".$Number), $Value);
			$this->SendDebug(("balance".$Number), $Value,0);
			}
		if ($Type == "AMP"){
			SetValue($this->GetIDForIdent("amp".$Number), $Value);
			$this->SendDebug(("amp".$Number), $Value,0);
			}
		if ($Type == "MUT"){
			SetValue($this->GetIDForIdent("mute".$Number), $Value);
			$this->SendDebug(("mute".$Number), $Value,0);
			}
	
        
/*
		if ($data->DeviceNumber == 2){
           if ($data->DataPoint == 1){
             $value = intval($data->Value, 10);
              for ($i = 1; $i <= 2; $i++){
        	    SetValue($this->GetIDForIdent("Output".$i), ($value >> ($i-1)) & 0x01);
        	 }
           }
		}
		*/
		
    }

	public function RequestAction($Ident, $Value) {
		//$Number = 1;
		
		switch($Ident) {
			case "volume1":							//$Number = 1;
			case "volume2":
			case "volume3":
			case "volume4":
			case "volume5":
			case "volume6":
				$Type = "VOL";
				$Number = SubStr($Ident, 6, 1);
				break;				
			case "gain1":
			case "gain2":
			case "gain3":
			case "gain4":
			case "gain5":
			case "gain6":
				$Type = "GAI";
				$Number = SubStr($Ident, 4, 1);
				break;
			case "input1":
			case "input2":
			case "input3":
			case "input4":
			case "input5":
			case "input6":
				$Type = "INP";
				$Number = SubStr($Ident, 5, 1);
				break;				
			case "bass1":
			case "bass2":
			case "bass3":
			case "bass4":
			case "bass5":
			case "bass6":
				$Type = "BAS";
				$Number = SubStr($Ident, 4, 1);
				break;			
			case "mid1":
			case "mid2":
			case "mid3":
			case "mid4":
			case "mid5":
			case "mid6":
				$Type = "MID";
				$Number = SubStr($Ident, 3, 1);
				break;				
			case "treble1":
			case "treble2":
			case "treble3":
			case "treble4":
			case "treble5":
			case "treble6":
				$Type = "TRE";
				$Number = SubStr($Ident, 6, 1);
				break;				
			case "balance1":
			case "balance2":
			case "balance3":
			case "balance4":
			case "balance5":
			case "balance6":
				$Type = "BAL";
				$Number = SubStr($Ident, 7, 1);
				break;				
			case "amp1":
			case "amp2":
			case "amp3":
			case "amp4":
			case "amp5":
			case "amp6":
				$Type = "AMP";
				$Number = SubStr($Ident, 3, 1);
				break;				
			case "mute1":
			case "mute2":
			case "mute3":
			case "mute4":
			case "mute5":
			case "mute6":
				$Type = "MUT";
				$Number = SubStr($Ident, 4, 1);
				break;
		}
		
		$this->SendDebug(($Type ." ". $Number), $Value,0);
		$this->SetAudioSettingAM($Number, $Type, $Value);
	}



    public function SetAudioSettingAM(int $Number, int $Type , int $Value) {
  		$this->Send("SET,AUDIO,". $Number .",". $Type . ",". $Value ."");
  	}
	


    //private function CreateVariableProfile($ProfileName, $ProfileType, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Icon,$Wert,$Name,$Color) {
    private function CreateVariableProfile($ProfileName, $ProfileType, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Icon) {
		    if (!IPS_VariableProfileExists($ProfileName)) {
			       IPS_CreateVariableProfile($ProfileName, $ProfileType);
			       IPS_SetVariableProfileText($ProfileName, "", $Suffix);
			       IPS_SetVariableProfileValues($ProfileName, $MinValue, $MaxValue, $StepSize);
			       IPS_SetVariableProfileDigits($ProfileName, $Digits);
			       IPS_SetVariableProfileIcon($ProfileName, $Icon);
				   //IPS_SetVariableProfileAssociation($ProfileName, $Wert,$Name,$Icon ,$color);
				   
		    }
	  }

    private function Send($Command) {

      //Zur 1Wire Controller Instanz senden
    	return $this->SendDataToParent(json_encode(Array("DataID" => "{EA53E045-B4EF-4035-B0CD-699B8731F193}", "Command" => $Command . chr(13))));

    }
}
?>
