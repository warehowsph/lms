<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Application specific global variables
class Globals
{
   private static $schoolCode = null;
   private static $s3BaseUrl = null;
   private static $initialized = false;

   private static function initialize()
   {
      if (self::$initialized)
         return;

      self::$schoolCode = null;
      self::$s3BaseUrl = null;
      self::$initialized = true;
   }

   public static function setSchoolCode($schoolcode)
   {
      self::initialize();
      self::$schoolCode = $schoolcode;
   }


   public static function getSchoolCode()
   {
      self::initialize();
      return self::$schoolCode;
   }

   public static function setS3BaseUrl($s3baseurl)
   {
      self::initialize();
      self::$s3BaseUrl = $s3baseurl;
   }

   public static function getS3BaseUrl()
   {
      self::initialize();
      return self::$s3BaseUrl;
   }
}
