<?php 
namespace App\Libraries;

// class AppContainer
// {
// 	public function run($url, $quality, $format)
// 	{
// 		$file = new File($url, $quality, $format);
// 		$file->setFileName("test_0_17");
// 		$fileManager 					= new FileManager();
// 		$file->attachFileManager($fileManager);

// 		$executer 						= new Executer();
// 		$executer->debugger = true;
		
// 		$fileSettingsRepository 		= new FileSettingsRepository();

// 		$fileSettingsFactory 			= new FileSettingsFactory();
// 		$extractor 						= new VideoInformationExtractor();
// 		$dataset = 'YTozNDp7aTo1O2E6ODp7aTowO2k6NTtpOjE7czozOiJGTFYiO2k6MjtzOjQ6IjI0MHAiO2k6MztzOjE0OiJTb3JlbnNvbiBILjI2MyI7aTo0O3M6MzoiTi9BIjtpOjU7czo0OiIwLjI1IjtpOjY7czozOiJNUDMiO2k6NztzOjI6IjY0Ijt9aTo2O2E6ODp7aTowO2k6NjtpOjE7czozOiJGTFYiO2k6MjtzOjQ6IjI3MHAiO2k6MztzOjE0OiJTb3JlbnNvbiBILjI2MyI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIwLjgiO2k6NjtzOjM6Ik1QMyI7aTo3O3M6MjoiNjQiO31pOjEzO2E6ODp7aTowO2k6MTM7aToxO3M6MzoiM0dQIjtpOjI7czozOiJOL0EiO2k6MztzOjEzOiJNUEVHLTQgVmlzdWFsIjtpOjQ7czozOiJOL0EiO2k6NTtzOjM6IjAuNSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiJOL0EiO31pOjE3O2E6ODp7aTowO2k6MTc7aToxO3M6MzoiM0dQIjtpOjI7czo0OiIxNDRwIjtpOjM7czoxMzoiTVBFRy00IFZpc3VhbCI7aTo0O3M6NjoiU2ltcGxlIjtpOjU7czo0OiIwLjA1IjtpOjY7czozOiJBQUMiO2k6NztzOjI6IjI0Ijt9aToxODthOjg6e2k6MDtpOjE4O2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMzYwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjg6IkJhc2VsaW5lIjtpOjU7czozOiIwLjUiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MjoiOTYiO31pOjIyO2E6ODp7aTowO2k6MjI7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6NToiMi0yLjkiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTkyIjt9aTozNDthOjg6e2k6MDtpOjM0O2k6MTtzOjM6IkZMViI7aToyO3M6NDoiMzYwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjQ6Ik1haW4iO2k6NTtzOjM6IjAuNSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiIxMjgiO31pOjM1O2E6ODp7aTowO2k6MzU7aToxO3M6MzoiRkxWIjtpOjI7czo0OiI0ODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiTWFpbiI7aTo1O3M6NToiMC44LTEiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTI4Ijt9aTozNjthOjg6e2k6MDtpOjM2O2k6MTtzOjM6IjNHUCI7aToyO3M6NDoiMjQwcCI7aTozO3M6MTM6Ik1QRUctNCBWaXN1YWwiO2k6NDtzOjY6IlNpbXBsZSI7aTo1O3M6NDoiMC4xNyI7aTo2O3M6MzoiQUFDIjtpOjc7czoyOiIzOCI7fWk6Mzc7YTo4OntpOjA7aTozNztpOjE7czozOiJNUDQiO2k6MjtzOjU6IjEwODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6NzoiM+KAkzUuOSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiIxOTIiO31pOjM4O2E6ODp7aTowO2k6Mzg7aToxO3M6MzoiTVA0IjtpOjI7czo1OiIzMDcycCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjQ6IkhpZ2giO2k6NTtzOjU6IjMuNS01IjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjE5MiI7fWk6NDM7YTo4OntpOjA7aTo0MztpOjE7czo0OiJXZWJNIjtpOjI7czo0OiIzNjBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiMC41IjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6NDQ7YTo4OntpOjA7aTo0NDtpOjE7czo0OiJXZWJNIjtpOjI7czo0OiI0ODBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MToiMSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxMjgiO31pOjQ1O2E6ODp7aTowO2k6NDU7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiNzIwcCI7aTozO3M6MzoiVlA4IjtpOjQ7czozOiJOL0EiO2k6NTtzOjE6IjIiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTkyIjt9aTo0NjthOjg6e2k6MDtpOjQ2O2k6MTtzOjQ6IldlYk0iO2k6MjtzOjU6IjEwODBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjE5MiI7fWk6ODI7YTo4OntpOjA7aTo4MjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiMC41IjtpOjY7czozOiJBQUMiO2k6NztzOjI6Ijk2Ijt9aTo4MzthOjg6e2k6MDtpOjgzO2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMjQwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjI6IjNEIjtpOjU7czozOiIwLjUiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MjoiOTYiO31pOjg0O2E6ODp7aTowO2k6ODQ7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MjoiM0QiO2k6NTtzOjU6IjItMi45IjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjE1MiI7fWk6ODU7YTo4OntpOjA7aTo4NTtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjUyMHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6NToiMi0yLjkiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTUyIjt9aToxMDA7YTo4OntpOjA7aToxMDA7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiMzYwcCI7aTozO3M6MzoiVlA4IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6MTAxO2E6ODp7aTowO2k6MTAxO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjM6IlZQOCI7aTo0O3M6MjoiM0QiO2k6NTtzOjM6Ik4vQSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxOTIiO31pOjEwMjthOjg6e2k6MDtpOjEwMjtpOjE7czo0OiJXZWJNIjtpOjI7czo0OiI3MjBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjI6IjNEIjtpOjU7czozOiJOL0EiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTkyIjt9aToxMjA7YTo4OntpOjA7aToxMjA7aToxO3M6MzoiRkxWIjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6OToiTWFpbkBMMy4xIjtpOjU7czoxOiIyIjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjEyOCI7fWk6MTMzO2E6ODp7aTowO2k6MTMzO2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMjQwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6NzoiMC4yLTAuMyI7aTo2O3M6NjoiTi9BWzRdIjtpOjc7czozOiJOL0EiO31pOjEzNDthOjg6e2k6MDtpOjEzNDtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjc6IjAuMy0wLjQiO2k6NjtzOjY6Ik4vQVs0XSI7aTo3O3M6MzoiTi9BIjt9aToxMzU7YTo4OntpOjA7aToxMzU7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI0ODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MzoiTi9BIjtpOjU7czo1OiIwLjUtMSI7aTo2O3M6NjoiTi9BWzRdIjtpOjc7czozOiJOL0EiO31pOjEzNjthOjg6e2k6MDtpOjEzNjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjcyMHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjU6IjEtMS41IjtpOjY7czo2OiJOL0FbNF0iO2k6NztzOjM6Ik4vQSI7fWk6MTM3O2E6ODp7aTowO2k6MTM3O2k6MTtzOjM6Ik1QNCI7aToyO3M6NToiMTA4MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjU6IjItMi45IjtpOjY7czo2OiJOL0FbNF0iO2k6NztzOjM6Ik4vQSI7fWk6MTM5O2E6ODp7aTowO2k6MTM5O2k6MTtzOjM6Ik1QNCI7aToyO3M6MzoiTi9BIjtpOjM7czo2OiJOL0FbNF0iO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czozOiJBQUMiO2k6NztzOjI6IjQ4Ijt9aToxNDA7YTo4OntpOjA7aToxNDA7aToxO3M6MzoiTVA0IjtpOjI7czozOiJOL0EiO2k6MztzOjY6Ik4vQVs0XSI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiJOL0EiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTI4Ijt9aToxNDE7YTo4OntpOjA7aToxNDE7aToxO3M6MzoiTVA0IjtpOjI7czozOiJOL0EiO2k6MztzOjY6Ik4vQVs0XSI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiJOL0EiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMjU2Ijt9aToxNjA7YTo4OntpOjA7aToxNjA7aToxO3M6MzoiTVA0IjtpOjI7czo0OiIxNDRwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIwLjEiO2k6NjtzOjY6Ik4vQVs0XSI7aTo3O3M6MzoiTi9BIjt9aToxNzE7YTo4OntpOjA7aToxNzE7aToxO3M6NDoiV2ViTSI7aToyO3M6MzoiTi9BIjtpOjM7czo2OiJOL0FbNF0iO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6MTcyO2E6ODp7aTowO2k6MTcyO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjM6Ik4vQSI7aTozO3M6NjoiTi9BWzRdIjtpOjQ7czozOiJOL0EiO2k6NTtzOjM6Ik4vQSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxOTIiO319';

