<?php
    /**
     * 2007-2014 [PagSeguro Internet Ltda.]
     *
     * NOTICE OF LICENSE
     *
     *Licensed under the Apache License, Version 2.0 (the "License");
     *you may not use this file except in compliance with the License.
     *You may obtain a copy of the License at
     *
     *http://www.apache.org/licenses/LICENSE-2.0
     *
     *Unless required by applicable law or agreed to in writing, software
     *distributed under the License is distributed on an "AS IS" BASIS,
     *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
     *See the License for the specific language governing permissions and
     *limitations under the License.
     *
     * @author    PagSeguro Internet Ltda.
     * @copyright 2007-2014 PagSeguro Internet Ltda.
     * @license   http://www.apache.org/licenses/LICENSE-2.0
     */

    /***
     * Class PagSeguroPaymentParserData
     */
    class PagSeguroPaymentParserData
    {
        /***
         * @var $code
         */
        private $code;
        /***
         * @var $registrationDate
         */
        private $registrationDate;

        /***
         * @return mixed
         */
        public function getCode(){
            return $this->code;
        }

        /***
         * @param $code
         */
        public function setCode($code){
            $this->code = $code;
        }

        /***
         * @return mixed
         */
        public function getRegistrationDate(){
            return $this->registrationDate;
        }

        /***
         * @param $registrationDate
         */
        public function setRegistrationDate($registrationDate){
            $this->registrationDate = $registrationDate;
        }
    }
