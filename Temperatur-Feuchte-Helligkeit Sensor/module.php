<?
class EseraTemperaturFeuchteHelligkeit extends IPSModule {

    public function Create(){
        //Never delete this line!
        parent::Create();

        //These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
        $this->CreateVariableProfile("ESERA.Temperatur", 2, " °C", -30, 150, 0, 2, "Temperature");
        $this->CreateVariableProfile("ESERA.Luftfeuchte", 2, " %", 0, 100, 0, 2, "Gauge");
        $this->CreateVariableProfile("ESERA.Helligkeit", 2, " lx", 0, 7000, 0, 2, "Sun");
        $this->CreateVariableProfile("ESERA.SpannungV", 2, " V", 0, 15, 0, 2, "");

        $this->RegisterPropertyInteger("OWDID", 1);

        $this->RegisterVariableFloat("Temperatur", "Temperatur", "ESERA.Temperatur", 1);
        $this->RegisterVariableFloat("Spannung", "Spannung", "ESERA.SpannungV", 2);
        $this->RegisterVariableFloat("Luftfeuchte", "Luftfeuchte", "ESERA.Luftfeuchte", 3);
        $this->RegisterVariableFloat("Taupunkt", "Taupunkt", "ESERA.Temperatur", 4);
        $this->RegisterVariableFloat("Helligkeit", "Helligkeit", "ESERA.Helligkeit", 5);

        $this->ConnectParent("{FCABCDA7-3A57-657D-95FD-9324738A77B9}"); //1Wire Controller
    }
    public function Destroy(){
        //Never delete this line!
        parent::Destroy();

    }
    public function ApplyChanges(){
        //Never delete this line!
        parent::ApplyChanges();

        //Apply filter
        $this->SetReceiveDataFilter(".*\"DeviceNumber\":". $this->ReadPropertyInteger("OWDID") .".*");

    }
    public function ReceiveData($JSONString) {

        $data = json_decode($JSONString);
        $this->SendDebug("ESERA-Temperatur-Feuchte-Helligkeit", "DataPoint:" . $data->DataPoint . " | Value: " . $data->Value, 0);

        if ($this->ReadPropertyInteger("OWDID") == $data->DeviceNumber) {
            if ($data->DataPoint == 1) {
                $value = $data->Value / 100;
                SetValue($this->GetIDForIdent("Temperatur"), $value);
            }

            if ($data->DataPoint == 2) {
                $value = $data->Value / 100;
                SetValue($this->GetIDForIdent("Spannung"), $value);
            }

            if ($data->DataPoint == 3) {
                $value = $data->Value / 100;
                SetValue($this->GetIDForIdent("Luftfeuchte"), $value);
            }

            if ($data->DataPoint == 4) {
                $value = $data->Value / 100;
                SetValue($this->GetIDForIdent("Taupunkt"), $value);
            }

            if ($data->DataPoint == 5) {
                $value = $data->Value / 100;
                SetValue($this->GetIDForIdent("Helligkeit"), $value);
            }
        }
    }
    private function CreateVariableProfile($ProfileName, $ProfileType, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Icon) {
		    if (!IPS_VariableProfileExists($ProfileName)) {
			       IPS_CreateVariableProfile($ProfileName, $ProfileType);
			       IPS_SetVariableProfileText($ProfileName, "", $Suffix);
			       IPS_SetVariableProfileValues($ProfileName, $MinValue, $MaxValue, $StepSize);
			       IPS_SetVariableProfileDigits($ProfileName, $Digits);
			       IPS_SetVariableProfileIcon($ProfileName, $Icon);
		    }
	  }
}
?>