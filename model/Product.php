<?php

	/**
	*	Product model
	*	Used for interaction with database
	*
	*/
	class Product extends Model {

		protected $columns = array(
			"id" => NOT_SET,
			"fabriek_id" => NOT_SET,
			"naam" => "",
			"type" => "",
			"inkoopprijs" => NOT_SET,
			"verkoopprijs" => NOT_SET
		);

	}

?>