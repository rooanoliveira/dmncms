<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url . ACPURL; ?>">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url . ACPURL; ?>/manage-settings/vip">Vip Settings</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Add Vip Package</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(isset($error)){
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                ?>
                <form class="form-horizontal" method="POST" action="" id="vip_package_form">
                    <div class="control-group">
                        <label class="control-label" for="package_title">Package Title <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="package_title" id="package_title"
                                   value="<?php if(isset($_POST['package_title'])): echo $_POST['package_title']; endif; ?>"
                                   required/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="price">Package Price <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="price" id="price"
                                   value="<?php if(isset($_POST['price'])): echo $_POST['price']; endif; ?>"
                                   pattern="\d*" required title="Allowed only numbers"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="payment_type">Payment Method <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <select id="payment_type" name="payment_type" required>
                                <option value="1" <?php if(isset($_POST['payment_type']) && $_POST['payment_type'] == 1): echo 'selected="selected"'; endif; ?>>
                                    Credits 1
                                </option>
                                <option value="2 "<?php if(isset($_POST['payment_type']) && $_POST['payment_type'] == 2): echo 'selected="selected"'; endif; ?>>
                                    Credits 2
                                </option>
                            </select>
                            <p class="help-block">Which donation points will be used for payment.</p>
                            <p>For reward types check your credits settings <a
                                        href="<?php echo $this->config->base_url . ACPURL; ?>/manage-settings/credits"
                                        target="_blank">here</a></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="server">Server <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <select id="server" name="server" required>
                                <?php foreach($servers as $key => $server): ?>
                                    <option value="<?php echo $key; ?>"
                                            <?php if(isset($_POST['server']) && $key == $_POST['server']){ ?>selected="selected"<?php } ?>><?php echo $servers[$key]['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="vip_time">Vip Time <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <select id="vip_time" name="vip_time" required class="input-small">
                                <?php for($time = 1; $time <= 31; $time++): ?>
                                    <option value="<?php echo $time; ?>"
                                            <?php if(isset($_POST['vip_time']) && $time == $_POST['vip_time']){ ?>selected="selected"<?php } ?>><?php echo $time; ?></option>
                                <?php endfor; ?>
                            </select>
                            <select id="vip_time_type" name="vip_time_type" required class="input-medium">
                                <option value="1"
                                        <?php if(isset($_POST['vip_time_type']) && 1 == $_POST['vip_time_type']){ ?>selected="selected"<?php } ?>>
                                    Days
                                </option>
                                <option value="2"
                                        <?php if(isset($_POST['vip_time_type']) && 2 == $_POST['vip_time_type']){ ?>selected="selected"<?php } ?>>
                                    Weeks
                                </option>
                                <option value="3"
                                        <?php if(isset($_POST['vip_time_type']) && 3 == $_POST['vip_time_type']){ ?>selected="selected"<?php } ?>>
                                    Months
                                </option>
                                <option value="4"
                                        <?php if(isset($_POST['vip_time_type']) && 4 == $_POST['vip_time_type']){ ?>selected="selected"<?php } ?>>
                                    Years
                                </option>
                            </select>
                        </div>
                    </div>
					 <div class="control-group">
                        <label class="control-label" for="allow_extend">Allow Extending <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <select id="allow_extend" name="allow_extend" required>
                                <option value="1" <?php if(isset($_POST['allow_extend']) && $_POST['allow_extend'] == 1): echo 'selected="selected"'; endif; ?>>
                                    Yes
                                </option>
                                <option value="0 "<?php if(isset($_POST['allow_extend']) && $_POST['allow_extend'] == 0): echo 'selected="selected"'; endif; ?>>
                                   No
                                </option>
                            </select>
                            <p class="help-block">Allow extending vip package time, while not expired previous time.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="reset_price_decrease">Reset Zen Decrease </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="reset_price_decrease"
                                   id="reset_price_decrease"
                                   value="<?php if(isset($_POST['reset_price_decrease'])): echo $_POST['reset_price_decrease']; endif; ?>"
                                   placeholder="0" pattern="\d*" title="Allowed only numbers"/>
                            <p class="help-block">Formula: req reset zen - vip reset zen decrease</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="reset_level_decrease">Reset Level Decrease </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="reset_level_decrease"
                                   id="reset_level_decrease"
                                   value="<?php if(isset($_POST['reset_level_decrease'])): echo $_POST['reset_level_decrease']; endif; ?>"
                                   placeholder="0" pattern="\d*" title="Allowed only numbers"/>
                            <p class="help-block">Formula: req reset lvl - vip reset level decrease</p>
                        </div>
                    </div>
					<div class="control-group">
                        <label class="control-label" for="reset_bonus_levelup">Reset Bonus LevelUp points </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="reset_bonus_levelup"
                                   id="reset_bonus_levelup"
                                   value="<?php if(isset($_POST['reset_bonus_levelup'])): echo $_POST['reset_bonus_levelup']; endif; ?>"
                                   placeholder="0" pattern="\d*" title="Allowed only numbers"/>
                            <p class="help-block">Formula: req level up points + vip reset level up points</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="grand_reset_bonus_credits">Grand Reset Bonus Credits </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="grand_reset_bonus_credits"
                                   id="grand_reset_bonus_credits"
                                   value="<?php if(isset($_POST['grand_reset_bonus_credits'])): echo $_POST['grand_reset_bonus_credits']; endif; ?>"
                                   placeholder="0" pattern="\d*" title="Allowed only numbers"/>
                            <p class="help-block">Formula: grand reset bonus credits + vip bonus credits</p>
                        </div>
                    </div>
					<div class="control-group">
                        <label class="control-label" for="grand_reset_bonus_gcredits">Grand Reset Bonus Gold Credits </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="grand_reset_bonus_gcredits"
                                   id="grand_reset_bonus_gcredits"
                                   value="<?php if(isset($_POST['grand_reset_bonus_gcredits'])): echo $_POST['grand_reset_bonus_gcredits']; endif; ?>"
                                   placeholder="0" pattern="\d*" title="Allowed only numbers"/>
                            <p class="help-block">Formula: grand reset bonus gold credits + vip bonus credits</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="hide_info_discount">Hide Info Discount </label>
                        <div class="controls">
                            <select name="hide_info_discount" id="hide_info_discount">
                                <?php for($i = 0; $i <= 100; $i++): ?>
                                    <option value="<?php echo $i; ?>"
                                            <?php if(isset($_POST['hide_info_discount']) && $i == $_POST['hide_info_discount']){ ?>selected="selected"<?php } ?>><?php echo $i; ?>
                                        %
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <p class="help-block">Formula: hide info price - hide info discount percents</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="change_name_discount">Change Name Discount </label>
                        <div class="controls">
                            <select name="change_name_discount" id="change_name_discount">
                                <?php for($i = 0; $i <= 100; $i++): ?>
                                    <option value="<?php echo $i; ?>"
                                            <?php if(isset($_POST['change_name_discount']) && $i == $_POST['change_name_discount']){ ?>selected="selected"<?php } ?>><?php echo $i; ?>
                                        %
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <p class="help-block">Formula: change name price - change name discount percents</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="change_class_discount">Change Class Discount </label>
                        <div class="controls">
                            <select name="change_name_discount" id="change_class_discount">
                                <?php for($i = 0; $i <= 100; $i++): ?>
                                    <option value="<?php echo $i; ?>"
                                            <?php if(isset($_POST['change_class_discount']) && $i == $_POST['change_class_discount']){ ?>selected="selected"<?php } ?>><?php echo $i; ?>
                                        %
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <p class="help-block">Formula: change class price - change class discount percents</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pk_clear_discount">PK Clear Zen Decrease </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="pk_clear_discount" id="pk_clear_discount"
                                   value="<?php if(isset($_POST['pk_clear_discount'])): echo $_POST['pk_clear_discount']; endif; ?>"
                                   placeholder="0" pattern="\d*" title="Allowed only numbers"/>
                            <p class="help-block">Formula: pk clear price - vip pk clear zen decrease</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="clear_skilltree_discount">SkillTree Reset Discount </label>
                        <div class="controls">
                            <select name="clear_skilltree_discount" id="clear_skilltree_discount">
                                <?php for($i = 0; $i <= 100; $i++): ?>
                                    <option value="<?php echo $i; ?>"
                                            <?php if(isset($_POST['clear_skilltree_discount']) && $i == $_POST['clear_skilltree_discount']){ ?>selected="selected"<?php } ?>><?php echo $i; ?>
                                        %
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <p class="help-block">Formula: skill tree reset price - skill tree discount percents</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="online_hour_exchange_bonus">Online hour exchange
                            bonus </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="online_hour_exchange_bonus"
                                   id="online_hour_exchange_bonus"
                                   value="<?php if(isset($_POST['online_hour_exchange_bonus'])): echo $_POST['online_hour_exchange_bonus']; endif; ?>"
                                   placeholder="0" pattern="\d*" title="Allowed only numbers"/>
                            <p class="help-block">Formula: online hour reward + online hour exchange bonus</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="bonus_credits_for_donate">Bonus Credits For Donation </label>
                        <div class="controls">
                            <select name="bonus_credits_for_donate" id="bonus_credits_for_donate">
                                <?php for($i = 0; $i <= 100; $i++): ?>
                                    <option value="<?php echo $i; ?>"
                                            <?php if(isset($_POST['bonus_credits_for_donate']) && $i == $_POST['bonus_credits_for_donate']){ ?>selected="selected"<?php } ?>><?php echo $i; ?>
                                        %
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <p class="help-block">Formula: Donation credits + bonus credits percents</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="shop_discount">Shop Discount </label>
                        <div class="controls">
                            <select name="shop_discount" id="shop_discount">
                                <?php for($i = 0; $i <= 100; $i++): ?>
                                    <option value="<?php echo $i; ?>"
                                            <?php if(isset($_POST['shop_discount']) && $i == $_POST['shop_discount']){ ?>selected="selected"<?php } ?>><?php echo $i; ?>
                                        %
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <p class="help-block">Formula: Shop item price - shop discount percents</p>
                        </div>
                    </div>
					<div class="control-group">
                        <label class="control-label" for="add_wcoins">Add Wcoins </label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="add_wcoins"
                                   id="add_wcoins"
                                   value="<?php if(isset($_POST['add_wcoins'])): echo $_POST['add_wcoins']; endif; ?>"
                                   placeholder="0" pattern="\d*" title="Allowed only numbers"/>
                            <p class="help-block">Amount of wcoins to add for uuser on vip purchase</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="connect_member_load">Connect Member Load</label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="connect_member_load" id="connect_member_load"
                                   value="<?php if(isset($_POST['connect_member_load'])): echo $_POST['connect_member_load']; endif; ?>"/>
                            <p class="help-block">Add account into connectmember.txt or IGC_ConnectMember.xml</p>
                            <p class="help-block">Example: C:/muserver/data/connectmember.txt</p>
                            <p class="help-block">This option works only if website is hosted on same server </p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="server_vip_package">Server Vip </label>
                        <div class="controls">
                            <?php
                                $options = '<option value="">None</option>';
                                foreach($query_data['quearies'] AS $key => $value){
                                    if(!empty($value['vip_codes'])){
                                        foreach($value['vip_codes'] AS $vip => $code){
                                            $options .= '<option value="' . $key . '|' . $vip . '">' . $value['title'] . ' ' . $code['title'] . '</option>';
                                        }
                                    } else{
                                        $options .= '<option value="' . $key . '">' . $value['title'] . '</option>';
                                    }
                                }
                            ?>

                            <select name="server_vip_package" id="server_vip_package">
                                <?php echo $options; ?>
                            </select>
                            <p class="help-block">Add server side vip package</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="server_bonus_info">Server Vip Info </label>
                        <div class="controls">
                            <textarea class="cleditor" id="server_bonus_info" name="server_bonus_info"
                                      rows="8"><?php if(isset($_POST['server_bonus_info'])): echo $_POST['server_bonus_info']; endif; ?></textarea>
                            <p class="help-block">Additional bonus info for server vip package</p>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="add_vip_package" id="add_vip_package">Add
                            package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
