<?php
	class Test {
		//properties ehk muutujad
		private $secretNum = 3;
		public $number = 9;
		
		function __construct(){
			echo "Laeti klass!";
			echo "Salajane number on: " .$this->secretNum;
			echo "Avalik number on: " .$this->number;
			$this->reveal();
		}//konstruktor lõppeb
		
		function __destruct(){
			echo "Klass lõpetab!!!";
		}
		
		public function reveal(){
			echo "Salajane number on: " .$this->secretNum;
		}
		
	}//class lõppeb