// 		$extractor->setDataset(unserialize(base64_decode($dataset)));
// 		$persistence 					= new InMemoryPersistence();

// 		$fileSettingsRepository->attachFactory($fileSettingsFactory);
// 		$fileSettingsRepository->attachExtractor($extractor);
// 		$fileSettingsRepository->attachPersistenceGateway($persistence);
 		
//  		$downloader = new Downloader($file);
// 		$downloader->attachExecuter($executer);
// 		//$downloader->attachFileManager($fileManager);
// 		$downloader->attachFileSettingsRepository($fileSettingsRepository);

// 		$downloader->download();
// 	}
// }


/**
 * IOC Container
 * Author: Bas van Vliet
 * Company: &samhoud
 * Date: 15-01-2014
 */

class AppContainer{
   /**
    * @var instance registry
    */
   protected static $registry = array();
 
   /**
    * Add a new resolver to the registry array.
    * @param  string $name The id
    * @param  object $resolve Closure that creates instance
    * @return void
    */
   public static function register($name, \Closure $resolve)
   {
      static::$registry[$name] = $resolve;
   }
 
   /**
    * Create the instance
    * @param  string $name The id
    * @return mixed
    */
   public static function resolve($name)
   {
      if ( static::registered($name) )
      {
         $name = static::$registry[$name];
         return $name();
      }
 
      throw new Exception('Nothing registered with that name');
   }
 
   /**
    * Determine whether the id is registered
    * @param  string $name The id
    * @return bool Whether the id exists or not
    */
   public static function registered($name)
   {
      return array_key_exists($name, static::$registry);
   }
}