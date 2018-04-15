<?php


class LMSCrypt {

    // Algorithme utilis� pour le cryptage des blocs
    private static $cipher = MCRYPT_RIJNDAEL_256;
    // Mode op�ratoire (traitement des blocs)
    private static $mode = MCRYPT_MODE_CBC;

    // Cl� de cryptage (32 octets)
    private static $key = 'algDE7h;2p;rW[~R4W[Q]_>wLb3e*!:>';

    /** Crypt the given data
     * The given data is first serialized, then encrypted thanks to the AES algorithm.
     * Return: base64 encoding of the encrypted data, otherwise false (i.e. in case of error).
     */
    public static function crypt($data)
    {
        $iv_size = mcrypt_get_iv_size(self::$cipher, self::$mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);

        $encrypted = mcrypt_encrypt( self::$cipher, self::$key, serialize($data), self::$mode, $iv );

        $base64 = false;
        if(is_string($encrypted)) {
            $base64 = base64_encode($iv . $encrypted);
        }
        return $base64;
    }

    /** Decrypt the given data
     * The given data is first base64 decoding, then decrypted thanks to the AES algorithm,
     * and finally unserialized in order to retrieve the original data (as given to Crypt function).
     * Return: decrypted data, otherwise false (i.e. in case of error).
     */
    public static function decrypt($base64)
    {
        $ret = false;

        try {
            $decoded = base64_decode( $base64, true );

            if ($decoded !== FALSE) {
                $iv_size = mcrypt_get_iv_size(self::$cipher, self::$mode);
                $iv = substr($decoded, 0, $iv_size);
                $decoded = substr($decoded, $iv_size);

                if( is_string($decoded) && !is_array($decoded)) {

                    $serialized = mcrypt_decrypt( self::$cipher, self::$key, $decoded, self::$mode, $iv );

                    if($serialized !== FALSE)  {
                        $ret = unserialize($serialized);
                    }
                }
            }
        } catch (Exception $e) {
            $ret = false;
        }
        return $ret;
    }
}

class LMSHardware {

    static $kindLaptopSN = "LaptopSN";
    static $kindMotherboardSN = "MotherboardSN";
    static $kindPhysicalMacAddress = "PhysicalMacAddress";

    // e.g. LaptopSN|no restriction except empty string
    private static $LaptopSnRegex = "LaptopSN\|.+";

    // e.g. MotherboardSN|no restriction except empty string
    private static $MotherboardSnRegex = "MotherboardSN\|.+";

    // The standard (IEEE 802) format for printing MAC-48 addresses in
    // human-friendly form is six groups of two hexadecimal digits,
    // separated by hyphens - or colons :.
    // e.g. PhysicalMacAddress|D4:C9:EF:53:A2:12
    private static $MacAddressRegex = "PhysicalMacAddress\|([0-9A-F]{2}[:-]){5}([0-9A-F]{2})(\|([0-9A-F]{2}[:-]){5}([0-9A-F]{2}))*$";

    /** Check the validity of the hardware identifier
     *
     * Empty "hardware" is not auhtorized.
     *
     * Hardware syntax: <kind>|<value>
     *    kind = LaptopSN when serial number of the laptop is used.
     *    kind = MotherboardSN when serial number of the motherboard is used.
     *    kind = PhysicalMacAddress when all physical MAC Address are used (i.e. value = array of MacAddress serialized thanks to json_encode).
     */
    public static function isValidHardware($givenHardware)
    {
        $regex = sprintf("#^(%s)|(%s)|(%s)$#i", LMSHardware::$LaptopSnRegex,
            LMSHardware::$MotherboardSnRegex, LMSHardware::$MacAddressRegex);
        $validity = preg_match($regex, $givenHardware);

        return $validity;
    }
}

class LMSSecure {

    public static function loadLicense( &$hardware, &$licensee, &$request, &$expiration, &$activated, &$revocationReason )
    {
        return false;

        $licenseFound = false;

        $db = empBD::globalBD( $GLOBALS[ "DB_VWUSERS" ], __FILE__, __LINE__ );
        $query = "SELECT * FROM secure;";

        $nb_licenses = $db->query_res_bd( $query, $rsresult, __FILE__, __LINE__ );

        // Only one license
        if ( $nb_licenses == 1 ) {
            try {
                $recordedLicense = $db->fetch_array_bd( $rsresult );

                $hardware = $recordedLicense[ "hardware" ];
                $licensee = $recordedLicense[ "licensee" ];
                $request = $recordedLicense[ "request" ];
                $expiration = $recordedLicense[ "expiration" ];
                $activated = $recordedLicense[ "activated" ];
                $revocationReason = $recordedLicense[ "revocation_reason" ];

                $db->free_res_bd( $rsresult );

                $licenseFound = true;
            } catch ( Exception $e ) {
                $licenseFound = false;
            }
        }

        return $licenseFound;
    }

