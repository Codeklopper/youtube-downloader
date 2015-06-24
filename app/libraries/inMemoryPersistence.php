<?php namespace App\Libraries;
use App\Libraries\Persistence;

/**
 * Save information in memory. A temporary class, can be replaced by a database.
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
class InMemoryPersistence implements Persistence {
        /**
         * array with data
         * @var array
         */
        private $data = array();

        /**
         * Data to persist
         * @param  array $data
         * @return void
         */
        function persist($data) {
                $this->data[] = $data;
        }

        /** 
         * Get data based on id
         * @param  mixed $id
         * @return array array with persisted data
         */
        function retrieve($id) {
                return $this->data[$id];
        }

        /**
         * Get all persisted data
         * @return [type]
         */
        function retrieveAll() {
                return $this->data;
        }
}