<?php

	/**
	*	Database model
	*
	*/
	abstract class Model {

		protected $columns = array();

		/**
		*	Save model to database
		*	Creates new database entry every time,
		*	even if id is already in database
		*
		*/
		public function save() {
			// initial values, ignoring $id
			$substringColumns = array_keys($this->columns)[1];
			$substringValues = "?";

			// add more columns if available
			foreach (array_slice($this->columns, 2) as $column => $value) {
				$substringColumns .= "`, `".$column;
				$substringValues .= ", ?";
			}

			$query = $GLOBALS["db"]->prepare("
				insert into `".get_called_class()."`
				(`".$substringColumns."`)
				values
				(".$substringValues.")
			");
			$query->execute(array_slice(array_values($this->columns), 1));
		}

		/**
		*	Update database entry based on model
		*	Prevents you from changing id
		*
		*/
		public function update() {
			// initial value, ignoring $id
			$substringUpdates = "`".array_keys($this->columns)[1]."` = ?";
			$bindings = array(
				array_values($this->columns)[1]
			);

			// add more columns if available
			foreach (array_slice($this->columns, 2) as $column => $value) {
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
		*	@param $id Delete entry with this primary key
		*
		*/
		public static function delete($id) {
			$query = $GLOBALS["db"]->prepare("
				delete from `".get_called_class()."`
				where `".get_called_class()."`.`id` = :id
			");
			$query->execute(array(
				":id" => $id
			));
		}

		/**
		*	Find a database entry
		*	@param $id Find entry based on this id
		*	@return New model with the fields from the found database entry
		*
		*/
		public static function findById($id) {
			$query = $GLOBALS["db"]->prepare("
				select * from `".get_called_class()."`
				where `".get_called_class()."`.`id` = :id
			");
			$query->execute(array(
				":id" => $id
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