    /**
     * Timezone is ignored.
     */
    public static function isActivated( $givenHardware )
    {
        //return true;
        $enable = false;
        $hardware = $licensee = $request = $expiration = $activated = $revocationReason = '';


        if ( self::loadLicense( $hardware, $licensee, $request, $expiration, $activated, $revocationReason ) ) {

            $remainingDay = self::remainingDay( $expiration );
            if ( $remainingDay != false ) {
                $isExpired = $remainingDay <= 0;
                if ( $isExpired ) {
                    // first time the expiration date is encountered (because detected by the application)
                    if ( $activated === '1' ) {
                        self::revokeLicense( "Expiration date detected on " . date( "Y-M-d" ) );
                    }
                }
            }

            // Check the hardware only if expiration date is not already reached
            // and the license is activated
            if ( !$isExpired && $activated==='1' ) {
                $enable = self::isMatchingHardware( $givenHardware, $hardware );
                if ( !$enable ) {
                    self::revokeLicense("The following detected hardware does not match the current license: " . $givenHardware);
                }
            }
        }

        return $enable;
    }

    /** Revoke the license if exists only.
     *
     */
    public static function revokeLicense( $revocationReason )
    {
        // disable the license and notify the reason
        $db = empBD::globalBD( $GLOBALS[ "DB_VWUSERS" ], __FILE__, __LINE__ );
        $query = sprintf( "update secure set activated=0, revocation_reason='%s'", $revocationReason );
        $result = $db->query_bd( $query, __FILE__, __LINE__ );
    }

    /** Same hardware regarding : serial number of the laptop if available,
     *   otherwise (i.e. not available) with the serial number of the
     *   motherboard if available, otherwise (i.e. not available) with
     *   any MAC address among the physical network adapters.
     */
    private static function isMatchingHardware( $givenHardware, $hardware )
    {
        //return true;
        $validity = LMSHardware::isValidHardware( $givenHardware )
            && LMSHardware::isValidHardware( $hardware );

        if ( $validity ) {
            $validity = false;
            // retrieve the kind of hardware identifier and its value from the stored license.
            switch ( $hardware[ 0 ] ) {
                case 'L':
                case 'M':
                    // serial number
                    $validity = $hardware === $givenHardware;
                    break;

                case 'P':
                    // mac address
                    $startIdentifier = strlen( LMSHardware::$kindPhysicalMacAddress ) + 1;
                    $stored = substr( $hardware, $startIdentifier );
                    $stored = explode( "|", $stored );

                    if ( strlen( $givenHardware ) > $startIdentifier ) {

                        $given = substr( $givenHardware, $startIdentifier );
                        $given = explode( "|", $given );

                        // Seek in $given one matching MAC Address from $stored
                        foreach ( $stored as $sn ) {
                            foreach ( $given as $tested ) {
                                $validity = $sn === $tested;
                                if ( $validity ) {
                                    // first valid matching is enough
                                    break 2;
                                }
                            }
                        }
                    }
                    break;
            }
        }

        return $validity;
    }

    /** Saves the given license into database
     *
     * @return boolean true on success, false on error
     */
    public static function saveLicense( $license )
    {
        //return true;

        $result = false;
        try {
            if ( self::isValidLicense( $license ) ) {
                if ( self::cleanSecure() ) {
                    $db = empBD::globalBD( $GLOBALS[ "DB_VWUSERS" ], __FILE__, __LINE__ );

                    // revocation reason is deleted (due to the new license)
                    $q = "INSERT INTO secure (hardware, licensee, request, expiration, activated, revocation_reason) VALUES";
                    $q .= '("' . $license[ 'hardware' ] . '","' . $license[ 'licensee' ] . '","'
                        . $license[ 'request' ] . '","' . $license[ 'expiration' ] . '",' . $license[ 'activated' ] . ', null)';

                    $licenseId = $db->insert_ID( $q, __FILE__, __LINE__ );

                    if ( $licenseId !== '-1' ) {
                        $q = "INSERT INTO `secure_expire` ( `secure_id`, `interval`, `passed`) VALUES( $licenseId, 30, 0),($licenseId, 20, 0),($licenseId, 10, 0);";
                        $popupId = $db->insert_ID( $q, __FILE__, __LINE__ );

                        $result = $popupId !== '-1';
                    }
                }
            }
        } catch ( Exception $e ) {
            $result = false;
        }
        return $result;
    }

