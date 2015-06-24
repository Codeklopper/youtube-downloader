<?php namespace App\Libraries;

/**
 * File object factory. Creates File objects
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */
interface Factory
{	
	/**
	 * Create object with given arguments from $data
	 * @param  array $data array with arguments for object constructor
	 * @return Object
	 */
	public function make($data);
}