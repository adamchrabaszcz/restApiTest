<?php

namespace calling;

use Mysqli;
use Exception;

class mothership {

   private $mysqli;

   var $fetch_company_unique_id_suggestions = array();

   var $fetch_company_information = array();

   var $token;

   function __construct() {

           // TYPO
          $this->mysqli = new Mysqli('localhost', 'database', 'password', 'database');

   }

    private function sanitize_input($input) {

      if(is_array($input)) {

             foreach($input as $k => $v) {

                    $input[$k] = $this->mysqli->real_escape_string($v);
                }

         } else {

                $input = $this->mysqli->real_escape_string($input);

         }

         return $input;

     }

     private function create_token() {

         $bytes = openssl_random_pseudo_bytes(30);

         $this->token = bin2hex($bytes);
     }

     private function check_token($input) {

           $validate_token = $this->mysqli->query("SELECT token FROM users WHERE token = '".$input."'")->fetch_assoc();

           if($validate_token['token'] == '') {

                  throw new Exception('invalid token');

                  return false;

           } else {

                  return true;

           }

    }
            
    function sign_up($input) {

          $input = $this->sanitize_input($input);

          $validate_sign_up_results = $this->mysqli->query("SELECT email FROM users WHERE email = '".$input['email']."'")->fetch_assoc();

          if($validate_sign_up_results['email'] != '') {

                throw new Exception('email already exists');

          } else {

                 $this->create_token();
                 
                 $this->mysqli->query("INSERT INTO users (name,email,password,token) VALUES ('".$input['name']."','".$input['email']."','".$input['password']."','".$this->token."')");
             }
             
             //ADDDED RETURN TOKEN
             return $this->token;

      }

      function select_company_unique_id_suggestions($input) {

             if(!$this->check_token($input['token'])) { return false; }

             $company_name_results = $this->mysqli->query("SELECT * FROM Virksomhedsoplysninger WHERE Virksomhed LIKE '%".$input['company_name']."%'");

             if($company_name_results->num_rows == 0) {

                    throw new Exception('no records found');

             }

             $counter = 0;

             while ($company_name_result = $company_name_results->fetch_assoc()) {

                    $this->fetch_company_unique_id_suggestions[$counter]['unique_id'] = $company_name_result['CVRNummer'];

                    $this->fetch_company_unique_id_suggestions[$counter]['company_name'] = $company_name_result['Virksomhed'];

                    $counter++;

             }

             return $this->fetch_company_unique_id_suggestions;

      }

      function select_company_unique_id($input) {

             if(!$this->check_token($input['token'])) { return false; }

             $input['unique_id'] = $this->sanitize_input($input['unique_id']);

             // TYPE TABLE NAME..
             $record = $this->mysqli->query("SELECT * FROM Virksomhedsoplysninger WHERE CVRNummer = '".$input['unique_id']."'")->fetch_assoc();

             if($record['CVRNummer'] == '') {

                    throw new Exception('Unique ID Not Found');

             } else {

                    $this->fetch_company_information['unique_id'] = $record['CVRNummer'];

                    // DOES NOT EXISTS..
                    //$this->fetch_company_information['category_id'] = $record['deltager_enhedsNummer'];

                    //$this->fetch_company_information['address'] = $record['Adresse'];

                    //$this->fetch_company_information['city'] = $record['Kommunenavn'];

                    //$this->fetch_company_information['district'] = $record['Postdistikt'];

                    //$this->fetch_company_information['country'] = $record['Land'];

             }

             return $this->fetch_company_information;

      }


      function add_company_unique_id_to_account($input) {

             if(!$this->check_token($input['token'])) { return false; }

             $this->mysqli->query("UPDATE users SET company_unique_id = '".$input['company_unique_id']."' WHERE token = '".$input['token']."'");

             if($this->mysqli->affected_rows == 0) {

                    throw new Exception('Account Already Updated');

             }

      }

      function log_in($input) {

             $record = $this->mysqli->query("SELECT * FROM users WHERE email = '".$input['email']."'")->fetch_assoc();

             if(password_verify($input['password'], $record['password'])) {

                 $this->create_token();

                 $this->mysqli->query("UPDATE users SET token = '".$this->token."' WHERE email = '".$input['email']."'");

          } else {

                 throw new Exception('login inncorrect');

          }

   }

}

?>