    public static function isUpdatedTime()
    {
        $systemTimeUpdated = false;

        $currentTimestamp = time();

        $db = empBD::globalBD( $GLOBALS[ "DB_VWUSERS" ], __FILE__, __LINE__ );
        $query = "SELECT lastTimestamp FROM secure;";
        $nb_licenses = $db->query_res_bd( $query, $rsresult, __FILE__, __LINE__ );
        if($nb_licenses == 1)
        {
            $recordedLicense = $db->fetch_array_bd($rsresult);
            $lastSavedTimestamp = $recordedLicense["lastTimestamp"];

            // Check the time has been set to a former time.
            $systemTimeUpdated = $currentTimestamp < $lastSavedTimestamp;
            if($systemTimeUpdated) {
                $amountOfDays = $hours = floor(($lastSavedTimestamp - $currentTimestamp) / (3600 * 24));
                if($amountOfDays>0) {
                    LMSSecure::revokeLicense(sprintf("The system date has been updated (decreased about %u days).", $amountOfDays));
                } else {
                    LMSSecure::revokeLicense(sprintf("The system date has been updated (decreased about %s).",
                        gmdate("H:i:s", $lastSavedTimestamp - $currentTimestamp)));
                }
            }
        }

        // Save the current time
        $query = sprintf("Update secure set lastTimestamp=%u;", $currentTimestamp);
        $db->query_res_bd( $query, $rsresult, __FILE__, __LINE__ );

        return $systemTimeUpdated;
    }

    private static function isValidLicense( $license )
    {
        //return true;

        $remainingDay = self::remainingDay( $license[ 'expiration' ] );
        $valid = is_array( $license )
            && ($remainingDay != false) && ($remainingDay > 0)
            && $license[ 'activated' ] === "1"
            && LMSHardware::isValidHardware( $license[ 'hardware' ] );
        return $valid;
    }

    private static function remainingDay( $strFromDate )
    {
        $remainingDay = false;

        if ( is_string( $strFromDate ) && trim( $strFromDate ) !== "" ) {
            $fromDate = DateTime::createFromFormat( 'Y-m-d H:i:s', $strFromDate . " 00:00:00" );

            $today = DateTime::createFromFormat( 'Y-m-d H:i:s', date( "Y-m-d" ) . " 00:00:00" );
            $remainingDay = intval( $today->diff( $fromDate, false )->format( "%R%a" ) );
        }
        return $remainingDay;
    }

    /** Only one license
     *
     */
    private static function cleanSecure()
    {
        $db = empBD::globalBD( $GLOBALS[ "DB_VWUSERS" ], __FILE__, __LINE__ );

        $strsql = "truncate secure";
        $result = $db->query_bd( $strsql, __FILE__, __LINE__ );
        if($result !== '-1') {
            $strsql = "truncate secure_expire";
            $result = $db->query_bd( $strsql, __FILE__, __LINE__ );
        }

        return $result !== '-1';
    }

}


class empBD {

    /**
     * Name of the base to connect to
     * 
     * @access public
     * @var string
     */
    public $DB;

    /**
     * Constructor
     * 
     * Use empDB::globalBD(); instead
     * 
     * @access private
     */
    protected function __construct()
    {
        print ("This constructor cannot be called; use empDB::globalBD(); instead" );
    }

    /**
     * Creates an object of the right type according to the database mentioned in the config file
     * 
     * @static
     * @access public
     * @param string $base name of the base to connect to
     * @param string $file name of the file that is calling this method (used to log errors)
     * @param int $line line that calls the method
     * @return mixed database management object (either empMySQLBD, empPostgreSQLBD or empOracleBD)
     */
    static function globalBD( $base, $file = null, $line = null )
    {
        $impl = & $GLOBALS[ "empBD" ];
        $DB = & $base;
        $implementation = & $GLOBALS[ "DB_TYPE" ];

        switch ( $implementation ) {
            case "mysql" : {
                    include_once "empmysqlbd.php";
                    $impl = new empMySQLBD( $DB, $file, $line );
                }
                break;

            case "postgresql" : {
//                  include_once (dirname(__FILE__)."\emppostgresqlbd.php");
//                  $impl = new empPostgreSQLBD($DB, $file, $line);
                }
                break;

            case "oracle" : {
                    include_once "emporaclebd.php";
                    $impl = new empOracleBD( $DB, $file, $line );
                }
                break;

            default : {
                    print ("Database error: have no support for $implementation" );
                }
                break;
        }
        return $impl;
    }

}

class empLog {

    /**
     * Path where the error log file is stored
     *
     * @access public
     * @var string
     */
    public $patherror;

    /**
     * Path where the message log file is stored
     *
     * @access public
     * @var string
     */
    public $pathmessage;

