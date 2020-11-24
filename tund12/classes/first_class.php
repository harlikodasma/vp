<?php
	class First {
		//muutujad on klassis omadused (properties)
		private $mybusiness;
		public $everybodysbusiness;
		
		function __construct($limit) {
			$this->mybusiness = mt_rand(0, $limit);
			$this->everybodysbusiness = mt_rand(0, 100);
			echo "Arvude korrutis on " .$this->mybusiness * $this->everybodysbusiness;
			$this->tellSecret();
		}
		
		function __destruct() {
			echo "Nägite hulka mõttetut infot.";
		}
		
		//funktsioonid on klassis meetodid (methods)
		public function tellMe() {
			echo "Salajane arv on " .$this->mybusiness;
		}
		
		private function tellSecret() {
			echo "Saladusi võib ka välja rääkida!";
		}
	} //class lõppeb
?>