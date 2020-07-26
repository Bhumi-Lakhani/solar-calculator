<?php 

class Calculator
{
	public $location; 
	public $billUnits;  
	public $billRs; 
	
	// public $location = 'residential';
	// public $billUnits = 400;
	// public $billRs = 500;
	

	public $consumerType = 0;
	public $irradiance = 3.8;
	public $monUnitsGen = 0;
	public $yearUnitsGen = 0;
	public $paybackPeriod = 0;
	public $sysCost = 0;
	public $systemCap = 0;
	public $yearlySavings = 0;
	public $unitRateElec = 0;
	public $powerPlantRate = 0;
	
	public $spaceReq = 0;

	function calculate()
	{
		$this->location = $_POST["location"];

		if (isset($_POST['monthlyUnits']) && $_POST['monthlyUnits'] != '') 
		{
			$this->billUnits = $_POST["monthlyUnits"];
			$_POST["monthlyBill"] = '';
		}
		else
		{
			$this->billRs = $_POST["monthlyBill"];
			$_POST["monthlyUnits"] = '';
		}
		
		switch ($this->location) 
		{
			case 'residential':
			$this->unitRateElec = 7;
			break;
			case 'industrial':
			$this->unitRateElec = 7.5;
			break;
			case 'commercial':

			case 'educational':

			case 'hospital':

			case 'corporate':
			$this->unitRateElec = 8;
			break;
		}
		if($this->billUnits == '')
		{	
			$this->billUnits = $this->billRs / 7;
		}
		$this->systemCap =$this->billUnits / 30;
		$this->systemCap =round($this->systemCap  / 4,2);
		$this->spaceReq = ceil($this->systemCap * 100);
		
		$this->monUnitsGen = ceil($this->systemCap * $this->irradiance * 30);
		$this->yearUnitsGen = ceil($this->systemCap * $this->irradiance * 365);
		$this->yearlySavings = $this->yearUnitsGen * $this->unitRateElec;
		$this->powerPlantRate = $this->getPowerPlantRate($this->location);
		$this->sysCost = $this->systemCap * $this->powerPlantRate;
		$this->paybackPeriod = round($this->sysCost / $this->yearlySavings,1);

		$return_arr = array('systemCap'=>$this->systemCap,'spaceReq'=>$this->spaceReq,'sysCost'=>$this->sysCost,'monUnitsGen'=>$this->monUnitsGen,'yearUnitsGen'=>$this->yearUnitsGen,'yearlySavings'=>$this->yearlySavings,'paybackPeriod'=>$this->paybackPeriod);

		echo json_encode($return_arr);
		
		return $return_arr;
		// return $this->$location;
	}

	function getPowerPlantRate($location)
	{
		if($location == "residential")
		{
			if($this->systemCap >= 1 && $this->systemCap <= 5)
				$this->powerPlantRate=47000;
			else if($this->systemCap >= 6 && $this->systemCap <= 8)
				$this->powerPlantRate=45000;
			else if($this->systemCap >= 9 && $this->systemCap <= 10)
				$this->powerPlantRate=42000;
			else if($this->systemCap >= 11 && $this->systemCap <= 20)
				$this->powerPlantRate=41000;
			else if($this->systemCap >= 21 && $this->systemCap <= 50)
				$this->powerPlantRate=40000;
			else if($this->systemCap >= 51 && $this->systemCap <= 100)
				$this->powerPlantRate=38000;
			return $this->powerPlantRate;
		}		
		else
		{
			if($this->systemCap >= 1 && $this->systemCap <= 5)
				$this->powerPlantRate=48000;
			else if($this->systemCap >= 6 && $this->systemCap <= 10)
				$this->powerPlantRate=45000;
			else if($this->systemCap >= 11 && $this->systemCap <= 25)
				$this->powerPlantRate=43000;
			else if($this->systemCap >= 26 && $this->systemCap <= 50)
				$this->powerPlantRate=40000;
			else if($this->systemCap >= 51 && $this->systemCap <= 100)
				$this->powerPlantRate=38000;
			else if($this->systemCap >= 101 && $this->systemCap <= 200)
				$this->powerPlantRate=32000;
			else if($this->systemCap >= 201 && $this->systemCap <= 500)
				$this->powerPlantRate=31000;
			else if($this->systemCap >= 501 && $this->systemCap <= 1000)
				$this->powerPlantRate=30000;			
			return $this->powerPlantRate;
		}	
	}
	

}
$obj=new Calculator();
$obj->calculate();

?>