    /**
     * IP address of the user requesting the PHP script from the server
     *
     * @access public
     * @var string
     */
    public $remoteaddr;

    /**
     * Current time / date
     *
     * @access public
     * @var string
     */
    public $dateobt;

    /**
     * Constructor
     *
     * @access public
     */
    protected function __construct()
    {
        $this->patherror = $GLOBALS[ "PATH_ERROR" ];
        $this->remoteaddr = $_SERVER[ "REMOTE_ADDR" ];
        $this->pathmessage = $GLOBALS[ "PATH_MESSAGE" ];
        $this->dateobt = $this->Obtient_Date();
    }

    /**
     * Retrieves current time / date
     *
     * @static
     * @return string date
     */
    static function Obtient_Date()
    {
        $aujourdhui = @ getdate();
        $heure = @ gettimeofday();
        return $aujourdhui[ 'year' ] . "/" . $aujourdhui[ 'mon' ] . "/" . $aujourdhui[ 'mday' ] . " " . $aujourdhui[ 'hours' ] . ":" . $aujourdhui[ 'minutes' ] . ":" . $aujourdhui[ 'seconds' ];
    }

    // trace logs methods

    /**
     * Logs a fatal error into the file which path / name is set in the application config file
     *
     * @static
     * @param string $Message message to log
     * @param string $file name of the file that is calling this method (used to log errors)
     * @param int $line line that calls the method
     * @return string message logged
     */
    static function Log_Fatal( $Message, $File = null, $Line = null )
    {
        $log = new empLog();
        if ( empty( $File ) && empty( $Line ) ) {
            $precision = "";
        } else {
            $precision = " (" . $File . " - " . $Line . ")";
        }
        @ error_log( "[$log->dateobt] - $log->remoteaddr - FATAL - " . $Message . $precision . "\n", 3, $log->patherror );
        return ($Message);
    }

    /**
     * Logs an error into the file which path / name is set in the application config file
     *
     * @static
     * @param string $Message message to log
     * @param string $file name of the file that is calling this method (used to log errors)
     * @param int $line line that calls the method
     * @return string message logged
     */
    static function Log_Error( $Message, $File = null, $Line = null )
    {
        $log = new empLog();
        if ( empty( $File ) && empty( $Line ) ) {
            $precision = "";
        } else {
            $precision = " (" . $File . " - " . $Line . ")";
        }
        @ error_log( "[$log->dateobt] - $log->remoteaddr - ERROR - " . $Message . $precision . "\n", 3, $log->patherror );
        return ($Message);
    }

    /**
     * Logs a warning into the file which path / name is set in the application config file
     *
     * @static
     * @param string $Message message to log
     * @param string $file name of the file that is calling this method (used to log errors)
     * @param int $line line that calls the method
     * @return string message logged
     */
    static function Log_Warning( $Message, $File = null, $Line = null )
    {
        $log = new empLog();
        if ( empty( $File ) && empty( $Line ) ) {
            $precision = "";
        } else {
            $precision = " (" . $File . " - " . $Line . ")";
        }
        @ error_log( "[$log->dateobt] - $log->remoteaddr - WARNING - " . $Message . $precision . "\n", 3, $log->patherror );
        return ($Message);
    }

    /**
     * Logs an info message into the file which path / name is set in the application config file
     *
     * @static
     * @param string $Message message to log
     * @param string $file name of the file that is calling this method (used to log errors)
     * @param int $line line that calls the method
     * @return string message logged
     */
    static function Log_Info( $Message, $File = null, $Line = null )
    {
        $log = new empLog();
        if ( empty( $File ) && empty( $Line ) ) {
            $precision = "";
        } else {
            $precision = " (" . $File . " - " . $Line . ")";
        }
        @ error_log( "[$log->dateobt] - $log->remoteaddr  - INFO - " . $Message . $precision . "\n", 3, $log->patherror );
        return ($Message);
    }

    /**
     * Logs a message into the file which path / name is set in the application config file
     *
     * @static
     * @param string $Message message to log
     * @return string message logged
     */
    static function Log_Message( $Message )
    {
        //$log = new empLog();
        //@error_log("MESSAGE - ".$Message."\n",3,$log->pathmessage);
        @ error_log( "MESSAGE - " . $Message . "\n", 3, $GLOBALS[ "PATH_MESSAGE" ] );
        return ($Message);
    }

    /**
     * Logs a message
     *
     * @static
     * @param string $Message message to log
     * @return string message logged
     */
    static function Log_TextInFile( $Message, $fileToLog )
    {
        @ error_log( $Message . "\n", 3, $fileToLog );
        return ($Message);
    }

}
