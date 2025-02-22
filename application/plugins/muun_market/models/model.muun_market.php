<?php
    in_file();

    class Mmuun_market extends model
    {
        public $error = false, $vars = [], $characters = [], $total;
        private $price, $per_page, $items, $char_list = [], $pos;

        public function __contruct(){
            parent::__construct();
        }

        public function __set($key, $val){
            $this->vars[$key] = $val;
        }

        public function __isset($name){
            return isset($this->vars[$name]);
        }

		public function load_char_list($account, $server){
            $stmt = $this->website->db('game', $server)->prepare('SELECT '.$this->website->get_char_id_col($server).' AS id, Name, cLevel, Class FROM Character WHERE AccountId = :account');
            $stmt->execute([':account' => $account]);
            $i = 0;
            while($row = $stmt->fetch()){
                $this->characters[] = [
					'id' => $row['id'],
					'name' => $row['Name'], 
					'level' => $row['cLevel'], 
					'Class' => $row['Class']
				];
                $i++;
            }
           
            if($i > 0){
                foreach($this->characters AS $key => $char){
                    $this->get_muun_content($char['name'], $server);
                    $this->characters[$key]['Muuns'] =  ($this->vars['Items'] != false) ? $this->load_muuns($this->vars['Items'], $server) : false;
                }
                return $this->characters;
            } else{
                return false;
            }
        }       
		      
		
		public function load_char_list_for_select($account, $server){
            $stmt = $this->website->db('game', $server)->prepare('SELECT '.$this->website->get_char_id_col($server).' AS id, Name FROM Character WHERE AccountId = :account');
            $stmt->execute([':account' => $account]);
            $i = 0;
            while($row = $stmt->fetch()){
                $this->characters[] = [
					'id' => $row['id'],
					'name' => $row['Name']
				];
                $i++;
            }
            if($i > 0){
                return $this->characters;
            } else{
                return false;
            }
        }   

        public function check_char($char, $account, $server, $byId = true){
			$check = ($byId == true) ? $this->website->get_char_id_col($server) : 'Name';
            $stmt = $this->website->db('game', $server)->prepare('SELECT Name FROM Character WHERE AccountId = :user AND '.$check.' = :char');
            $stmt->execute([':user' => $account, ':char' => $char]);
            return $stmt->fetch();
        }
		
		
		public function get_muun_content($char, $server = ''){
			if($this->website->db('game', $server)->check_if_table_exists('MuunInventory'))
				$tbl = 'MuunInventory';
			else
				$tbl = 'IGC_Muun_Inventory';
			$stmt = $this->website->db('game', $server)->prepare('SELECT CONVERT(IMAGE, Items) AS Items FROM '.$tbl.' WHERE Name = :char');
			$stmt->execute([':char' => $char]);
			$inv = $stmt->fetch();
			$stmt->close_cursor();
			if($inv != false){
				$unpack = unpack('H*', $inv['Items']);
				$this->vars['Items'] = $this->website->clean_hex($unpack[1]);
			} 
			else{
				$this->vars['Items'] = false;
			}
        }

				
		public function check_muun($char, $server, $hex){
			$this->get_muun_content($char, $server);
			if($this->vars['Items'] == false)
				return false;
			else{
				return $this->find_item_by_hex($hex);
			}				
		}

				
		public function find_item_by_hex($hex) {
            $items = str_split($this->vars['Items'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
			foreach($items AS $key => $val){
				if($val == $hex){
					return $key;
				}
			}
            return false;
        }
		
		
		public function find_free_slot($char, $server) {
			$this->get_muun_content($char, $server);
            $items = str_split($this->vars['Items'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
			foreach($items AS $key => $val){
				if($key <= 2)
					continue;
				if(strtoupper($val) == str_pad("", $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), "F")){
					return $key;
				}
			}
            return false;
        }

		public function generate_new_item_by_slot($slot, $item = ''){
            $hex = str_split($this->vars['Items'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
            if(isset($hex[$slot])){
                $hex[$slot] = ($item == '') ? str_pad("", $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), "F") : $item;
            }
            $this->vars['new_hex'] = implode('', $hex);
        }
		
		
		public function update_muun_inventory($char , $server){
			if($this->website->db('game', $server)->check_if_table_exists('MuunInventory'))
				$tbl = 'MuunInventory';
			else
				$tbl = 'IGC_Muun_Inventory';
            $stmt = $this->website->db('game', $server)->prepare('UPDATE '.$tbl.' SET Items = 0x' .  $this->vars['new_hex'] . ' WHERE Name = :char');
            $stmt->execute([':char' => $char]);
        }

				
		public function update_muun_period($char, $server, $serial){
			if($this->website->db('game', $server)->check_if_table_exists('IGC_Muun_Period')){
				$stmt = $this->website->db('game', $server)->prepare('UPDATE IGC_Muun_Period SET Name = :name WHERE Serial = :serial');
				$stmt->execute([':name' => $char, ':serial' => $serial]);
			}
        }

				
		public function update_muun_condition($char, $server, $slot = false, $serial = ''){
			if($this->website->db('game', $server)->check_if_table_exists('IGC_Muun_ConditionInfo')){
				if($serial == ''){
					$stmt = $this->website->db('game', $server)->prepare('UPDATE IGC_Muun_ConditionInfo SET Name = :name WHERE SlotIndex = :slot AND Name = :namee');
					$stmt->execute([':name' => $char, ':slot' => $slot, ':namee' => $char]);
				}
				else{
					$stmt = $this->website->db('game', $server)->prepare('UPDATE IGC_Muun_ConditionInfo SET Name = :name, SlotIndex = :slot WHERE Name = :char');
					$stmt->execute([':name' => $char, ':slot' => $slot, ':char' => $serial]);
				}
			}
        }

				
		public function load_muuns($items, $server = ''){
            $items_array = str_split($items, $this->website->get_value_from_server($server, 'item_size'));
            foreach($items_array as $key => $item){
                if($item != str_pad("", $this->website->get_value_from_server($server, 'item_size'), "F")){
					if($key == 2){
						$equipment[$key] = 0;
					}
					else{
						$this->iteminfo->itemData($item, true, $server);
						$equipment[$key]['item_id'] = $this->iteminfo->id;
						$equipment[$key]['item_cat'] = $this->iteminfo->type;
						$equipment[$key]['name'] = $this->iteminfo->realName();
						$equipment[$key]['level'] = (int)substr($this->iteminfo->getLevel(), 1);
						$equipment[$key]['hex'] = $item;
					}
                } else{
                    $equipment[$key] = 0;
                }
            }												   
            return $equipment;
        }

				
		public function add_muun_into_market($hex, $mcharacter, $time, $payment_method, $price, $account, $server){
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Muun_Market (price, price_type, item, seller, add_date, active_till, seller_acc, server) VALUES (:price, :price_type, :item, :seller, GETDATE(), :end_date, :seller_acc, :server)');
            return $stmt->execute([
				':price' => $price, 
				':price_type' => $payment_method, 
				':item' => $hex, 
				':seller' => $mcharacter, 
				':end_date' => date('Ymd H:i:s', strtotime('+' . $time . ' days', time())), 
				':seller_acc' => $account,
				':server' => $server,
			]);
        }

		public function count_total_muuns($server){
            $this->total = $this->website->db('web')->snumrows('SELECT COUNT(id) AS count FROM DmN_Muun_Market WHERE active_till > GETDATE() AND sold != 1 AND removed != 1 AND server = '.$this->website->db('web')->escape($server).'');
        }

		public function load_market($page, $per_page = 25, $server, $tax = 0){
            $this->per_page = ($page <= 1) ? 0 : $per_page * ($page - 1);
            $this->items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->escape((int)$per_page) . ' id, add_date, active_till, price, price_type, item, seller, server FROM DmN_Muun_Market WHERE active_till > GETDATE() AND sold != 1  AND removed != 1 AND server = '.$this->website->db('web')->escape($server).' AND id Not IN (SELECT Top ' . $this->website->db('web')->escape((int)$this->per_page) . ' id FROM DmN_Muun_Market WHERE active_till > GETDATE() AND sold != 1  AND removed != 1 AND server = '.$this->website->db('web')->escape($server).' ORDER BY id DESC) ORDER BY id DESC');
            $this->pos = ($page == 1) ? 1 : (int)(($page - 1) * $per_page) + 1;
			$data = [];
            foreach($this->items->fetch_all() as $value){
				$this->iteminfo->isMuun(true);
				$this->iteminfo->itemData($value['item'], true, $value['server']);
                $data[] = [
					'icon' => (date("F j, Y", strtotime($value['add_date'])) == date("F j, Y", time())) ? '<img src="' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/images/new.png" />' : $this->pos, 
					'price' => round(($value['price'] / 100) * $tax + $value['price']) . ' ' . $this->website->translate_credits($value['price_type'], $server), 
					'item' => $value['item'], 
					'name' => $this->iteminfo->getNameStyle(true), 
					'namenostyle' => $this->iteminfo->realName(),
					'id' => $value['id'], 
					'pos' => $this->pos, 
					'seller' => $value['seller'], 
					'end' => date("F j, Y", strtotime($value['active_till']))
					
				];
                $this->pos++;
            }
            return $data;
        }

				
		public function count_total_history_items(){
            $this->total = $this->website->db('web')->snumrows('SELECT COUNT(item) AS count FROM DmN_Muun_Market WHERE seller_acc = '.$this->website->db('web')->escape($this->session->userdata(['user' => 'username'])).' AND server = '.$this->website->db('web')->escape($this->session->userdata(['user' => 'server'])).'');
        }

				
		public function load_market_history($page, $per_page = 25, $tax = 0){
            $this->per_page = ($page <= 1) ? 0 : $per_page * ($page - 1);
            $this->items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->escape((int)$per_page) . ' id, item, price, price_type, active_till, sold, removed, seller, server FROM DmN_Muun_Market WHERE seller_acc = '.$this->website->db('web')->escape($this->session->userdata(['user' => 'username'])).' AND server = '.$this->website->db('web')->escape($this->session->userdata(['user' => 'server'])).' AND id Not IN (SELECT Top ' . $this->website->db('web')->escape((int)$this->per_page) . ' id FROM DmN_Muun_Market WHERE seller_acc = '.$this->website->db('web')->escape($this->session->userdata(['user' => 'username'])).' AND server = '.$this->website->db('web')->escape($this->session->userdata(['user' => 'server'])).' ORDER BY id DESC) ORDER BY id DESC');
            $this->pos = ($page == 1) ? 1 : (int)(($page - 1) * $per_page) + 1;
            $data = [];
			foreach($this->items->fetch_all() as $value){
				$this->iteminfo->isMuun(true);
                $this->iteminfo->itemData($value['item'], true, $value['server']);
                $data[] = [
					'price' => round(($value['price'] / 100) * $tax + $value['price']) . ' ' . $this->website->translate_credits($value['price_type'], $value['server']), 
					'item' => $value['item'], 
					'name' => $this->iteminfo->getNameStyle(true), 
					'id' => $value['id'], 
					'pos' => $this->pos, 
					'seller' => $value['seller'], 
					'active' => $value['active_till'], 
					'sold' => $value['sold'], 
					'removed' => $value['removed']
				];
                $this->pos++;
            }
            return $data;
        }

		public function update_sale_set_purchased($id, $buyer, $acc){
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Muun_Market SET sold = 1, buyer = :buyer, buyer_acc = :acc, purchase_date = GETDATE() WHERE id = :id');
            return $stmt->execute([':buyer' => $buyer, ':acc' => $acc, ':id' => $id]);
        }

		public function update_sale_set_removed($id, $buyer){
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Muun_Market SET removed = 1, buyer = :buyer WHERE id = :id');
            return $stmt->execute([':buyer' => $buyer, ':id' => $id]);
        }

        public function check_sale_in_market($id, $server){
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 add_date, active_till, server, price, price_type, item, sold, seller, removed, seller_acc FROM DmN_Muun_Market WITH (UPDLOCK) WHERE id = :id AND server = :server');
            $stmt->execute([':id' => $id, ':server' => $server]);
            return $stmt->fetch();
        }

        public function get_guid($user = '', $server){
            $stmt = $this->website->db('account', $server)->prepare('SELECT memb_guid FROM MEMB_INFO WHERE memb___id = :user');
            $stmt->execute([':user' => $user]);
            $info = $stmt->fetch();
            return $info['memb_guid'];
        }

        public function add_account_log($log, $credits, $acc, $server){
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Account_Logs (text, amount, date, account, server, ip) VALUES (:text, :amount, GETDATE(), :acc, :server, :ip)');
            $stmt->execute([':text' => $log, ':amount' => $credits, ':acc' => $acc, ':server' => $server, ':ip' => ip()]);
            $stmt->close_cursor();
        }
		
		public function load_logs($page = 1, $per_page = 25, $acc = '', $server = 'All'){
            if(($acc == '' || $acc == '-') && $server == 'All')
                $items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->escape((int)$per_page) . '  id, server, price, price_type, item, seller_acc, buyer_acc, purchase_date FROM DmN_Muun_Market WHERE sold = 1 AND id Not IN (SELECT Top ' . $this->website->db('web')->escape((int)($per_page * ($page - 1))) . ' id FROM DmN_Muun_Market WHERE sold = 1 ORDER BY id DESC) ORDER BY id DESC'); 
			else{
                if(($acc != '' && $acc != '-') && $server == 'All')
                    $items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->escape((int)$per_page) . ' id, server, price, price_type, item, seller_acc, buyer_acc, purchase_date FROM DmN_Muun_Market WHERE sold = 1 AND (seller_acc like \'%' . $this->website->db('web')->escape($acc) . '%\' OR buyer_acc like \'%' . $this->website->db('web')->escape($acc) . '%\') AND id Not IN (SELECT Top ' . $this->website->db('web')->escape((int)($per_page * ($page - 1))) . ' id FROM DmN_Muun_Market WHERE sold = 1 AND (seller_acc like \'%' . $this->website->db('web')->escape($acc) . '%\' OR buyer_acc like \'%' . $this->website->db('web')->escape($acc) . '%\') ORDER BY id DESC) ORDER BY id DESC'); else
				$items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->escape((int)$per_page) . ' id, server, price, price_type, item, seller_acc, buyer_acc, purchase_date FROM DmN_Muun_Market WHERE sold = 1  AND (seller_acc like \'%' . $this->website->db('web')->escape($acc) . '%\' OR buyer_acc like \'%' . $this->website->db('web')->escape($acc) . '%\') AND server = '.$this->website->db('web')->escape($server).' AND id Not IN (SELECT Top ' . $this->website->db('web')->escape((int)($per_page * ($page - 1))) . ' id DmN_Muun_Market WHERE sold = 1 AND (seller_acc like \'%' . $this->website->db('web')->escape($acc) . '%\' OR buyer_acc like \'%' . $this->website->db('web')->escape($acc) . '%\') AND server = '.$this->website->db('web')->escape($server).' ORDER BY id DESC) ORDER BY id DESC');
            }
			$logs = [];
            foreach($items->fetch_all() as $value){
				$this->iteminfo->isMuun(true);
                $this->iteminfo->itemData($value['item'], true, $value['server']);
				$logs[] = [
					'price' => $value['price'], 
					'price_type' => $this->website->translate_credits($value['price_type'], $value['server']), 
					'item' => $value['item'], 
					'name' => $this->iteminfo->getNameStyle(true), 
					'seller' => htmlspecialchars($value['seller_acc']), 
					'buyer' => htmlspecialchars($value['buyer_acc']), 
					'server' => htmlspecialchars($value['server']), 
					'purchase_date' => date(DATETIME_FORMAT, strtotime($value['purchase_date']))
				];
            }
            return $logs;
        }

        public function count_total_logs($acc = '', $server = 'All'){
            $sql = 'WHERE sold = 1';
            if($acc != '' && $acc != '-'){
                $sql .= ' AND(seller_acc like \'%' . $this->website->db('web')->escape($acc) . '%\' OR buyer_acc like \'%' . $this->website->db('web')->escape($acc) . '%\')';
                if($server != 'All'){
                    $sql .= ' AND server = '.$this->website->db('web')->escape($server).'';
                }
            }
            $count = $this->website->db('web')->snumrows('SELECT COUNT(id) AS count FROM DmN_Muun_Market ' . $sql . '');
            return $count;
        }
		
		public function check_connect_stat($account, $server){
            $stmt = $this->website->db('account', $server)->prepare('SELECT ConnectStat FROM MEMB_STAT WHERE memb___id = :user');
            $stmt->execute([':user' => $account]);
            if($status = $stmt->fetch()){
                return ($status['ConnectStat'] == 0);
            }
            return true;
        }
    }
