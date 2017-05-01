<?php

	/**
	*	Gebruiker model
	*	Used for interaction with database
	*
	*/
	class Gebruiker extends Model {

		protected $columns = array(
			"id" => NOT_SET,
			"filiaal_id" => null,
			"rol_id" => NOT_SET,
			"email" => "",
			"wachtwoord" => ""
		);

	}

?>