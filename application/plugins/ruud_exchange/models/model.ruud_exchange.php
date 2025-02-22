<?php

    class Mruud_exchange extends model
    {
        private $characters = [];

        public function __contruct(){
            parent::__construct();
        }

        /**
         * Load required character data from database on current account
         *
         * @param string $account
         * @param string $server
         *
         * @return mixed
         */
        public function load_char_list($account, $server){
            $stmt = $this->website->db('game', $server)->prepare('SELECT Name, '.$this->website->get_char_id_col($server).' AS id FROM Character WHERE AccountId = :account');
            $stmt->execute([':account' => $account]);
            $i = 0;
            while($row = $stmt->fetch()){
                $this->characters[] = ['id' => $row['id'], 'Name' => $row['Name']];
                $i++;
            }
            if($i > 0){
                return $this->characters;
            } else{
                return false;
            }
        }

        /**
         * Check if character exists
         *
         * @param string $account
         * @param string $server
         * @param int $id
         *
         * @return mixed
         */
        public function check_char($account, $server, $id){
			if($this->website->db('game', $server)->check_if_column_exists('RuudMoney', 'Character') != false){
				$ruud = 'RuudMoney AS Ruud';
			}
			else{
				$ruud = 'Ruud';
			}
            $stmt = $this->website->db('game', $server)->prepare('SELECT TOP 1 Name, '.$this->website->get_char_id_col($server).', '.$ruud.' FROM Character WHERE AccountId = :account AND '.$this->website->get_char_id_col($server).' = :id');
            $stmt->execute([':account' => $account, ':id' => $id]);
            return $stmt->fetch();
        }
		
		 /**
         * Add Character ruud
         *
         * @param string $account
         * @param string $server
         * @param int $id
         *
         * @return bool
         */
        public function add_ruud($account, $server, $id){
			if($this->website->db('game', $server)->check_if_column_exists('RuudMoney', 'Character') != false){
				$ruud = 'RuudMoney';
			}
			else{
				$ruud = 'Ruud';
			}
            $stmt = $this->website->db('game', $server)->prepare('UPDATE Character SET '.$ruud.' = 0 WHERE AccountId = :account AND '.$this->website->get_char_id_col($server).' = :id');
            return $stmt->execute([':money' => $money, ':account' => $account, ':id' => $id]);
        }

        /**
         * Update Character ruud
         *
         * @param string $account
         * @param string $server
         * @param int $id
         * @param int $money
         *
         * @return bool
         */
        public function update_ruud($account, $server, $id, $money){
			if($this->website->db('game', $server)->check_if_column_exists('RuudMoney', 'Character') != false){
				$ruud = 'RuudMoney';
			}
			else{
				$ruud = 'Ruud';
			}
            $stmt = $this->website->db('game', $server)->prepare('UPDATE Character SET '.$ruud.' = '.$ruud.' + :money WHERE AccountId = :account AND '.$this->website->get_char_id_col($server).' = :id');
            return $stmt->execute([':money' => $money, ':account' => $account, ':id' => $id]);
        }

        /**
         * Check if account is connected to game
         *
         * @param string $account
         * @param string $server
         *
         * @return bool
         */
        public function check_connect_stat($account, $server){
            $stmt = $this->website->db('account', $server)->prepare('SELECT ConnectStat FROM MEMB_STAT WHERE memb___id = :user');
            $stmt->execute([':user' => $account]);
            if($status = $stmt->fetch()){
                return ($status['ConnectStat'] == 0);
            }
            return true;
        }
    }
