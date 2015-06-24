<?php
require 'vendor/autoload.php';
use App\Libraries\Downloader;
use App\Libraries\FileSettingsFactory;
use App\Libraries\FileFactory;
use App\Libraries\AppContainer;
use App\Libraries\Executer;
use App\Libraries\FileSettingsRepository;
use App\Libraries\VideoInformationExtractor;
use App\Libraries\InMemoryPersistence;
AppContainer::register('Executer', function() {
   $Executer = new Executer; 
   $Executer->debug = true;
   return $Executer;
});

AppContainer::register('FileSettingsFactory', function() {
   $FileSettingsFactory = new FileSettingsFactory; 
   return $FileSettingsFactory;
});

AppContainer::register('FileFactory', function() {
   $FileFactory = new FileFactory; 
   return $FileFactory;
});
AppContainer::register('VideoInformationExtractor', function() {
   $VideoInformationExtractor = new VideoInformationExtractor; 
   //$dataset = 'YTozNDp7aTo1O2E6ODp7aTowO2k6NTtpOjE7czozOiJGTFYiO2k6MjtzOjQ6IjI0MHAiO2k6MztzOjE0OiJTb3JlbnNvbiBILjI2MyI7aTo0O3M6MzoiTi9BIjtpOjU7czo0OiIwLjI1IjtpOjY7czozOiJNUDMiO2k6NztzOjI6IjY0Ijt9aTo2O2E6ODp7aTowO2k6NjtpOjE7czozOiJGTFYiO2k6MjtzOjQ6IjI3MHAiO2k6MztzOjE0OiJTb3JlbnNvbiBILjI2MyI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIwLjgiO2k6NjtzOjM6Ik1QMyI7aTo3O3M6MjoiNjQiO31pOjEzO2E6ODp7aTowO2k6MTM7aToxO3M6MzoiM0dQIjtpOjI7czozOiJOL0EiO2k6MztzOjEzOiJNUEVHLTQgVmlzdWFsIjtpOjQ7czozOiJOL0EiO2k6NTtzOjM6IjAuNSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiJOL0EiO31pOjE3O2E6ODp7aTowO2k6MTc7aToxO3M6MzoiM0dQIjtpOjI7czo0OiIxNDRwIjtpOjM7czoxMzoiTVBFRy00IFZpc3VhbCI7aTo0O3M6NjoiU2ltcGxlIjtpOjU7czo0OiIwLjA1IjtpOjY7czozOiJBQUMiO2k6NztzOjI6IjI0Ijt9aToxODthOjg6e2k6MDtpOjE4O2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMzYwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjg6IkJhc2VsaW5lIjtpOjU7czozOiIwLjUiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MjoiOTYiO31pOjIyO2E6ODp7aTowO2k6MjI7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6NToiMi0yLjkiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTkyIjt9aTozNDthOjg6e2k6MDtpOjM0O2k6MTtzOjM6IkZMViI7aToyO3M6NDoiMzYwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjQ6Ik1haW4iO2k6NTtzOjM6IjAuNSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiIxMjgiO31pOjM1O2E6ODp7aTowO2k6MzU7aToxO3M6MzoiRkxWIjtpOjI7czo0OiI0ODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiTWFpbiI7aTo1O3M6NToiMC44LTEiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTI4Ijt9aTozNjthOjg6e2k6MDtpOjM2O2k6MTtzOjM6IjNHUCI7aToyO3M6NDoiMjQwcCI7aTozO3M6MTM6Ik1QRUctNCBWaXN1YWwiO2k6NDtzOjY6IlNpbXBsZSI7aTo1O3M6NDoiMC4xNyI7aTo2O3M6MzoiQUFDIjtpOjc7czoyOiIzOCI7fWk6Mzc7YTo4OntpOjA7aTozNztpOjE7czozOiJNUDQiO2k6MjtzOjU6IjEwODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6NzoiM+KAkzUuOSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiIxOTIiO31pOjM4O2E6ODp7aTowO2k6Mzg7aToxO3M6MzoiTVA0IjtpOjI7czo1OiIzMDcycCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjQ6IkhpZ2giO2k6NTtzOjU6IjMuNS01IjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjE5MiI7fWk6NDM7YTo4OntpOjA7aTo0MztpOjE7czo0OiJXZWJNIjtpOjI7czo0OiIzNjBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiMC41IjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6NDQ7YTo4OntpOjA7aTo0NDtpOjE7czo0OiJXZWJNIjtpOjI7czo0OiI0ODBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MToiMSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxMjgiO31pOjQ1O2E6ODp7aTowO2k6NDU7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiNzIwcCI7aTozO3M6MzoiVlA4IjtpOjQ7czozOiJOL0EiO2k6NTtzOjE6IjIiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTkyIjt9aTo0NjthOjg6e2k6MDtpOjQ2O2k6MTtzOjQ6IldlYk0iO2k6MjtzOjU6IjEwODBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjE5MiI7fWk6ODI7YTo4OntpOjA7aTo4MjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiMC41IjtpOjY7czozOiJBQUMiO2k6NztzOjI6Ijk2Ijt9aTo4MzthOjg6e2k6MDtpOjgzO2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMjQwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjI6IjNEIjtpOjU7czozOiIwLjUiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MjoiOTYiO31pOjg0O2E6ODp7aTowO2k6ODQ7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MjoiM0QiO2k6NTtzOjU6IjItMi45IjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjE1MiI7fWk6ODU7YTo4OntpOjA7aTo4NTtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjUyMHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6NToiMi0yLjkiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTUyIjt9aToxMDA7YTo4OntpOjA7aToxMDA7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiMzYwcCI7aTozO3M6MzoiVlA4IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6MTAxO2E6ODp7aTowO2k6MTAxO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjM6IlZQOCI7aTo0O3M6MjoiM0QiO2k6NTtzOjM6Ik4vQSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxOTIiO31pOjEwMjthOjg6e2k6MDtpOjEwMjtpOjE7czo0OiJXZWJNIjtpOjI7czo0OiI3MjBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjI6IjNEIjtpOjU7czozOiJOL0EiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTkyIjt9aToxMjA7YTo4OntpOjA7aToxMjA7aToxO3M6MzoiRkxWIjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6OToiTWFpbkBMMy4xIjtpOjU7czoxOiIyIjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjEyOCI7fWk6MTMzO2E6ODp7aTowO2k6MTMzO2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMjQwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6NzoiMC4yLTAuMyI7aTo2O3M6NjoiTi9BWzRdIjtpOjc7czozOiJOL0EiO31pOjEzNDthOjg6e2k6MDtpOjEzNDtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjc6IjAuMy0wLjQiO2k6NjtzOjY6Ik4vQVs0XSI7aTo3O3M6MzoiTi9BIjt9aToxMzU7YTo4OntpOjA7aToxMzU7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI0ODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MzoiTi9BIjtpOjU7czo1OiIwLjUtMSI7aTo2O3M6NjoiTi9BWzRdIjtpOjc7czozOiJOL0EiO31pOjEzNjthOjg6e2k6MDtpOjEzNjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjcyMHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjU6IjEtMS41IjtpOjY7czo2OiJOL0FbNF0iO2k6NztzOjM6Ik4vQSI7fWk6MTM3O2E6ODp7aTowO2k6MTM3O2k6MTtzOjM6Ik1QNCI7aToyO3M6NToiMTA4MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czozOiJOL0EiO2k6NTtzOjU6IjItMi45IjtpOjY7czo2OiJOL0FbNF0iO2k6NztzOjM6Ik4vQSI7fWk6MTM5O2E6ODp7aTowO2k6MTM5O2k6MTtzOjM6Ik1QNCI7aToyO3M6MzoiTi9BIjtpOjM7czo2OiJOL0FbNF0iO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czozOiJBQUMiO2k6NztzOjI6IjQ4Ijt9aToxNDA7YTo4OntpOjA7aToxNDA7aToxO3M6MzoiTVA0IjtpOjI7czozOiJOL0EiO2k6MztzOjY6Ik4vQVs0XSI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiJOL0EiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTI4Ijt9aToxNDE7YTo4OntpOjA7aToxNDE7aToxO3M6MzoiTVA0IjtpOjI7czozOiJOL0EiO2k6MztzOjY6Ik4vQVs0XSI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiJOL0EiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMjU2Ijt9aToxNjA7YTo4OntpOjA7aToxNjA7aToxO3M6MzoiTVA0IjtpOjI7czo0OiIxNDRwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIwLjEiO2k6NjtzOjY6Ik4vQVs0XSI7aTo3O3M6MzoiTi9BIjt9aToxNzE7YTo4OntpOjA7aToxNzE7aToxO3M6NDoiV2ViTSI7aToyO3M6MzoiTi9BIjtpOjM7czo2OiJOL0FbNF0iO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjEyOCI7fWk6MTcyO2E6ODp7aTowO2k6MTcyO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjM6Ik4vQSI7aTozO3M6NjoiTi9BWzRdIjtpOjQ7czozOiJOL0EiO2k6NTtzOjM6Ik4vQSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxOTIiO319';
   $dataset = 'YTo0Mjp7aTo1O2E6ODp7aTowO3M6MToiNSI7aToxO3M6MzoiRkxWIjtpOjI7czo0OiIyNDBwIjtpOjM7czoxNDoiU29yZW5zb24gSC4yNjMiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6NDoiMC4yNSI7aTo2O3M6MzoiTVAzIjtpOjc7czoyOiI2NCI7fWk6NjthOjg6e2k6MDtzOjE6IjYiO2k6MTtzOjM6IkZMViI7aToyO3M6NDoiMjcwcCI7aTozO3M6MTQ6IlNvcmVuc29uIEguMjYzIjtpOjQ7czozOiJOL0EiO2k6NTtzOjM6IjAuOCI7aTo2O3M6MzoiTVAzIjtpOjc7czoyOiI2NCI7fWk6MTM7YTo4OntpOjA7czoyOiIxMyI7aToxO3M6MzoiM0dQIjtpOjI7czozOiJOL0EiO2k6MztzOjEzOiJNUEVHLTQgVmlzdWFsIjtpOjQ7czozOiJOL0EiO2k6NTtzOjM6IjAuNSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiJOL0EiO31pOjE3O2E6ODp7aTowO3M6MjoiMTciO2k6MTtzOjM6IjNHUCI7aToyO3M6NDoiMTQ0cCI7aTozO3M6MTM6Ik1QRUctNCBWaXN1YWwiO2k6NDtzOjY6IlNpbXBsZSI7aTo1O3M6NDoiMC4wNSI7aTo2O3M6MzoiQUFDIjtpOjc7czoyOiIyNCI7fWk6MTg7YTo4OntpOjA7czoyOiIxOCI7aToxO3M6MzoiTVA0IjtpOjI7czo0OiIzNjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6ODoiQmFzZWxpbmUiO2k6NTtzOjM6IjAuNSI7aTo2O3M6MzoiQUFDIjtpOjc7czoyOiI5NiI7fWk6MjI7YTo4OntpOjA7czoyOiIyMiI7aToxO3M6MzoiTVA0IjtpOjI7czo0OiI3MjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6MzoiMi0zIjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjE5MiI7fWk6MzQ7YTo4OntpOjA7czoyOiIzNCI7aToxO3M6MzoiRkxWIjtpOjI7czo0OiIzNjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiTWFpbiI7aTo1O3M6MzoiMC41IjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjEyOCI7fWk6MzU7YTo4OntpOjA7czoyOiIzNSI7aToxO3M6MzoiRkxWIjtpOjI7czo0OiI0ODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiTWFpbiI7aTo1O3M6NToiMC44LTEiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTI4Ijt9aTozNjthOjg6e2k6MDtzOjI6IjM2IjtpOjE7czozOiIzR1AiO2k6MjtzOjQ6IjI0MHAiO2k6MztzOjEzOiJNUEVHLTQgVmlzdWFsIjtpOjQ7czo2OiJTaW1wbGUiO2k6NTtzOjU6IjAuMTc1IjtpOjY7czozOiJBQUMiO2k6NztzOjI6IjM2Ijt9aTozNzthOjg6e2k6MDtzOjI6IjM3IjtpOjE7czozOiJNUDQiO2k6MjtzOjU6IjEwODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6NzoiM+KAkzUuOSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiIxOTIiO31pOjM4O2E6ODp7aTowO3M6MjoiMzgiO2k6MTtzOjM6Ik1QNCI7aToyO3M6NToiMzA3MnAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czo0OiJIaWdoIjtpOjU7czo1OiIzLjUtNSI7aTo2O3M6MzoiQUFDIjtpOjc7czozOiIxOTIiO31pOjQzO2E6ODp7aTowO3M6MjoiNDMiO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjM6IlZQOCI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIwLjUiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTI4Ijt9aTo0NDthOjg6e2k6MDtzOjI6IjQ0IjtpOjE7czo0OiJXZWJNIjtpOjI7czo0OiI0ODBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjM6Ik4vQSI7aTo1O3M6MToiMSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxMjgiO31pOjQ1O2E6ODp7aTowO3M6MjoiNDUiO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjcyMHAiO2k6MztzOjM6IlZQOCI7aTo0O3M6MzoiTi9BIjtpOjU7czoxOiIyIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjE5MiI7fWk6NDY7YTo4OntpOjA7czoyOiI0NiI7aToxO3M6NDoiV2ViTSI7aToyO3M6NToiMTA4MHAiO2k6MztzOjM6IlZQOCI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiJOL0EiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTkyIjt9aTo4MjthOjg6e2k6MDtzOjI6IjgyIjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiMC41IjtpOjY7czozOiJBQUMiO2k6NztzOjI6Ijk2Ijt9aTo4MzthOjg6e2k6MDtzOjI6IjgzIjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjI0MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiMC41IjtpOjY7czozOiJBQUMiO2k6NztzOjI6Ijk2Ijt9aTo4NDthOjg6e2k6MDtzOjI6Ijg0IjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjcyMHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiMi0zIjtpOjY7czozOiJBQUMiO2k6NztzOjM6IjE5MiI7fWk6ODU7YTo4OntpOjA7czoyOiI4NSI7aToxO3M6MzoiTVA0IjtpOjI7czo1OiIxMDgwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjI6IjNEIjtpOjU7czozOiIzLTQiO2k6NjtzOjM6IkFBQyI7aTo3O3M6MzoiMTkyIjt9aToxMDA7YTo4OntpOjA7czozOiIxMDAiO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjM6IlZQOCI7aTo0O3M6MjoiM0QiO2k6NTtzOjM6Ik4vQSI7aTo2O3M6NjoiVm9yYmlzIjtpOjc7czozOiIxMjgiO31pOjEwMTthOjg6e2k6MDtzOjM6IjEwMSI7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiMzYwcCI7aTozO3M6MzoiVlA4IjtpOjQ7czoyOiIzRCI7aTo1O3M6MzoiTi9BIjtpOjY7czo2OiJWb3JiaXMiO2k6NztzOjM6IjE5MiI7fWk6MTAyO2E6ODp7aTowO3M6MzoiMTAyIjtpOjE7czo0OiJXZWJNIjtpOjI7czo0OiI3MjBwIjtpOjM7czozOiJWUDgiO2k6NDtzOjI6IjNEIjtpOjU7czozOiJOL0EiO2k6NjtzOjY6IlZvcmJpcyI7aTo3O3M6MzoiMTkyIjt9aToxMzM7YTo2OntpOjA7czozOiIxMzMiO2k6MTtzOjM6Ik1QNCI7aToyO3M6NDoiMjQwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjQ6Ik1haW4iO2k6NTtzOjc6IjAuMi0wLjMiO31pOjEzNDthOjY6e2k6MDtzOjM6IjEzNCI7aToxO3M6MzoiTVA0IjtpOjI7czo0OiIzNjBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiTWFpbiI7aTo1O3M6NzoiMC4zLTAuNCI7fWk6MTM1O2E6Njp7aTowO3M6MzoiMTM1IjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjQ4MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czo0OiJNYWluIjtpOjU7czo1OiIwLjUtMSI7fWk6MTM2O2E6Njp7aTowO3M6MzoiMTM2IjtpOjE7czozOiJNUDQiO2k6MjtzOjQ6IjcyMHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czo0OiJNYWluIjtpOjU7czo1OiIxLTEuNSI7fWk6MTM3O2E6Njp7aTowO3M6MzoiMTM3IjtpOjE7czozOiJNUDQiO2k6MjtzOjU6IjEwODBwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiSGlnaCI7aTo1O3M6MzoiMi0zIjt9aToxMzg7YTo2OntpOjA7czozOiIxMzgiO2k6MTtzOjM6Ik1QNCI7aToyO3M6NToiMTQ0MHAiO2k6MztzOjU6IkguMjY0IjtpOjQ7czo0OiJIaWdoIjtpOjU7czozOiI0LjQiO31pOjE2MDthOjY6e2k6MDtzOjM6IjE2MCI7aToxO3M6MzoiTVA0IjtpOjI7czo0OiIxNDRwIjtpOjM7czo1OiJILjI2NCI7aTo0O3M6NDoiTWFpbiI7aTo1O3M6MzoiMC4xIjt9aToyNDI7YTo2OntpOjA7czozOiIyNDIiO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjI0MHAiO2k6MztzOjM6IlZQOSI7aTo0O3M6MzoiTi9BIjtpOjU7czo3OiIwLjEtMC4yIjt9aToyNDM7YTo2OntpOjA7czozOiIyNDMiO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjM2MHAiO2k6MztzOjM6IlZQOSI7aTo0O3M6MzoiTi9BIjtpOjU7czo0OiIwLjI1Ijt9aToyNDQ7YTo2OntpOjA7czozOiIyNDQiO2k6MTtzOjQ6IldlYk0iO2k6MjtzOjQ6IjQ4MHAiO2k6MztzOjM6IlZQOSI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIwLjUiO31pOjI0NzthOjY6e2k6MDtzOjM6IjI0NyI7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiNzIwcCI7aTozO3M6MzoiVlA5IjtpOjQ7czozOiJOL0EiO2k6NTtzOjc6IjAuNy0wLjgiO31pOjI0ODthOjY6e2k6MDtzOjM6IjI0OCI7aToxO3M6NDoiV2ViTSI7aToyO3M6NToiMTA4MHAiO2k6MztzOjM6IlZQOSI7aTo0O3M6MzoiTi9BIjtpOjU7czozOiIxLjUiO31pOjI2NDthOjY6e2k6MDtzOjM6IjI2NCI7aToxO3M6MzoiTVA0IjtpOjI7czo1OiIxNDQwcCI7aTozO3M6NToiSC4yNjQiO2k6NDtzOjQ6IkhpZ2giO2k6NTtzOjM6IjMuNyI7fWk6MjcxO2E6Njp7aTowO3M6MzoiMjcxIjtpOjE7czo0OiJXZWJNIjtpOjI7czo1OiIxNDQwcCI7aTozO3M6MzoiVlA5IjtpOjQ7czozOiJOL0EiO2k6NTtzOjE6IjQiO31pOjI3ODthOjY6e2k6MDtzOjM6IjI3OCI7aToxO3M6NDoiV2ViTSI7aToyO3M6NDoiMTQ0cCI7aTozO3M6MzoiVlA5IjtpOjQ7czozOiJOL0EiO2k6NTtzOjQ6IjAuMDgiO31pOjEzOTthOjQ6e2k6MDtzOjM6IjEzOSI7aToxO3M6MzoibTRhIjtpOjI7czozOiJBQUMiO2k6MztzOjI6IjQ4Ijt9aToxNDA7YTo0OntpOjA7czozOiIxNDAiO2k6MTtzOjM6Im00YSI7aToyO3M6MzoiQUFDIjtpOjM7czozOiIxMjgiO31pOjE0MTthOjQ6e2k6MDtzOjM6IjE0MSI7aToxO3M6MzoibTRhIjtpOjI7czozOiJBQUMiO2k6MztzOjM6IjI1NiI7fWk6MTcxO2E6NDp7aTowO3M6MzoiMTcxIjtpOjE7czo0OiJXZWJNIjtpOjI7czo2OiJWb3JiaXMiO2k6MztzOjM6IjEyOCI7fWk6MTcyO2E6NDp7aTowO3M6MzoiMTcyIjtpOjE7czo0OiJXZWJNIjtpOjI7czo2OiJWb3JiaXMiO2k6MztzOjM6IjE5MiI7fX0=';
   $VideoInformationExtractor->setDataset(unserialize(base64_decode($dataset)));
   return $VideoInformationExtractor;
});
AppContainer::register('InMemoryPersistence', function() {
   $InMemoryPersistence = new InMemoryPersistence; 
   return $InMemoryPersistence;
});

