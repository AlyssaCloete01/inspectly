<?php

/**
 * A helper for user authentication related functions
 */
class AuthenticationHelper {
    
    /**
     *  Hash a plain text password, the function will use the selected algorithm,
     *  so then there is no need for knowing the underlying algorithm in case it changes
     * @param String $password
     * @return String A hash value of the password passed
     */
    public static function password_hash($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    /**
     * This function verifies the passed username against the passed hash value
     * 
     * @param String $password The password to be verified
     * @param String $passwordHash The hash to be used in verifying the password.
     * @return boolean True if the passed password matches the passed hash
     */
    public static function password_verify( $password, $passwordHash ){
        return password_verify($password, $passwordHash);
    }
    
}