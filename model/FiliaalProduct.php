<?php


	/**
	*	FiliaalProduct model
	*	Used for interaction with database
	*
	*/
	class FiliaalProduct {

		private $columns = array(
			"filiaal_id" => NOT_SET,
			"product_id" => NOT_SET,
			"aantal" => NOT_SET,
			"minimumaantal" => NOT_SET
		);

		/**
		*	Save model to database
		*
		*/
		public function save() {
			// initial values
			$substringColumns = array_keys($this->columns)[0];
			$substringValues = "?";

			// add more columns if available
			foreach (array_slice($this->columns, 1) as $column => $value) {
				$substringColumns .= "`, `".$column;
				$substringValues .= ", ?";
			}

			$query = $GLOBALS["db"]->prepare("
				insert into `".get_called_class()."`
				(`".$substringColumns."`)
				values
				(".$substringValues.")
			");
			$query->execute(array_values($this->columns));
		}

		/**
		*	Update database entry based on model
		*
		*/
		public function update() {
			// initial value
			$substringUpdates = "`".array_keys($this->columns)[0]."` = ?";
			$bindings = array(
				array_values($this->columns)[0]
			);

			// add more columns if available
			foreach (array_slice($this->columns, 1) as $column => $value) {
				$substringUpdates .= ", `".$column."` = ?";
				array_push($bindings, $value);
			}

			array_push($bindings, $this->columns["id"]);
			$query = $GLOBALS["db"]->prepare("
				update `".get_called_class()."`
				set ".$substringUpdates."
				where `".get_called_class()."`.`id` = ?
			");
			$query->execute($bindings);
		}

		/**
		*	Delete database entry
		*	@param $filiaal_id Delete entry based on this foreign key
		*	@param $product_id Delete entry based on this foreign key
		*
		*/
		public static function delete($filiaal_id, $product_id) {
			$query = $GLOBALS["db"]->prepare("
				delete from `".get_called_class()."`
				where `".get_called_class()."`.`filiaal_id` = ?
				and `".get_called_class()."`.`product_id` = ?
			");
			$query->execute(array(
				$filiaal_id,
				$product_id
			));
		}

		/**
		*	Find a database entry
		*	@param $filiaal_id Find entry based on this foreign key
		*	@param $product_id Find entry based on this foreign key
		*	@return New model with the fields from the found database entry
		*
		*/
		public static function findByKeys($filiaal_id, $product_id) {
			$query = $GLOBALS["db"]->prepare("
				select * from `".get_called_class()."`
				where `".get_called_class()."`.`filiaal_id` = ?
				and `".get_called_class()."`.`product_id` = ?
			");
			$query->execute(array(
				$filiaal_id,
				$product_id
			));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			$model = new static;
			foreach ($result as $column => $value) {
				$model->setAttribute($column, $value);
			}
			return $model;
		}

		/**
		*	Find all database entries for this table
		*	@return 2-dimensional array of all database entries for this table
		*
		*/
		public static function findAll() {
			$query = $GLOBALS["db"]->query("
				select * from `".get_called_class()."`
			");
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}



		/*###########################################################*/
		/* ~~~ ~~~ ~~~ ~~~ ~~~ GETTERS & SETTERS ~~~ ~~~ ~~~ ~~~ ~~~ */
		/*###########################################################*/

		/**
		*	Get any column
		*	@param $column Get this column
		*	@return Value of column
		*
		*/
		public function getAttribute($column) {
			return $this->columns[$column];
		}

		/**
		*	Set any column
		*	@param $column Set this column
		*	@param $value Set it to this value
		*
		*/
		public function setAttribute($column, $value) {
			$this->columns[$column] = $value;
		}

	}

?>