AppContainer::register('FileSettingsRepository', function() {
   $FileSettingsRepository = new FileSettingsRepository; 
   $FileSettingsRepository->attachFactory(AppContainer::resolve('FileSettingsFactory'));
   $FileSettingsRepository->attachExtractor(AppContainer::resolve('VideoInformationExtractor'));
   $FileSettingsRepository->attachPersistenceGateway(AppContainer::resolve('InMemoryPersistence'));
   return $FileSettingsRepository;
});

AppContainer::register('Downloader', function() {
   $Downloader = new Downloader; 
   $Downloader->attachExecuter(AppContainer::resolve('Executer'));
   $Downloader->attachFileSettingsRepository(AppContainer::resolve('FileSettingsRepository'));

   return $Downloader;
});

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Youtube downloader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<div class="container">

      <div class="starter-template">
		<div class="page-header">
			<h2>Youtube downloader v1.12 <small>And many other sites</small></h2>
		</div>
		

		<p>&nbsp;</p>
		<div class="panel panel-default">
		  <!-- Default panel contents -->
		  <div class="panel-heading">Download video</div>
		  <div class="panel-body">
    <p>
<?php



function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function make_filename($filename)
{
   $filename =  preg_replace("([^a-zA-Z0-9 :space:])", '', $filename);
   $filename =  str_replace(" ", "_", $filename);
   $filename =  str_replace("___", "_", $filename);
   $filename =  str_replace("__", "_", $filename);
   return $filename;
}


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$url =  "";
  	$url = test_input($_POST["url"]);

   $filename =  "";
   $filename = test_input($_POST["filename"]);
   $filename = make_filename($filename);

  	$quality =  "";
  	$quality = test_input($_POST["quality"]);

  	$format =  "";
  	$format = test_input($_POST["format"]);

	

	$file = AppContainer::resolve('FileFactory')->make(array($url, $quality, $format));
	$file->setFileName($filename);


	$downloader = AppContainer::resolve('Downloader');

	$downloader->setFile($file);
	$result = $downloader->download();

	if($result)
	{
		
		echo "<h2>Video downloaded!</h2>";

		echo '<div class="alert alert-success"><strong>Download finished!</strong> Download your file below: (right mousebutton save-as)</div><br />';
		echo "<a href=\"downloads/".$file->getFullFileName()."\" class=\"btn btn-primary btn-lg btn-block\" download>DOWNLOAD VIDEO</a><br /><br />";

		echo '<ul class="pager">
  			<li class="previous"><a href="'.$_SERVER["PHP_SELF"].'">&larr; Try again or download another movie</a></li>	
		</ul>';
	}else{
		echo "Something went wrong. Check the URL and <a href=\"".$_SERVER["PHP_SELF"]."\">try again</a>.";
	}
	
}else{
?>
	<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
	<div class="form-group">
	<label for="url">Step 1. Please enter full video URL (eg. http://www.youtube.com/watch?v=iV2ViNJFZC8 or http://vimeo.com/78029639)</label>
	<input class="form-control" type="text" name="url" size="50">
	<label for="url">Step 2. Desired filename (without extension!)</label>
	<input class="form-control" type="text" name="filename" size="50" value="video1">
	<br />
	<p><strong>Step 3. Quality (Best quality available recommended.)<br /></strong></p>
	<p class="text-primary">YouTube only, other sites will grab highest quality video available)<p>
		<div class="radio">
		  <label>
		    <input type="radio" name="quality" id="quality1" value="best" checked>
		    Best available
		  </label>
		</div>
		<div class="radio">
		  <label>
		   <input type="radio" name="quality" id="quality2" value="1080" >
		  	 HD: 1080 (if available)
		  </label>
		</div>
		<div class="radio">
		  <label>
		   <input type="radio" name="quality" id="quality3" value="720" >
		  	 HD: 720 (if available)
		  </label>
		</div>
		<div class="radio">
		  <label>
		   <input type="radio" name="quality" id="quality4" value="480" >
		  	 SD: 480 (if available) or 360
		  </label>
		</div>


		<p><strong>Step 4. File format (MP4 recommended)</strong></p>
		<div class="radio">
		  <label>
		    <input type="radio" name="format" id="format1" value="mp4" checked>
		    MP4
		  </label>
		</div>
		<div class="radio">
		  <label>
		   <input type="radio" name="format" id="format2" value="flv" >
		  	 FLV (SD Only)
		  </label>
		</div>
		<div class="radio">
		  <label>
		   <input type="radio" name="format" id="format3" value="webm" >
		  	 WebM (Mostly SD)
		  </label>
		</div>
		<div class="radio">
		  <label>
		   <input type="radio" name="format" id="format4" value="mp3" >
		  	 MP3 (audio only)
		  </label>
		</div>
	</div>
  	<button type="submit" class="btn btn-default">Download Video</button>
	
	</form>
	</p>
  		</div>
	</div>
	<div class="page-header">
		<h3>Supported sites <small>And many other sites</small></h3>
	</div>
	

	<div class="panel-group" id="accordion">
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
	          Tested
	        </a>
	      </h4>
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse in">
	      <div class="panel-body">
	       		<ul>
					<li><b>Youtube</b></li>
					<li><b>Vimeo</b></li>
					<li><b>Dailymotion</b></li>
				</ul>
	       </div>
	    </div>
	  </div>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
	          Untested
	        </a>
	      </h4>
	    </div>
	    <div id="collapseTwo" class="panel-collapse collapse">
	      <div class="panel-body">
				<ul>
				<li><b>AddAnime</b></li>
				<li><b>AppleTrailers</b></li>
				<li><b>archive.org</b>: archive.org videos</li>
				<li><b>ARD</b></li>
				<li><b>arte.tv</b></li>
				<li><b>Bandcamp</b></li>
				<li><b>Bloomberg</b></li>
				<li><b>canalc2.tv</b></li>
				<li><b>canalplus.fr</b></li>
				<li><b>CNN</b></li>
				<li><b>CollegeHumor</b></li>
				<li><b>ComedyCentral</b>: The Daily Show / Colbert Report</li>
				<li><b>CondeNast</b>: Condé Nast media group: GQ, Glamour, Vanity Fair, Vogue, W Magazine, WIRED</li>
				<li><b>DefenseGouvFr</b></li>
				<li><b>DepositFiles</b></li>
				<li><b>EbaumsWorld</b></li>
				<li><b>eHow</b></li>
				<li><b>Escapist</b></li>
				<li><b>facebook</b></li>
				<li><b>fernsehkritik.tv</b></li>
				<li><b>Flickr</b></li>
				<li><b>france2.fr</b></li>
				<li><b>francetvinfo.fr</b></li>
				<li><b>Freesound</b></li>
				<li><b>FunnyOrDie</b></li>
				<li><b>GameSpot</b></li>
				<li><b>Gametrailers</b></li>
				<li><b>generic</b>: Generic downloader that works on some sites</li>
				<li><b>ign.com</b></li>
				<li><b>Instagram</b></li>
				<li><b>InternetVideoArchive</b></li>
				<li><b>JeuxVideo</b></li>
				<li><b>Jukebox</b></li>
				<li><b>justin.tv</b></li>
				<li><b>KickStarter</b></li>
				<li><b>liveleak</b></li>
				<li><b>Livestream</b></li>
				<li><b>metacafe</b></li>
				<li><b>Metacritic</b></li>
				<li><b>mixcloud</b></li>
				<li><b>MTV</b></li>
				<li><b>MySpace</b></li>
				<li><b>NBA</b></li>
				<li><b>NBCNews</b></li>
				<li><b>Newgrounds</b></li>
				<li><b>nhl.com</b></li>
				<li><b>ORF</b></li>
				<li><b>photobucket</b></li>
				<li><b>plus.google</b>: Google Plus</li>
				<li><b>pluzz.francetv.fr</b></li>
				<li><b>RottenTomatoes</b></li>
				<li><b>RTLnow</b></li>
				<li><b>Slideshare</b></li>
				<li><b>soundcloud</b></li>
				<li><b>soundcloud:set</b></li>
				<li><b>soundcloud:user</b></li>
				<li><b>southparkstudios.com</b></li>
				<li><b>Spiegel</b></li>
				<li><b>stanfordoc</b>: Stanford Open ClassRoom</li>
				<li><b>Steam</b></li>
				<li><b>TechTalks</b></li>
				<li><b>techtv.mit.edu</b></li>
				<li><b>TED</b></li>
				<li><b>Tumblr</b></li>
				<li><b>Viddler</b></li>
				<li><b>Vine</b></li>
				<li><b>vk.com</b></li>
				<li><b>ZDF</b></li>
				</ul>
	      </div>
	    </div>
	  </div>
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
	          Changelog
	        </a>
	      </h4>
	    </div>
	    <div id="collapseThree" class="panel-collapse collapse">
	      <div class="panel-body">
	       		<ul>
					<li><b>V1.11 - 15-04-2014</b></li>
						<ul>
							<li><b>Fix: Allow non alphabetic characters in file name</b></li>
							<li><b>New: Added support for Dailymotion</b></li>
						</ul>
					<li><b>V1.12 - 07-05-2014</b></li>
						<ul>
							<li><b>Fix: Video format information markup was changed by youtube-dl. Downloader can now handle new markup </b></li>
							
						</ul>
					<li><b>V1.13 - 28-08-2014</b></li>
					<ul>
						<li><b>Fix: Youtube format codes updated. Better error reporting.</b></li>
						
					</ul>
				</ul>
	       </div>
	    </div>
	  </div>
	</div>	
<p style="text-align:center">Development: Bassie</p>
<p>&nbsp;</p>
<?php
}


?>
<p>&nbsp;</p>

	</div>
</div>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>