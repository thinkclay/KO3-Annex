<?php defined('SYSPATH') or die('No direct script access.');

return [

	/**
	 * Configuration Name
	 *
	 * You use this name when initializing a new BrassDB instance
	 *
	 * $db = BrassDB::instance('default');
	 */
	'default' => [

		/**
		 * Connection Setup
		 *
		 * See http://www.php.net/manual/en/mongo.construct.php for more information
		 *
		 * or just edit / uncomment the keys below to your requirements
		 */
		'connection' => [

			/** hostnames, separate multiple hosts by commas **/
			'hostnames' => 'localhost:27017',

			/** connection options (see http://www.php.net/manual/en/mongo.construct.php) **/
			'options'   => [
				// database to connect to
				'db'         => 'example',

				// default timeout of 20 seconds is too long
				'connectTimeoutMS'    => 2000,

				// Connect to DB on creation - how do you want to deal with connection errors
				// TRUE : BrassDB::instance fails and an exception is thrown. Next call to BrassDB::instance will try to connect again
				// FALSE: Exception is thrown when you run first DB action. Next call to BrassDB::instance will return same object
				'connect'    => FALSE,

				// authentication
				//'username'  => 'username',
				//'password'  => 'password',

				// replication
				//'replicaSet' => 'someSet',
			]
		],

		/**
		 * Whether or not to use profiling
		 *
		 * If enabled, profiling data will be shown through Kohana's profiler library
		 */
		'profiling' => FALSE
	]
];